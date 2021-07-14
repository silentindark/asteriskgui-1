<?php

return array(
    "logfile" => "/var/log/asterisk5061/full",
    "monitor" => "/var/spool/asterisk5061/monitor/",
    "users" => array('admin' => 'password', 'user' => 'user'),
    "MYSQL" => array(
        'server' => 'localhost',
        'username' => 'admin',
        'password' => 'Qwestions',
        'db' => 'asteriskcdr',
    ),
    "PAMI" => array(
        'host' => '127.0.0.1',
        'scheme' => 'tcp://',
        'port' => 5038,
        'username' => 'monitoring',
        'secret' => 'monitoring_pass',
        'connect_timeout' => 10000,
        'read_timeout' => 10000
    ),
    "User" => array(
        'login' => 'admin',
        'password' => 'admin'
    )
);
