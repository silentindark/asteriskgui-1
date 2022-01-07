<?php

use app\models\queue\QueueStatusRepository;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$config = include(__DIR__ . "/../../config.php");

$report = new QueueStatusRepository($config);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $report->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo json_encode($result);
