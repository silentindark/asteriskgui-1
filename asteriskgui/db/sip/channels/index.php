<?php

use app\models\sip\SipChannelsRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';
$config = include(__DIR__ . "/../../config.php");

$sipChannels = new SipChannelsRepository($config);
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipChannels->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
