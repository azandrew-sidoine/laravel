<?php

namespace App\Support\Routing;

interface URLRoutable
{
    /**
     * returns the url to the routable resource
     * 
     * @return string 
     */
    public function getRoute(): string;
}


