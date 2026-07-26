<?php

namespace App\Support\MQ\Amqp\Exceptions;

use Exception;
use Throwable;

class ChannelException extends Exception
{
    /**
     * creates exception class
     * 
     * @param Throwable $throwable 
     * @return void 
     */
    public function __construct(string $message, int $code, \Throwable $throwable)
    {
        parent::__construct($message, $code, $throwable);
    }

}