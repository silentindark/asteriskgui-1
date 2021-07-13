<?php

return array(
    "logfile" => "/var/log/asterisk5061/full",
    "monitor" => "/var/spool/asterisk5061/monitor/",
    "users" => array('admin' => 'password', 'user' => 'user'),
    "MYSQL" => array(
        'server' => 'localhost',
        'username' => 'ams5061',
        'password' => 'Qwestions',
        'db' => 'ams5061',
    ),
    "PAMI" => array(
        'host' => '127.0.0.1',
        'scheme' => 'tcp://',
        'port' => 6061,
        'username' => 'monitoring',
        'secret' => 'monitoring_pass',
        'connect_timeout' => 10000,
        'read_timeout' => 10000
    ),
    "User" => array(
        'login' => 'admin',
        'password' => 'Qwestions'
    )
);
