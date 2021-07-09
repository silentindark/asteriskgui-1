<?php
include "../../../models/report/cdr.php";

$config = include("../../config.php");

$db = new PDO($config["db"], $config["username"], $config["password"], $config["options"]);

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
