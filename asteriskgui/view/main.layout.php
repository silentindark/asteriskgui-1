<?php
/**
 * @var string $page
 */
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8"/>
    <title>Asterisk Report</title>
    <link rel="stylesheet" type="text/css" href="public/css/index.css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600,400" rel="stylesheet" type="text/css">
</head>
<body>
<div class="navigation">
    <h1>Asterisk</h1>
    <?php include 'main.menu.php' ?>
</div>
<div class="index-frame">
    <?php include $page . '.php' ?>
</div>
</body>
</html>