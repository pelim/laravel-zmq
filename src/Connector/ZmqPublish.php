<?php

namespace Pelim\LaravelZmq\Connector;

/**
 * Class ZmqConnector
 * @package Pelim\LaravelZmq\Connector
 */
class ZmqPublish extends ZmqConnector {
    
    /**
     * ZmqPublish constructor.
     * @param string $connection
     */
    public function __construct($connection = 'publish')
    {
        parent::__construct($connection);
    }

    /**
     * @return \ZMQSocket
     */
    public function connect() 
    {
        $context = new \ZMQContext();
        $socket  = new \ZMQSocket($context, \ZMQ::SOCKET_PUB);
        $socket->connect($this->dsn());

        // @need some sleep at :-(
        usleep(500);

        return $socket;
    }
}