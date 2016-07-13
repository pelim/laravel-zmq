<?php

namespace Pelim\LaravelZmq\Broadcasting\Broadcaster;

use Illuminate\Contracts\Broadcasting\Broadcaster;
use Pelim\LaravelZmq\Connector\ZmqConnector;
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
     * ZmqBroadcaster constructor.
     * @param Zmq $zmq
     */
    public function __construct(Zmq $zmq)
    {
        $this->zmq = $zmq;
    }
    
    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        $this->zmq->publish($channels, $event, $payload, 'publish');
    }
}