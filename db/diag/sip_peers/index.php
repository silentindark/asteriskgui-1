<?php
include "../../../models/diag/sip_peers.php";
$config = include("../../config.php");

//$db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

//error_reporting(E_ALL); 

$sippeer = new SipPeerRepository();
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sippeer->getAll(
            array(
                "name" => $_GET["name"],
                "ip" => $_GET["ip"],
                "port" => $_GET["port"],
                "state" => $_GET["state"],
                "desc" => $_GET["desc"]
            )
        );
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
