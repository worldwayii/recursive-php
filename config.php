<?php

return [
    'database' => [
        'name' => 'netpay',
        'username' => 'db_username', //change to yours
        'password' => 'db_password', //change to yours
        'connection' => 'mysql:host=127.0.0.1',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    ]
];
