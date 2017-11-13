<?php

namespace Pelim\LaravelZmq\Connector;

/**
 * Class ZmqConnector
 * @package Pelim\LaravelZmq\Connector
 */
class ZmqPublish extends ZmqConnector
{
    /**
     * ZmqPublish constructor.
     * @param string $connection
     */
    public function __construct($connection = 'publish')
    {
        parent::__construct($connection);
    }

    /**
     * Connect to the socket for publishing.
     * @return \ZMQSocket
     */
    public function connect()
    {
        $context = new \ZMQContext();
        $socket_method = \Config::get(sprintf('zmq.connections.%s.method', $this->connection), \ZMQ::SOCKET_PUB);
        $socket = $context->getSocket($socket_method);
        $socket->connect($this->dsn());

        usleep(500);

        return $socket;
    }
}
