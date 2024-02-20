<?php

namespace App\Support\Routing;

interface URLRoutable
{
    /**
     * Returns the url to the routable resource
     * 
     * @return string 
     */
    public function getRoute(): string;
}


