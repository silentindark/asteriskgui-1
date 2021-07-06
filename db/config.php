<?php

return array(
    "db" => "mysql:host=localhost;dbname=asteriskcdrdb",
    "username" => "freepbxuser",     //Mysql login
    "password" => "60b47abf8d41e93ecb964bd5b6f97948", //Mysql password
    "options" => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    "asterisk_ip" => "127.0.0.1",
    "manager_port" => "6061",
    "manager_login" => "monitoring",
    "manager_password" => "monitoring_pass",
    "logfile" => "/var/log/asterisk5061/full",
    "monitor" => "/var/spool/asterisk/monitor/",
    "users" => array('admin' => 'password', 'user' => 'user')
);
