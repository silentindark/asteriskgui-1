<?php

use app\models\sip\SipRegistryRepository;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$config = include(__DIR__ . "/../../config.php");

$sipRegistry = new SipRegistryRepository($config);
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $sipRegistry->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo json_encode($result);
