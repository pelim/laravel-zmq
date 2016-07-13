<?php

namespace Pelim\LaravelZmq\Connector;

/**
 * Class ZmqConnector
 * @package Pelim\LaravelZmq\Connector
 */
class ZmqSubscribe extends ZmqConnector {

    /**
     * ZmqPublish constructor.
     * @param string $connection
     */
    public function __construct($connection = 'subscribe')
    {
        parent::__construct($connection);
    }

    public function connect()
    {
        $context = new \ZMQContext();
        $socket  = new \ZMQSocket($context, \ZMQ::SOCKET_SUB);
        $socket->bind($this->dsn());
        
        return $socket;
    }
}