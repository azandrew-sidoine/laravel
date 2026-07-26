<?php

namespace App\Support\MQ\Amqp;

use App\Support\MQ\Contracts\ChannelInterface;
use App\Support\MQ\Contracts\ConnectionInterface;
use App\Support\MQ\Amqp\Exceptions\ConnectionException;
use Closure;
use PhpAmqpLib\Connection\AMQPConnectionConfig;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Connection\AbstractConnection;

class Client implements ConnectionInterface
{
    /** @var AbstractConnection[] */
    private $connections;

    /**  @var float */
    private $connection_timeout = 3.0;

    /** @var float */
    private $read_write_timeout = 3.0 ;


    /** @var  ChannelInterface[] */
    private $channels = [];

    /** @var Server|null */
    private $server;

    /**
     * Creates new connection instance
     * 
     * @param Server $server 
     * @param null|AMQPConnectionConfig $config
     * 
     * @return void 
     */
    public function __construct(Server $server, ?AMQPConnectionConfig $config = null)
    {
        if (null === $config) {
            $config = new AMQPConnectionConfig;
            $config->setIsLazy(true);
        }
        $this->server = $server;
        // $this->initializeConnection($config);
    }


    /**
     * attempts to connect to rabbitmq server and executes the callback with an instance of channel object which user can interact with to `broadcast`, `send` or listen to messages
     * 
     * **Note** Case `callback` parameter resolve to null, the channel instance if returned to the caller
     * 
     * @template T
     * 
     * @param Closure(ChannelInterface):T $callback 
     * @param int $retries 
     * @param null|int $chan            channel id if any
     * @param int $wait                 number of seconds to wait for in order to to retry to connect to server
     * @return ChannelInterface|T
     * 
     * @throws ConnectionException 
     */
    public function connect(?\Closure $callback = null, int $retries = 3, ?int $chan = null, int $wait = 5)
    {
        $tries = 0;

        /** @var ChannelInterface|null */
        $channel = null;

        /** @var \Throwable|null */
        $exception = null;

        while ($channel === null) {
            try {
                $this->cleanup(); // review code in future to check if cleanup needs to be called

                $connection = $this->createConnection();
                array_push($this->connections, $connection);

                $channel =  new Channel($connection->channel($chan));
                array_push($this->channels, $channel);
            } catch (\Throwable $e) {
                $exception = $e;
            }

            if ($channel !== null) {
                $exception = null;
                break;
            }

            $tries += 1;
            if ($tries >= $retries) {
                break;
            }

            usleep($wait * 5000);
        }

        if ($channel === null && ($exception !== null)) {
            throw new ConnectionException(sprintf("failed to connect after a maxium of %d attempts, %s", $tries, $exception->getMessage()), $exception->getCode(), $exception);
        }

        if (!$callback) {
            return $channel;
        }

        return $callback($channel);
    }

    public function close(): void
    {
        try {
            $this->cleanup();

            foreach ($this->connections as $connection) {
                if (!$connection) {
                    continue;
                }
                $connection->close();
            }
            $this->connections = [];
        } finally { }
    }


    /**
     * closes all opened channel and reset the `channels` array
     * 
     * @return void 
     */
    private function cleanup()
    {
        foreach ($this->channels as $channel) {
            if (!$channel) {
                continue;
            }
            $channel->close();
        }

        $this->channels = [];
    }

    /**
     * create a new rabbit mq connection
     * 
     * @param AMQPConnectionConfig|null $config
     * 
     * @return AMQPStreamConnection 
     */
    private function createConnection(?AMQPConnectionConfig $config = null)
    {
        return new AMQPStreamConnection(
            $this->server->getHost(),
            $this->server->getPort(),
            $this->server->getUser(),
            $this->server->getPassword(),
            $this->server->getVirtualHost(),
            false,
            'AMQPLAIN',
            null,
            'en_US',
            $this->connection_timeout,
            $this->read_write_timeout,
            null,
            false,
            0,
            0.0,
            $config
        );
    }

    public function __destruct()
    {
        $this->close();
    }
}
