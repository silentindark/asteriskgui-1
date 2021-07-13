<?php
include dirname(__FILE__) . "/../../../models/report/cdr.php";

$config = include(dirname(__FILE__) . "/../../pami_config.php");

// $db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);
$db = new mysqli($config['MYSQL']['server'], $config['MYSQL']['username'], $config['MYSQL']['password'], $config['MYSQL']['db']);

$report = new ReportCdrRepository($db);

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $report->getAll(
            array(
                "start" => $_GET["start"],
                "end" => $_GET["end"],
                "calls_limit" => $_GET["calls_limit"],
                "search_number" => $_GET["search_number"]
            )
        );
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
