<?php

require_once 'db/pami_config.php';
$config = include("db/pami_config.php");

header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
error_log("PATH: " . $config["logfile"] . PHP_EOL, 3, "log.log");


if (isset($_GET['audio'])) {
	$filename = $config['monitor'] . $_GET['audio'];
	// header("Content-Type: application/$system_audio_format");
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($filename));
	header("Content-Disposition: attachment; filename=" . $filename);
	readfile($filename);
}

exit();
