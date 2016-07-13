<?php

namespace Pelim\LaravelZmq;

/**
 * Class Zmq
 * @package Pelim\LaravelZmq
 */
class Zmq
{
    /**
     * @var array
     */
    protected $connections;

    /**
     * @var  \ZMQSocket [][]
     */
    protected static $sockets;

    /**
     * ZmqConnection constructor.
     * @param array $connections
     */
    public function __construct(array $connections = [])
    {
        $this->connections = $connections;
    }

    /**
     * @param string $name
     * @return \ZMQSocket
     */
    public function connection($name = 'default', $type = \ZMQ::SOCKET_REQ)
    {

        if (!isset(static::$sockets[$name][$type])) {

            if ($connection = array_get($this->connections, $name)) {
                $type = array_get($connection, 'type', $type);
                $dsn = array_get($connection, 'dsn', 'tcp://127.0.0.1:5555');
                static::$sockets[$name][$type] = (new \ZMQSocket(new \ZMQContext(), $type))->connect($dsn);
            }
        }

        return static::$sockets[$name][$type];
    }

    /**
     * @param array $channels
     * @param \Closure $callback
     * @param null $connection
     * @throws \Exception
     */
    public function subscribe(array $channels, \Closure $callback, $connection = null)
    {

        $connection = $this->connection($connection, \ZMQ::SOCKET_SUB);
        foreach ($channels as $channel) {
            $connection->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, $channel);
        }

        while (true) {

            try {
                $channel = $connection->recv();
                $payload = $connection->recv();

                if ($arrayData = json_decode($payload, true)) {
                    $payload = $arrayData;
                } else {
                    $payload = [$payload];
                }

                call_user_func($callback, $payload, $channel);
            } catch (\Exception $e) {
                unset($connection);

                throw $e;
            }

        }
    }

}