<?php

namespace Pelim\LaravelZmq\Broadcasting\Broadcaster;

use Pelim\LaravelZmq\Zmq;
use Illuminate\Http\Request;
use Pelim\LaravelZmq\Connector\ZmqConnector;
use Illuminate\Broadcasting\Broadcasters\Broadcaster;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Str;

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

    /**
     * Authenticate the incoming request for a given channel.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     * @throws \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
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

    /**
     * Return the valid authentication response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $result
     * @return mixed
     */
    public function validAuthenticationResponse($request, $result)
    {
        if (is_bool($result)) {
            return json_encode($result);
        }

        return json_encode(['channel_data' => [
            'user_id' => $request->user()->getAuthIdentifier(),
            'user_info' => $result,
        ]]);
    }
}
