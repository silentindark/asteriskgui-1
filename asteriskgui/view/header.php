<?php
require_once __DIR__ . '/../../vendor/autoload.php';

if (!isset($title)) {
    $title = 'iframe';
}
?><!DOCTYPE html>
<html lang="ru">
<head>
    <title><?= $title ?></title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <link rel="stylesheet" type="text/css" href="../public/css/jsgrid.css"/>
    <link rel="stylesheet" type="text/css" href="../public/css/theme.css"/>
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap.css"/>
    <link rel="stylesheet" type="text/css" media="all" href="../public/css/daterangepicker.css"/>
    <link rel="stylesheet" type="text/css" href="../public/css/bootstrap-theme.css"/>
    <link rel="stylesheet" href="../public/css/style.css"/>
    <link rel="stylesheet" href="../public/css/jquery-ui.css"/>
</head>
<body>
    <script src="../public/js/jquery.js"></script>
    <script src="../public/js/moment.min.js"></script>
    <script src="../public/js/daterangepicker.js"></script>
    <script src="../public/js/jsgrid.core.js"></script>
    <script src="../public/js/jsgrid.load-indicator.js"></script>
    <script src="../public/js/jsgrid.load-strategies.js"></script>
    <script src="../public/js/jsgrid.sort-strategies.js"></script>
    <script src="../public/js/jsgrid.field.js"></script>
    <script src="../public/js/fields/jsgrid.field.text.js"></script>
    <script src="../public/js/fields/jsgrid.field.number.js"></script>
    <script src="../public/js/fields/jsgrid.field.select.js"></script>
    <script src="../public/js/fields/jsgrid.field.checkbox.js"></script>
    <script src="../public/js/fields/jsgrid.field.control.js"></script>
    <script src="../public/js/jquery-ui.min.js"></script>
    <script src="../public/js/date.format.js"></script>
    <script src="../public/js/setting.js"></script>
    <script src="../public/js/bootstrap.js"></script>
    <script src="../public/js/json2csv.js"></script>
