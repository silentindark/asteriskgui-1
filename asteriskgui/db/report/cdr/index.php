<?php

use app\models\report\ReportCdrRepository;

require_once __DIR__ . '/../../../../vendor/autoload.php';
$config = include(__DIR__ . "/../../config.php");

// $db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

$db = new mysqli(
    $config['MYSQL']['server'],
    $config['MYSQL']['username'],
    $config['MYSQL']['password'],
    $config['MYSQL']['db'],
    $config['MYSQL']['port']
);

$report = new ReportCdrRepository($db);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $report->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo json_encode($result);
