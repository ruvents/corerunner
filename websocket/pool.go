package websocket

import (
	"runner"
	"sync"
)

// Опциональный пул соединений, используемый для подписки на топики и рассылки
// сообщений по ним.
type Pool struct {
	// Список соединений, подписанных на топик.
	topics map[string]map[*Connection]bool
	// Список топиков, на которые подписано соединение. Нужно для
	// эффективного закрытия соединения с отпиской от всех топиков.
	pointers map[runner.UUID4][]string
	mu       sync.Mutex
}

func NewPool() Pool {
	return Pool{
		topics:   make(map[string]map[*Connection]bool),
		pointers: make(map[runner.UUID4][]string),
	}
}

func (p *Pool) Subscribe(conn *Connection, topic string) {
	p.mu.Lock()
	defer p.mu.Unlock()
	if _, ok := p.topics[topic]; !ok {
		p.topics[topic] = make(map[*Connection]bool)
	}
	p.topics[topic][conn] = true
	if _, ok := p.pointers[conn.ID]; !ok {
		p.pointers[conn.ID] = make([]string, 0)
	}
	p.pointers[conn.ID] = append(p.pointers[conn.ID], topic)
}

func (p *Pool) Remove(conn *Connection) {
	p.mu.Lock()
	defer p.mu.Unlock()
	if _, ok := p.pointers[conn.ID]; !ok {
		return
	}
	for _, topic := range p.pointers[conn.ID] {
		delete(p.topics[topic], conn)
		if len(p.topics[topic]) == 0 {
			delete(p.topics, topic)
		}
	}
	delete(p.pointers, conn.ID)
}

func (p *Pool) Publish(topic string, msg []byte, except runner.UUID4) {
	p.mu.Lock()
	defer p.mu.Unlock()
	if _, ok := p.topics[topic]; !ok {
		return
	}
	// Копируем соедиения в отдельную переменную, чтобы не блокировать весь
	// пул рассылкой.
	conns := make([]*Connection, len(p.topics[topic]))
	for conn := range p.topics[topic] {
		conns = append(conns, conn)
	}
	go p.publish(conns, msg, except)
}

func (p *Pool) publish(conns []*Connection, msg []byte, except runner.UUID4) {
	for _, conn := range conns {
		if conn == nil || conn.ID == except {
			continue
		}
		conn.Send(msg)
	}
}
