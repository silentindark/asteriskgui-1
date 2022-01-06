<?php
/**
 * @var string $page
 */
?><!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8" />
    <title>Asterisk Report</title>
    <link rel="stylesheet" type="text/css" href="public/css/index.css" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,600,400" rel="stylesheet" type="text/css">
</head>
<body>
<div class="navigation">
    <h1>Asterisk</h1>
    <ul>
        <li>Report
            <ul>
                <li><a href="index.php?p=rep.cdr" >Call records</a></li>
                <!--
                                <li><a href="index.php?p=rep.cdr.ext" >Call records extended</a></li>
                                <li><a href="index.php?p=rep.noanswer" >Not answered</a></li>
                                <li><a href="index.php?p=rep.group.ext.cdr">Group by Extension</a></li>
                -->
            </ul>
        </li>
        <li>Queue
            <ul>
                <li><a href="index.php?p=queue">Show status</a></li>
            </ul>
        </li>
        <li>Diagnostics
            <ul>
                <li><a href="index.php?p=diag.total" >Total</a></li>
                <li><a href="index.php?p=diag.database">Database</a></li>
            </ul>
        </li>
        <li>SIP
            <ul>
                <li><a href="index.php?p=sip.registry" >Sip Registry</a></li>
                <li><a href="index.php?p=sip.peers" >Sip Peers</a></li>
                <li><a href="index.php?p=sip.channels" >Channels</a></li>
                <li><a href="index.php?p=sip.channelstats" >Channel Stats</a></li>
            </ul>
        </li>
        <li><a href="/?is_exit=1">Logout</a></li>
        </li>
    </ul>
</div>
<div class="index-frame">
    <iframe name="index" src="view/<?=$page?>.php"></iframe>
</div>
</body>
</html>