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
        $socket = $context->getSocket(config("zmq.{$this->connection}.method", \ZMQ::SOCKET_PUB));
        $socket->connect($this->dsn());

        usleep(500);

        return $socket;
    }
}
