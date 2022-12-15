<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: proto/request.proto

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>Request</code>
 */
class Request extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string httpVersion = 1;</code>
     */
    protected $httpVersion = '';
    /**
     * Generated from protobuf field <code>string method = 2;</code>
     */
    protected $method = '';
    /**
     * Generated from protobuf field <code>string path = 3;</code>
     */
    protected $path = '';
    /**
     * Generated from protobuf field <code>map<string, .List> headers = 4;</code>
     */
    private $headers;
    /**
     * Generated from protobuf field <code>map<string, .List> query = 5;</code>
     */
    private $query;
    /**
     * Generated from protobuf field <code>string body = 6;</code>
     */
    protected $body = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $httpVersion
     *     @type string $method
     *     @type string $path
     *     @type array|\Google\Protobuf\Internal\MapField $headers
     *     @type array|\Google\Protobuf\Internal\MapField $query
     *     @type string $body
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Proto\Request::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string httpVersion = 1;</code>
     * @return string
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     * Generated from protobuf field <code>string httpVersion = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setHttpVersion($var)
    {
        GPBUtil::checkString($var, True);
        $this->httpVersion = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string method = 2;</code>
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Generated from protobuf field <code>string method = 2;</code>
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
     * Generated from protobuf field <code>string path = 3;</code>
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Generated from protobuf field <code>string path = 3;</code>
     * @param string $var
     * @return $this
     */
    public function setPath($var)
    {
        GPBUtil::checkString($var, True);
        $this->path = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, .List> headers = 4;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Generated from protobuf field <code>map<string, .List> headers = 4;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setHeaders($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \PBList::class);
        $this->headers = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>map<string, .List> query = 5;</code>
     * @return \Google\Protobuf\Internal\MapField
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Generated from protobuf field <code>map<string, .List> query = 5;</code>
     * @param array|\Google\Protobuf\Internal\MapField $var
     * @return $this
     */
    public function setQuery($var)
    {
        $arr = GPBUtil::checkMapField($var, \Google\Protobuf\Internal\GPBType::STRING, \Google\Protobuf\Internal\GPBType::MESSAGE, \PBList::class);
        $this->query = $arr;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string body = 6;</code>
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Generated from protobuf field <code>string body = 6;</code>
     * @param string $var
     * @return $this
     */
    public function setBody($var)
    {
        GPBUtil::checkString($var, True);
        $this->body = $var;

        return $this;
    }

}

