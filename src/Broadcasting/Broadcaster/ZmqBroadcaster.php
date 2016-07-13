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
     * @var Zmq
     */
    protected $zmq;

    /**
     * @var string
     */
    protected $connection;

    /**
     * ZmqBroadcaster constructor.
     * @param Zmq $zmq
     * @param null $connection
     */
    public function __construct(Zmq $zmq, $connection = 'publish')
    {
        $this->zmq        = $zmq;
        $this->connection = $connection;
    }
    
    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $zmq = $this->zmq->connection($this->connection, \ZMQ::SOCKET_PUB)->connect('tcp://127.0.0.1:5552', true);

        dump($zmq->getEndpoints());

        if($payload) {
            $payload = json_encode(['event' => $event, 'payload' => $payload]);
        } else {
            $payload = $event;
        }


        foreach($channels as $channel) {
            
            \Log::debug('zmq.broadcast', [
                'channel' => $channel,
                'payload' => $payload
            ]);

            $zmq->send($channel, \ZMQ::MODE_SNDMORE)->send($payload);
        }
    }
}