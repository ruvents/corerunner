<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace Runner\Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>runner.messages.JobRequest</code>
 */
class JobRequest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string name = 1;</code>
     */
    protected $name = '';
    /**
     * Generated from protobuf field <code>string payload = 2;</code>
     */
    protected $payload = '';
    /**
     * В мс.
     *
     * Generated from protobuf field <code>uint64 timeout = 3;</code>
     */
    protected $timeout = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $name
     *     @type string $payload
     *     @type int|string $timeout
     *           В мс.
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Messages::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Generated from protobuf field <code>string name = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setName($var)
    {
        GPBUtil::checkString($var, True);
        $this->name = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string payload = 2;</code>
     * @return string
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Generated from protobuf field <code>string payload = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setPayload($var)
    {
        GPBUtil::checkString($var, True);
        $this->payload = $var;

        return $this;
    }

    /**
     * В мс.
     *
     * Generated from protobuf field <code>uint64 timeout = 3;</code>
     * @return int|string
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * В мс.
     *
     * Generated from protobuf field <code>uint64 timeout = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setTimeout($var)
    {
        GPBUtil::checkUint64($var);
        $this->timeout = $var;

        return $this;
    }

}

