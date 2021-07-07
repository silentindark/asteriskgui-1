<?php

include dirname(__FILE__) . "/../../db/pami_asterisk.php";

class SipPeerRepository {
    public function getAll($filter) {
        $prepared_filters = array_filter($filter);
        $use_filter = !empty($prepared_filters);
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $sip_peers = $asterisk_ami->sip_peers();
        foreach ($sip_peers->getEvents() as $variable) {
            $event = $variable->getKeys();
            if ($event['event'] == 'PeerEntry') {
                if ($use_filter) {
                    $add = false;
                    foreach (array_keys($prepared_filters) as $hdr) {
                        if ($event[$hdr] == $filter[$hdr]) {
                            $add = true;
                        }
                    }
                    if ($add) {
                        array_push($json,  array(
                            'objectname' => $event['objectname'],
                            'ipaddress' =>  $event['ipaddress'],
                            'ipport' =>  $event['ipport'],
                            'status' =>  $event['status'],
                            'description' => $event['description']
                        ));
                    }
                } else {
                    array_push($json,  array(
                        'objectname' => $event['objectname'],
                        'ipaddress' =>  $event['ipaddress'],
                        'ipport' =>  $event['ipport'],
                        'status' =>  $event['status'],
                        'description' => $event['description']
                    ));
                }
            }
        }
        //error_log("ast: ".$row.PHP_EOL);
        return $json;
    }
}
