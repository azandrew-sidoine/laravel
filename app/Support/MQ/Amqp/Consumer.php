<?php

namespace App\Support\MQ\Amqp;

use App\Support\MQ\Contracts\ConnectionInterface;
use Closure;
use PhpAmqpLib\Exception\AMQPConnectionClosedException;
use PhpAmqpLib\Exception\AMQPRuntimeException;

class Consumer
{
    /** @var \Closure|null */
    private $logger = null;

    /** @var int */
    private $retries = 3;

    /** @var int */
    private $wait = 5;


    /**
     * creates a consumer class instance
     * 
     * @param ConnectionInterface $connection
     * 
     * @return void 
     */
    public function __construct(private ConnectionInterface $connection) {}


    /**
     * chain method to configure logger object
     * 
     * @param Closure $logger
     * 
     * @return static 
     */
    public function withLogger(\Closure $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * chain method to configure maximum reconnect retry count
     * 
     * @param int $value
     * 
     * @return static 
     */
    public function withMaxReconnectTries(int $value)
    {
        $this->retries = $value;

        return $this;
    }

    /**
     * chain method to configure reconnect timeout
     * 
     * @param int $value
     * 
     * @return static 
     */
    public function withReconnectTimeout(int $value)
    {
        $this->wait = $value;

        return $this;
    }

    public function start(\Closure $callback, ?string $queue = null, ?string $deliveryTag = null): void
    {
        while (true) {
            try {

                $this->connection->connect(fn($channel) => $channel->listen($callback, $queue, $deliveryTag), $this->retries, $this->wait);
                
            } catch (AMQPConnectionClosedException | AMQPRuntimeException | \Exception $e) {
                if ($this->logger) {
                    call_user_func($this->logger, $e->getMessage());
                }

                trigger_error(sprintf("consumer failed with errror message: %s, retrying after %d seconds...", $e->getMessage(), 5), \E_USER_WARNING);

                usleep(5 * 1000);
                continue;
            }
        }
    }
}
