<?php

namespace App\Support\Config;

interface ConfigInterface
{
    /**
     * Returns configuration name property value
     * 
     * @return string
     */
    public function getName(): string;

    /**
     * Get configuration value
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Returns the configuration value type
     * 
     * @return string 
     */
    public function getValueType(): string;
}
