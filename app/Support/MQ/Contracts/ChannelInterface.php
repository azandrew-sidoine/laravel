<?php

namespace App\Support\MQ\Contracts;

use Closure;

interface ChannelInterface extends Resource
{
    /**
     * broadcast a message to the message Queue server using the provided message broker.
     * 
     * if a queue name is provided, the message is dispatched to it
     * 
     * @param MessageInterface $message 
     * @param string|null $queue
     * 
     * @return void
     * 
     * @throws \Throwable 
     */
    public function broadcast(MessageInterface $message, ?string $queue = null);

    /**
     * send a message to a given channel queue
     * 
     * @param MessageInterface $message
     * @param string $queue
     * 
     * @return void 
     */
    public function send(MessageInterface $message, string $queue): void;

    /**
     * listen for events on a given channel queue
     * 
     * @param string $queue 
     * @param Closure $callback 
     * @param string|null $tag
     * 
     * @return void 
     */
    public function listen(Closure $callback, ?string $queue = null, ?string $tag = null): void;
}