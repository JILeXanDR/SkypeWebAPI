<?php

namespace WebSkype;

use Psr\Http\Message\ResponseInterface;

class WebSkypeException extends \LogicException
{
    public $response;

    public function __construct($message, ResponseInterface $response)
    {
        $this->response = $response;

        parent::__construct($message);
    }
}