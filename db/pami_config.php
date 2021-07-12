<?php

return array(
    "db" => "mysql:host=localhost;dbname=ams5061",
    "username" => "ams5061",     //Mysql login
    "password" => "Qwestions", //Mysql password
    "options" => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    "logfile" => "/var/log/asterisk5061/full",
    "monitor" => "/var/spool/asterisk5061/monitor/",
    "users" => array('admin' => 'password', 'user' => 'user'),
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
