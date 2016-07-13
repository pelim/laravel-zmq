<?php

namespace Pelim\LaravelZmq\Broadcasting\Broadcaster;

use Illuminate\Contracts\Broadcasting\Broadcaster;

class ZmqBroadcaster implements Broadcaster
{
    /**
     * {@inheritdoc}
     */
    public function broadcast(array $channels, $event, array $payload = [])
    {
        //@TODO: implement
    }
}