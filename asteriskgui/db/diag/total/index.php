<?php

use app\models\diag\DiagTotalRepository;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$config = include("../../config.php");

$total = new DiagTotalRepository($config);
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $total->getAll();
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
