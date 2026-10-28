<?php

namespace Lib\MQ\Amqp;

use Lib\MQ\Contracts\ChannelInterface;
use Lib\MQ\Contracts\MessageInterface;
use Lib\MQ\Amqp\Exceptions\ChannelException;
use BadMethodCallException;
use Closure;
use Override;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class Channel implements ChannelInterface
{
    /** @var string */
    private $broker;

    /** @var string */
    private $namespace;

    /**
     * Creates new channel instance
     * 
     * @param AMQPChannel $channel 
     * @return void 
     */
    public function __construct(private AMQPChannel $channel)
    {
    }


    /**
     * binds the channel to a given broker
     * 
     * @param string $broker
     * @param string|null $namespace
     * 
     * @return static 
     */
    public function bind(string $broker, ?string $namespace)
    {
        $this->broker = $broker;
        $this->namespace = $namespace;

        return $this;
    }

    public function broadcast(MessageInterface $message, ?string $queue = null)
    {
        try {

            if (!$this->broker) {
                throw new BadMethodCallException("unkown broker, please bind the channel to a broker calling ::bind() method before calling the ::boadcast() method.");
            }

            $this->channel->confirm_select();

            // execute exchange_declare in a try...catch block to ignore any error
            try {
                $this->channel->exchange_declare($this->broker, AMQPExchangeType::FANOUT, false, true, false);
            } finally { }

            if (!empty($queue)) {
                $this->queueConfirm($queue);
                $this->channel->queue_bind($queue, $this->broker, $this->namespace);
            }


            // Publish message on the queue
            $this->channel->basic_publish(
                new AMQPMessage($message->__toString(), $message->getDeliveryOptions()),
                $this->broker,
                $this->namespace
            );
            
            $this->channel->wait_for_pending_acks(5);
        } catch (\Throwable $e) {
            throw new ChannelException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function send(MessageInterface $message, string $queue): void
    {
        try {
            $this->channel->confirm_select();
            
            // execute exchange_declare in a try...catch block to ignore any error
            try {            
                $this->channel->exchange_declare($this->broker, $this->namespace, false, true, false);
                $this->channel->exchange_declare($this->broker, AMQPExchangeType::TOPIC, false, true, false);
            } finally {}


            $this->queueConfirm($queue);
            $this->channel->queue_bind($queue, $this->broker, $this->namespace);

            $this->channel->basic_publish(
                new AMQPMessage($message->__toString(), $message->getDeliveryOptions()),
                $this->broker,
                $this->namespace
            );
            
            $this->channel->wait_for_pending_acks(5);
        } catch (\Throwable $e) {
            throw new ChannelException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function listen(Closure $callback, ?string $queue = null, ?string $tag = null): void
    {
        try {
            $this->queueConfirm($queue);
            $this->channel->basic_consume($queue, $tag ?? '', false, true, false, false, $callback);
            $this->channel->consume();
        } catch (\Throwable $e) {
            throw new ChannelException($e->getMessage(), $e->getCode(), $e);
        }
    }


    #[Override]
    public function close(): void
    {
        if ($this->channel && $this->channel->is_open()) {
            $this->channel->close();
        }
    }

    /**
     * create queue if not exist and return success if operation was successfull
     * 
     * @param string $queue 
     * @return void 
     * @throws AMQPTimeoutException 
     */
    private function queueConfirm(?string $queue = null)
    {
        $this->channel->queue_declare($queue ?? '', false, true, false, false);
    }


    public function __destruct()
    {
        $this->close();
    }
}
