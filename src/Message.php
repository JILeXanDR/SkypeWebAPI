<?php

namespace WebSkype;

class Message
{
    public $content;
    public $messagetype = 'RichText';
    public $contenttype = 'text';
    public $clientmessageid;

    const MAGIC_NUMBER = '012';

    public function __construct($content)
    {
        $this->content = $content;
        $this->clientmessageid = time() . static::MAGIC_NUMBER;
    }

    public function __toString()
    {
        return json_encode([
            'content' => $this->content,
            'messagetype' => $this->messagetype,
            'contenttype' => $this->contenttype,
            'clientmessageid' => $this->clientmessageid,
        ]);
    }
}