<?php

return [

        /*
         * packages service Providers...
         */

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\RouteServiceProvider::class,

        Lib\Config\ServiceProvider::class,
        Lib\MQ\ServiceProvider::class,
];