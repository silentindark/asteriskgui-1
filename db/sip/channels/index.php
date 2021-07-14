<?php
include "../../../models/sip/channels.php";
$config = include("../../config.php");

$sipchannels = new SipChannelsRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipchannels->getAll(
            array(
                "channel" => $_GET["channel"],
                "calleridname" => $_GET["calleridname"],
                "calleridnum" => $_GET["calleridnum"],
                // "context" => $_GET["context"],
                // "extension" => $_GET["extension"],
                "duration" => $_GET["duration"],
                "channelstatedesc" => $_GET["channelstatedesc"],
                "bridgedchannel" => $_GET["bridgedchannel"],
                "application" => $_GET["application"],
                "applicationdata" => $_GET["applicationdata"]
            )
        );
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
