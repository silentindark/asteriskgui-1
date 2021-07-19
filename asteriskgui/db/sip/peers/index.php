<?php
include dirname(__FILE__) . "/../../../models/sip/peers.php";
$config = include(dirname(__FILE__) . "/../../config.php");

//$db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

//error_reporting(E_ALL); 

$sippeer = new SipPeerRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sippeer->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
