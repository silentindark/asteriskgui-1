<?php

class AsteriskMGMT {

    public function Command($cmd) {
        // $config = include("config.php");
        $config = include("pami_config.php");

        $socket = fsockopen($config["PAMI"]["host"],$config["PAMI"]["port"], $errno, $errstr, 30);

        if (!$socket) {
            error_log("$errstr ($errno)".PHP_EOL);
            return $errstr;
        } else {
            fputs($socket, "Action: Login\r\n");
            fputs($socket, "UserName: ".$config["PAMI"]["username"]."\r\n");
            fputs($socket, "Secret: ".$config["PAMI"]["secret"]."\r\n\r\n");

            fputs($socket, "Action: Command\r\n");
            fputs($socket, "Command: ".$cmd."\r\n\r\n"); 
            fputs($socket, "Action: Logoff\r\n\r\n");
            $wrets = "";
            while (!feof($socket)) {
                $wrets .= fread($socket, 8192);
            }
            fclose($socket);
            return $wrets;
        }
    }

    public function Action($cmd) {
        // $config = include("config.php");
        $config = include("pami_config.php");

        $socket = fsockopen($config["PAMI"]["host"],$config["PAMI"]["port"], $errno, $errstr, 30);

        if (!$socket) {
                error_log("$errstr ($errno)".PHP_EOL);
            return $errstr;
        } else {
            fputs($socket, "Action: Login\r\n");
            fputs($socket, "UserName: ".$config["PAMI"]["username"]."\r\n");
            fputs($socket, "Secret: ".$config["PAMI"]["secret"]."\r\n\r\n");

            fputs($socket, "Action: ".$cmd."\r\n\r\n");
            fputs($socket, "ActionID: 123\r\n\r\n");
            fputs($socket, "Action: Logoff\r\n\r\n");
            $wrets = "";
            while (!feof($socket)) {
                $wrets .= fread($socket, 8192);
            }
            fclose($socket);
            return $wrets;
        }
    }
}
