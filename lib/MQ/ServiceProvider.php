<?php

namespace Lib\MQ;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Lib\Config\Config;
use Lib\MQ\Amqp\Client;
use Lib\MQ\Amqp\ConnectionFactory;

class ServiceProvider extends BaseServiceProvider
{

    /**
     * Register application services.
     * 
     * @return void
     */
    public function register()
    {
        $this->app->bind(Client::class, fn() => (new ConnectionFactory)->createConnection(
            Config::get('queue.connections.rabbit.user', 'guest'),
            Config::get('queue.connections.rabbit.password', 'guest'),
            Config::get('queue.connections.rabbit.host', '127.0.0.1'),
            (int)(Config::get('queue.connections.rabbit.port', '5672')),
            Config::get('queue.connections.rabbit.vhost', '/')
        ));
    }
}
