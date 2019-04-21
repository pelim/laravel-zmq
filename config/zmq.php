<?php

return [
    'default' => 'publish',

    'connections' => [

        'publish' => [
            0 => [
                'dsn'       => 'tcp://127.0.0.1:5555',
                'method'    => \ZMQ::SOCKET_PUB,
            ]
        ],

        'subscribe' => [
            'dsn'    => 'tcp://0.0.0.0:5555',
            'method'    => \ZMQ::SOCKET_SUB,
        ],

    ]
];
