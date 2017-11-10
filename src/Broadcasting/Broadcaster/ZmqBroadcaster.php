<?php

namespace Pelim\LaravelZmq\Broadcasting\Broadcaster;

use Pelim\LaravelZmq\Zmq;
use \Illuminate\Http\Request;
use Pelim\LaravelZmq\Connector\ZmqConnector;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Class ZmqBroadcaster
 * @package Pelim\LaravelZmq\Broadcasting\Broadcaster
 */
class ZmqBroadcaster extends Broadcaster
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

    public function auth($request)
    {
        if (Str::startsWith($request->channel_name, ['private-', 'presence-']) &&
            ! $request->user()) {
            throw new AccessDeniedHttpException;
        }

        $channelName = Str::startsWith($request->channel_name, 'private-')
                            ? Str::replaceFirst('private-', '', $request->channel_name)
                            : Str::replaceFirst('presence-', '', $request->channel_name);

        return parent::verifyUserCanAccessChannel(
            $request,
            $channelName
        );
    }

    public function validAuthenticationResponse($request, $result)
    {
        if (Str::startsWith($request->channel_name, 'private')) {
            return $this->decodePusherResponse(
                $this->pusher->socket_auth($request->channel_name, $request->socket_id)
            );
        }

        return $this->decodePusherResponse(
            $this->pusher->presence_auth(
                $request->channel_name,
                $request->socket_id,
                $request->user()->getAuthIdentifier(),
                $result
            )
        );
    }
}
