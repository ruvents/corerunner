<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: messages.proto

namespace GPBMetadata;

class Messages
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            '
�
messages.protorunner.messages"�
Request
method (	
url (	6
headers (2%.runner.messages.Request.HeadersEntry
body (2
files (2#.runner.messages.Request.FilesEntry0
form (2".runner.messages.Request.FormEntry.
HeadersEntry
key (	
value (	:8C

FilesEntry
key (	$
value (2.runner.messages.File:8+
	FormEntry
key (	
value (	:8"�
Response

statusCode (7
headers (2&.runner.messages.Response.HeadersEntry
body (.
HeadersEntry
key (	
value (	:8"7
File
filename (	
tmpPath (	
size ("<

JobRequest
name (	
payload (	
timeout (B
Zmessage/bproto3'
        , true);

        static::$is_initialized = true;
    }
}

