package corerunner

import (
	"bufio"
	"bytes"
	"errors"
	"fmt"
	"io"
	"log"
	"os/exec"
	"strconv"
	"strings"
	"sync"
	"time"
)

const (
	// Размер пакета данных (в байтах) для общения между Go и запускаемым
	// процессом. Чем больше значение, тем меньше будет итераций при
	// передачи большого количества данных, но есть возможость упереться в
	// ограничения транспорта.
	// https://unix.stackexchange.com/questions/11946/how-big-is-the-pipe-buffer
	PipeChunkSize = 2048
)

type Pool struct {
	pool    []*Worker
	lastWrk int
	mu      sync.Mutex
}

// Start запускает n процессов, указанных в argv с переменными окружения env.
// Повторный запуск возможен только после выполнения Stop.
func (p *Pool) Start(argv []string, n int, env []string) error {
	if len(p.pool) != 0 {
		return errors.New("already started")
	}
	var wg sync.WaitGroup
	wg.Add(n)
	var werr error
	for i := 0; i < n; i++ {
		go func() {
			defer wg.Done()
			wrk := NewWorker()
			start := time.Now()
			err := wrk.Start(argv, env)
			p.mu.Lock()
			defer p.mu.Unlock()
			if err != nil {
				werr = err
				return
			}
			p.pool = append(p.pool, wrk)
			log.Printf(
				"PID %d: worker started in %s",
				wrk.cmd.Process.Pid,
				time.Since(start),
			)
		}()
	}
	wg.Wait()
	return werr
}

// Send отправляет данные следующему в очереди процессу для обработки и
// возвращает ответ из него. Если данные не получены через timeout, процесс
// перезапускается и возвращается ошибка.
func (p *Pool) Send(data []byte, timeout time.Duration) chan WorkerResult {
	return p.getWorker().Send(data, timeout)
}

// Stop останавливает все запущенные процессы и очищает пул.
func (p *Pool) Stop() {
	for _, wrk := range p.pool {
		wrk.Stop()
	}
	p.pool = []*Worker{}
	p.lastWrk = 0
}

func (p *Pool) getWorker() *Worker {
	p.mu.Lock()
	defer p.mu.Unlock()

	res := p.pool[p.lastWrk]
	p.lastWrk = (p.lastWrk + 1) % len(p.pool)
	return res
}

type Worker struct {
	cmd     *exec.Cmd
	read    *bufio.Reader
	write   *bufio.Writer
	mu      sync.Mutex
	argv    []string
	env     []string
	queue   chan WorkerJob
	looping bool
}

type WorkerResult struct {
	Res []byte
	Err error
}

type WorkerJob struct {
	data    []byte
	timeout time.Duration
	res     chan WorkerResult
}

func NewWorker() *Worker {
	return &Worker{
		queue: make(chan WorkerJob, 512),
	}
}

// Start запускает процесс с указанными аргументами argv. Этот метод не
// дожидается завершения процесса.
// XXX: переделать в блокирующий метод? Будет проще отслеживать завершение
// процесса (сейчас это реализовано отловом EOF в любом из pipe'ов).
// exec.Run(), судя по всему, не дает параллельно читать pipe'ы, как и
// последовательный запуск exec.Start() и exec.Wait().
func (wrk *Worker) Start(argv []string, env []string) error {
	if wrk.cmd != nil {
		return errors.New("already started")
	}

	wrk.argv = argv
	wrk.env = env
	var cmd *exec.Cmd
	if len(argv) == 1 {
		cmd = exec.Command(argv[0])
	} else {
		cmd = exec.Command(argv[0], argv[1:]...)
	}
	cmd.Env = env
	stdout, err := cmd.StdoutPipe()
	if err != nil {
		return err
	}
	wrk.read = bufio.NewReader(stdout)

	stdin, err := cmd.StdinPipe()
	if err != nil {
		return err
	}
	wrk.write = bufio.NewWriter(stdin)

	stderr, err := cmd.StderrPipe()
	if err != nil {
		return err
	}
	errReader := bufio.NewReader(stderr)
	// Параллельно запускаем чтение и вывод ошибок приложения.
	go func() {
		for {
			l, err := errReader.ReadString('\n')
			if err != nil {
				if err != io.EOF {
					log.Println("logging error: ", err)
				}
				break
			}
			log.Print(l)
		}
	}()

	if err = cmd.Start(); err != nil {
		return err
	}
	ok, err := wrk.read.ReadString('\n')
	if err != nil {
		return err
	}
	if string(ok) != "ok\n" {
		msg, _ := io.ReadAll(wrk.read)
		return errors.New(string(msg))
	}
	wrk.cmd = cmd
	go wrk.jobLoop()

	return nil
}

func (wrk *Worker) jobLoop() {
	if wrk.looping == true {
		return
	}
	wrk.looping = true
	for {
		select {
		case job := <-wrk.queue:
			job.res <- *wrk.timedSend(job.data, job.timeout)
		}
	}
}

// Stop останавливает процесс и закрывает все соответствующие буферы
// чтения/записи.
func (wrk *Worker) Stop() error {
	wrk.mu.Lock()
	defer wrk.mu.Unlock()

	if wrk.cmd == nil {
		return errors.New("Worker is not running")
	}
	var err error
	if _, err := wrk.write.Write([]byte("\n")); err != nil {
		return err
	}
	if err = wrk.write.Flush(); err != nil {
		return err
	}
	if err = wrk.cmd.Wait(); err != nil {
		return err
	}
	wrk.reset()
	return nil
}

// Wait ждет завершения процесса и закрывает все соответствующие буферы
// чтения/записи.
func (wrk *Worker) Wait() error {
	wrk.mu.Lock()
	defer wrk.mu.Unlock()

	if wrk.cmd == nil {
		return errors.New("Worker is not running")
	}
	if err := wrk.cmd.Wait(); err != nil {
		return err
	}
	wrk.reset()
	return nil
}

// != nil Kill посылает SIGKILL процессу и закрывает все соответствующие буферы
// чтения/записи.
func (wrk *Worker) Kill() error {
	wrk.mu.Lock()
	defer wrk.mu.Unlock()

	if wrk.cmd == nil {
		return errors.New("Worker is not running")
	}
	if err := wrk.cmd.Process.Kill(); err != nil {
		return err
	}
	if _, err := wrk.cmd.Process.Wait(); err != nil {
		return err
	}
	wrk.reset()
	return nil
}

// send отправляет данные процессу для обработки, дожидается ответа и
// возвращает его.
func (wrk *Worker) Send(data []byte, timeout time.Duration) chan WorkerResult {
	res := make(chan WorkerResult)
	wrk.queue <- WorkerJob{data: data, res: res, timeout: timeout}
	return res
}

// send отправляет данные процессу для обработки, дожидается ответа и
// возвращает его.
func (wrk *Worker) send(data []byte) ([]byte, error) {
	wrk.mu.Lock()
	defer wrk.mu.Unlock()

	err := wrk.writeMsg(data)
	if err != nil {
		log.Println("write error:", err)
		return nil, err
	}
	res, err := wrk.readMsg()
	if err != nil {
		log.Println("read error:", err)
		return nil, err
	}

	return res, nil
}

// Restart перезапускает процесс. Если kill = true, то процессу посылается
// SIGKILL, иначе ожидается его естественное завершение.
func (wrk *Worker) Restart(kill bool) error {
	var err error
	if wrk.cmd != nil && wrk.cmd.Process != nil {
		if kill {
			if err = wrk.cmd.Process.Kill(); err != nil {
				return err
			}
		}
		if err = wrk.cmd.Wait(); err != nil {
			log.Println("restart wait error:", err)
		}
	}
	argv := wrk.argv
	env := wrk.env
	wrk.reset()
	return wrk.Start(argv, env)
}

func (wrk *Worker) readMsg() ([]byte, error) {
	if wrk.read == nil {
		return nil, errors.New("read pipe is not started")
	}
	// Считываем длину сообщения.
	l, err := wrk.read.ReadString('\n')
	if err != nil {
		return nil, err
	}
	ln, err := strconv.Atoi(strings.TrimSuffix(l, "\n"))
	if err != nil {
		return nil, err
	}
	// Читаем ln байт сообщения частями размером PipeChunkSize.
	buf := &bytes.Buffer{}
	for ln > 0 {
		n, err := io.CopyN(buf, wrk.read, int64(min(ln, PipeChunkSize)))
		if err != nil {
			return nil, err
		}
		ln -= int(n)
	}
	return buf.Bytes(), nil
}

func (wrk *Worker) writeMsg(data []byte) error {
	if wrk.write == nil {
		return errors.New("write pipe is not started")
	}
	// Записываем длину сообщения.
	dlen := len(data)
	_, err := wrk.write.WriteString(strconv.Itoa(dlen) + "\n")
	if err != nil {
		return err
	}
	// Записываем сообщение по частям размером PipeChunkSize.
	ptr := 0
	for ptr < dlen {
		_, err = wrk.write.Write(data[ptr:min(ptr+PipeChunkSize, dlen)])
		if err != nil {
			return err
		}
		ptr += PipeChunkSize
	}
	return wrk.write.Flush()
}

func (wrk *Worker) reset() {
	wrk.cmd = nil
	wrk.write = nil
	wrk.read = nil
}

func (wrk *Worker) timedSend(data []byte, timeout time.Duration) *WorkerResult {
	ch := make(chan *WorkerResult)
	timer := time.NewTimer(timeout)
	go func() {
		res, err := wrk.send(data)
		ch <- &WorkerResult{Res: res, Err: err}
	}()

	select {
	// Ответ пришел до таймаута.
	case res := <-ch:
		timer.Stop()
		if res.Err != nil {
			wrk.Restart(true)
		}
		return res
	// Таймаут.
	case <-timer.C:
		wrk.Restart(true)
		return &WorkerResult{
			nil,
			fmt.Errorf(
				"worker timed out after %s",
				timeout,
			),
		}
	}
}
