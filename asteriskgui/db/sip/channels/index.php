<?php
include __DIR__ . "/../../../models/sip/channels.php";
$config = include(__DIR__ . "/../../config.php");

$sipchannels = new SipChannelsRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipchannels->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
