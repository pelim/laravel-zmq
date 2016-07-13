<?php

namespace Pelim\LaravelZmq\Broadcasting\Broadcaster;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Pelim\LaravelZmq\Zmq;

/**
 * Class ZmqBroadcaster
 * @package Pelim\LaravelZmq\Broadcasting\Broadcaster
 */
class ZmqBroadcaster implements Broadcaster
{
    /**
     * ZmqBroadcaster constructor.
     * @param Zmq $zmq
     * @param null $connection
     */
    public function __construct(Zmq $zmq, $connection = null)
    {
        $this->zmq        = $zmq;
        $this->connection = $connection;
    }
    
    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $zmq = $this->zmq->connection($this->connection, \ZMQ::SOCKET_PUB);

        if($payload) {
            $payload = json_encode(['event' => $event, 'payload' => $payload]);
        } else {
            $payload = $event;
        }

        foreach($channels as $channel) {
            $zmq->send($channel, \ZMQ::MODE_SNDMORE);
            $zmq->send($payload);
        }
    }
}