<?php


return [
    
    'connections' => [

        'publish' => [
            'type'      => \ZMQ::SOCKET_PUB,
            'method'    =>  'connect',
            'dsn'       => 'tcp://127.0.0.1:5555',
            'peristent' => false
        ],

        'subscribe' => [
            'type'   => \ZMQ::SOCKET_SUB,
            'method' => 'bind',
            'dsn'    => 'tcp://0.0.0.0:5555',
            'peristent' => true
        ],

    ]
];