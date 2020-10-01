<?php

return [
    'default' => 'publish',

    'connections' => [

        'publish' => [
            'dsn'       => 'tcp://127.0.0.1:5555',
            'method'    => \ZMQ::SOCKET_PUB,
        ],

        'subscribe' => [
            'dsn'    => 'tcp://0.0.0.0:5555',
            'method'    => \ZMQ::SOCKET_SUB,
        ],    
    ]
    'debug_logs' => true // enable this to log all published messages to debug channel
];
