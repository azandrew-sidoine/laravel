<?php

namespace App\Support\MQ\Contracts;

interface ConnectionInterface extends Resource
{
    /**
     * attempts to connect to rabbitmq server and executes the callback with an instance of channel object which user can interact with to `broadcast`, `send` or listen to messages
     * 
     * **Note** Case `callback` parameter resolve to null, the channel instance if returned to the caller
     * 
     * @template T
     * 
     * @param \Closure(ChannelInterface):T $callback 
     * @param int $retries 
     * @param null|int $channel         channel id if any
     * @param int $wait                 number of seconds to wait for in order to to retry to connect to server
     * @return ChannelInterface|T
     * 
     * @throws \Throwable 
     */
    public function connect(?\Closure $callback = null, int $retries = 3, ?int $channel = null, int $wait = 5);

}