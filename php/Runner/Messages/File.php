<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace Runner\Messages;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>runner.messages.File</code>
 */
class File extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string filename = 1;</code>
     */
    protected $filename = '';
    /**
     * Generated from protobuf field <code>string tmpPath = 2;</code>
     */
    protected $tmpPath = '';
    /**
     * Generated from protobuf field <code>int64 size = 3;</code>
     */
    protected $size = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $filename
     *     @type string $tmpPath
     *     @type int|string $size
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Messages::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string filename = 1;</code>
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Generated from protobuf field <code>string filename = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setFilename($var)
    {
        GPBUtil::checkString($var, True);
        $this->filename = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string tmpPath = 2;</code>
     * @return string
     */
    public function getTmpPath()
    {
        return $this->tmpPath;
    }

    /**
     * Generated from protobuf field <code>string tmpPath = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setTmpPath($var)
    {
        GPBUtil::checkString($var, True);
        $this->tmpPath = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 size = 3;</code>
     * @return int|string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Generated from protobuf field <code>int64 size = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setSize($var)
    {
        GPBUtil::checkInt64($var);
        $this->size = $var;

        return $this;
    }

}

