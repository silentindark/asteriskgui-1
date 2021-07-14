<?php
include "../../../models/queue/status.php";

$report = new QueueStatusRepository();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $result = $report->getAll($_GET);
        break;
}

header("Content-Type: application/json");
echo  json_encode($result);
