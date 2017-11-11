<?php

namespace Pelim\LaravelZmq\Connector;

/**
 * Class ZmqConnector
 * @package Pelim\LaravelZmq\Connector
 */
class ZmqSubscribe extends ZmqConnector
{
    /**
     * ZmqPublish constructor.
     * @param string $connection
     */
    public function __construct($connection = 'subscribe')
    {
        parent::__construct($connection);
    }

    /**
     * Connect to the socket for subscribing.
     *
     * @return \ZMQSocket
     */
    public function connect()
    {
        $context = new \ZMQContext();
        $socket  = $context->getSocket(config("zmq.{$this->connection}.method", \ZMQ::SOCKET_SUB));
        $socket->bind($this->dsn());

        return $socket;
    }
}
