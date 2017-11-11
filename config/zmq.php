<?php

return [
    'default' => 'publish',

    'connections' => [

        'publish' => [
            'dsn'       => 'tcp://127.0.0.1:5555',
            'method'    => \ZMQ::SOCET_PUB,
        ],

        'subscribe' => [
            'dsn'    => 'tcp://0.0.0.0:5555',
            'method'    => \ZMQ::SOCET_SUB,
        ],

    ]
];
