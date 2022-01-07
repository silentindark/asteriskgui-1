<?php

use app\models\diag\DiagDatabaseRepository;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$config = include("../../config.php");

$total = new DiagDatabaseRepository($config);
//error_log("ast: call command ".PHP_EOL);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $total->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo json_encode($result);
