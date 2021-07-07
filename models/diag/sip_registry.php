<?php

include dirname(__FILE__) . "/../../db/pami_asterisk.php";

class SipRegistryRepository {
    public function getAll($filter) {
        $use_filter = !empty(array_filter($filter));
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $registers = $asterisk_ami->sip_show_registry();
        foreach ($registers->getEvents() as $variable) {
            $event = $variable->getKeys();
            if ($event['event'] == 'RegistryEntry') {
                if ($use_filter) {
                    if ($event['host'] == $filter['host'] || $event['username'] == $filter['username'] || $event['state'] == $filter['state']) {
                        array_push($json,  array(
                            'host' => $event['host'] . ":" . $event['port'],
                            'username' =>  $event['username'],
                            'state' => $event['state'],
                            'registration time' => gmdate("D, d M Y H:i:s T", $event['registrationtime'])
                        ));
                    }
                } else {
                    array_push($json,  array(
                        'host' => $event['host'] . ":" . $event['port'],
                        'username' =>  $event['username'],
                        'state' => $event['state'],
                        'registration time' => gmdate("D, d M Y H:i:s T", $event['registrationtime'])
                    ));
                }
            }
        }
        //error_log("ast: ".$row.PHP_EOL);
        return $json;
    }
}
