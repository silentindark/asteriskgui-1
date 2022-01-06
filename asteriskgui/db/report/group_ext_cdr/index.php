<?php

use app\models\report\ReportGroupExtCdrRepository;

require_once __DIR__ . '/../../../vendor/autoload.php';
$config = include("../../config.php");

$db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

$report = new ReportGroupExtCdrRepository($db);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $report->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
