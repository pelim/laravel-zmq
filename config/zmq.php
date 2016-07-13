<?php


return [

    'sockets' => [

        'subscribe' => [
            'type'      => ZMQ::SOCKET_SUB,
            'dsn'       => 'tcp://127.0.0.0.1:5555',
            'persitent' => false
        ],

        'publish' => [
            'type'      => ZMQ::SOCKET_PUB,
            'dsn'       => 'tcp://127.0.0.0.1:5555',
            'persitent' => false
        ]
    ]
];