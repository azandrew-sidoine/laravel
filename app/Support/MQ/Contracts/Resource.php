<?php

namespace App\Support\MQ\Contracts;

interface Resource
{
    /** 
     * release resources hold by the object 
     */
    public function close(): void;
}
