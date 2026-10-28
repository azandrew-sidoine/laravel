<?php

namespace Lib\MQ\Contracts;

interface HasMessageQueue
{
    /**
     * returns the queue name on which read and write operations are performed
     * 
     * @return string 
     */ 
    public function getMessageBroker(): string;

    /**
     * returns the message topic prefix
     * 
     * @return null|string 
     */
    public function getTopicNamespace(): ?string;
}