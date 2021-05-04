<?php

namespace Pelim\LaravelZmq\Connector;

/**
 * Class ZmqConnector
 * @package Pelim\LaravelZmq\Connector
 */
abstract class ZmqConnector
{
    protected $connection;

    /**
     * @var \ZMQContext
     */
    public $socket;

    public function __construct($connection)
    {
        $this->connection = $connection;
        $this->socket =  $this->connect();
    }

    /**
     * @return \ZMQSocket
     */
    abstract public function connect();

    public function getSocket()
    {
        if(is_null($this->socket)) {
            $this->socket = $this->connect();
        }
        return $this->socket;
    }

    protected function dsn()
    {
        return \Config::get(sprintf('zmq.connections.%s.dsn', $this->connection), 'tcp://127.0.0.1:5555');
    }
}
