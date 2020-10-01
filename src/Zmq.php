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
     * @param string $connection
     * @return \ZMQSocket
     */
    public function connection($connection = null)
    {

        if (! $connection) {
            $connection = \Config::get('zmq.default');
        }

        return \App::make(sprintf('zmq.connection.%s', $connection))->connect();
    }

    /**
     * @param array $channels
     * @param \Closure $callback
     * @param string $connection
     */
    public function subscribe(array $channels, \Closure $callback, $connection = 'subscribe')
    {
        $connection = $this->connection($connection);

        foreach ($channels as $channel) {
            $connection->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, $channel);
            if(\Config::get("zmq.debug_logs") == true)
            {
                \Log::debug('zmq.subscribe', [
                    'channel' => $channel
                ]);   
            }            
        }

        while (true) {
            $channel = $connection->recv();
            $payload = $connection->recv();

            if ($arrayData = json_decode($payload, true)) {
                $payload = $arrayData;
            } else {
                $payload = [$payload];
            }

            call_user_func($callback, $payload, $channel);

            usleep(10);
        }
    }

    /**
     * @param array $channels
     * @param $event
     * @param array $payload
     * @param string $connection
     */
    public function publish(array $channels, $event, $payload = [], $connection = 'publish')
    {
        $connection = $this->connection($connection);

        if ($payload) {
            $payload = json_encode(['event' => $event, 'payload' => $payload]);
        } else {
            $payload = $event;
        }

        foreach ($channels as $channel) {

            if(\Config::get("zmq.debug_logs") == true)
            {
                \Log::debug('zmq.publish', [
                    'channel' => $channel->name,
                    'payload' => $payload
                ]);
            }            

            $connection->send($channel, \ZMQ::MODE_SNDMORE)->send($payload);
        }
    }
}
