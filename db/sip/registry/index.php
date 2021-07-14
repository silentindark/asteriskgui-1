<?php
include dirname(__FILE__) . "/../../../models/sip/registry.php";
$config = include(dirname(__FILE__) . "/../../pami_config.php");

$sipregistry = new SipRegistryRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipregistry->getAll(
            array(
                "host" => $_GET["host"],
                "port" => $_GET["port"],
                "username" => $_GET["username"],
                "state" => $_GET["state"],
                "registration_time" => $_GET["registration_time"]
            )
        );
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
