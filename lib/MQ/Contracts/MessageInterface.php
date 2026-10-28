<?php

namespace Lib\MQ\Contracts;

interface MessageInterface
{

    /**
     * return the message delivery options configured for the current message
     * 
     * @return array 
     */
    public function getDeliveryOptions(): array;
    
    /**
     * returns the string version of the message instance
     * 
     * @return string 
     */
    public function __toString(): string;
}