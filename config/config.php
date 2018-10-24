<?php
return [
    'databases' => [
        'default' => [
            'driver' => \App\Database\Driver\MySQL::class,
            'hostname' => 'localhost',
            'username' => 'higor',
            'password' => 'sp120c',
            'database' => 'investdb',
            'port' => '3306'
        ]
    ],
    'session' => [
        'session.name' => 'SYSTEM'
    ]
];