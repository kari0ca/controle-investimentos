<?php
return [
    'databases' => [
        'default' => [
            'driver' => \App\Database\Drivers\MySQL::class,
            'hostname' => '127.0.0.1',
            'username' => 'root',
            'password' => 'root',
            'database' => 'controle_investimentos',
            'port' => '3306'
        ]
    ]
];