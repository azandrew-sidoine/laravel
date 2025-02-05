<?php

namespace App\Support\Config;

interface RepositoryInterface
{
    /**
     * Returns a configuration value for the provided configuration name
     * 
     * @param string $name 
     * @param mixed $default 
     * @return mixed 
     * @throws \Throwable 
     */
    public function get(string $name, $default = null);

}