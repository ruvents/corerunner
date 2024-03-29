<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace Runner\Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>runner.messages.Request</code>
 */
class Request extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string method = 1;</code>
     */
    protected $method = '';
    /**
     * Generated from protobuf field <code>string url = 2;</code>
     */
    protected $url = '';
    /**
     * Generated from protobuf field <code>map<string, string> headers = 3;</code>
     */
    private $headers;
    /**
     * Generated from protobuf field <code>bytes body = 4;</code>
     */
    protected $body = '';
    /**
     * Generated from protobuf field <code>map<string, .runner.messages.File> files = 5;</code>
     */
    private $files;
    /**
     * Generated from protobuf field <code>map<string, string> form = 6;</code>
     */
    private $form;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $method
     *     @type string $url
     *     @type array|\Google\Protobuf\Internal\MapField $headers
     *     @type string $body
     *     @type array|\Google\Protobuf\Internal\MapField $files
     *     @type array|\Google\Protobuf\Internal\MapField $form
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Messages::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string method = 1;</code>
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Generated from protobuf field <code>string method = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setMethod($var)
    {
        GPBUtil::checkString($var, True);
        $this->method = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string url = 2;</code>
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Generated from protobuf field <code>string url = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setUrl($var)
    {
        GPBUtil::checkString($var, True);
        $this->url = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, string> headers = 3;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Generated from protobuf field <code>map<string, string> headers = 3;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setHeaders($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::STRING);
        $this->headers = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>bytes body = 4;</code>
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Generated from protobuf field <code>bytes body = 4;</code>
     * @param string $var
     * @return $this
     */
    public function setBody($var)
    {
        GPBUtil::checkString($var, False);
        $this->body = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, .runner.messages.File> files = 5;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Generated from protobuf field <code>map<string, .runner.messages.File> files = 5;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setFiles($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \Runner\Messages\File::class);
        $this->files = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, string> form = 6;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Generated from protobuf field <code>map<string, string> form = 6;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setForm($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::STRING);
        $this->form = $arr;

        return $this;
    }

}

