<?php

use app\models\sip\SipPeerRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';
$config = include(__DIR__ . "/../../config.php");

//$db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

//error_reporting(E_ALL); 

$sippeer = new SipPeerRepository($config);
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sippeer->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo json_encode($result);
