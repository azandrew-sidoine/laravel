<?php

namespace App\Support\Config\Contracts;

interface Driver
{

    /**
     * Read value matching provided configuration `name`. Implementation should return
     * `App\Support\Config\NotFound` case value does not exists
     * 
     * @param string $name 
     * @return NoSymbol|mixed 
     */
    public function get(string $name);
}