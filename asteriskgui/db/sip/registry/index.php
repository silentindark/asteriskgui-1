<?php
include __DIR__ . "/../../../models/sip/registry.php";
$config = include(__DIR__ . "/../../config.php");

$sipregistry = new SipRegistryRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipregistry->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
