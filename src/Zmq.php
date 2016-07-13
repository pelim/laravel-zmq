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
    protected static $sockets = 0;

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


        if ($connection  = array_get($this->connections, $name)) {
            $type        = array_get($connection, 'type', $type);
            $method      = array_get($connection, 'method', 'connect');
            $dsn         = array_get($connection, 'dsn', 'tcp://127.0.0.1:5555');
            $persistent  = array_get($connection, 'peristent', true);
            $ioThreads   = array_get($connection, 'io_threads', 10);
            
            $context = new \ZMQContext(19, false);

            $socket  = new \ZMQSocket($context, $type, self::$sockets, function($socket, &$id) {
                Zmq::$sockets++;
            });

            if(method_exists($socket, $method)) {
                dump($name, $method, $dsn);
                //return $socket->$method($dsn, true);
                
                return $socket;
            } else {
                throw new \ZMQException(sprintf('connection method not implemented: "%s"', $method));
            }
        } else {
            throw new \ZMQException(sprintf('unkown connection name: "%s"', $name));
        }

    }

    /**
     * @param array $channels
     * @param \Closure $callback
     * @param null $connection
     * @throws \Exception
     */
    public function subscribe(array $channels, \Closure $callback, $connection = 'subscribe')
    {

        $connection = $this->connection($connection, \ZMQ::SOCKET_SUB)->bind('tcp://*:5552', true);

        foreach ($channels as $channel) {
            $connection->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, $channel);
        }

        while (true) {

            $channel = $connection->recv();
            $payload = $connection->recv();

            if ($arrayData = json_decode($payload, true)) {
                $payload = $arrayData;
            } else {
                $payload = [$payload];
            }

            \Log::debug('zmq.broadcast.recieved', [
                'channel' => $channel,
                'payload' => $payload
            ]);

            call_user_func($callback, $payload, $channel);

            usleep(10);


        }
    }

}