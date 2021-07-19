<?php

return array(
    "db" => "mysql:host=localhost;dbname=ams5061",
    "username" => "ams5061",     //Mysql login
    "password" => "Qwestions", //Mysql password
    "options" => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
    "asterisk_ip" => "127.0.0.1",
    "manager_port" => "6061",
    "manager_login" => "monitoring",
    "manager_password" => "monitoring_pass",
    "logfile" => "/var/log/asterisk5061/full",
    "monitor" => "/var/spool/asterisk/monitor/",
    "users" => array('admin' => 'password', 'user' => 'user')
);
