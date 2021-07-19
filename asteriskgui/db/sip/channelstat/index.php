<?php
include dirname(__FILE__) . "/../../../models/sip/channelstat.php";
$config = include(dirname(__FILE__) . "/../../config.php");

$sipchannelstat = new SipChannelstatRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipchannelstat->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
