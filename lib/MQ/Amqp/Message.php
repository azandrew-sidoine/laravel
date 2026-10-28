<?php

namespace Lib\MQ\Amqp;

use Lib\MQ\Contracts\MessageInterface;
use PhpAmqpLib\Message\AMQPMessage;

class Message implements MessageInterface
{

    /** @var array<string,mixed> */
    private array $deliveryOptions = [];

    /** @var array<string,mixed> */
    const DELIVERY_OPTIONS = ['content_type' => 'text/plain', 'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT];

    /**
     * creates a rabbit mq message
     * 
     * @param string|object $message 
     * @param array $deliveryOptions
     * 
     * @return void 
     */
    public function __construct(private $message, array $deliveryOptions = [])
    {
        $this->deliveryOptions = array_merge(static::DELIVERY_OPTIONS, $deliveryOptions);
    }

    public function getDeliveryOptions(): array
    {
        return $this->deliveryOptions;
    }

    public function __toString(): string
    {
        return is_string($this->message) ? $this->message : json_encode($this->message);
    }
}
