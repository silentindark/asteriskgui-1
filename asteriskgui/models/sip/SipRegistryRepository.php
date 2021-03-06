<?php

namespace app\models\sip;

use app\models\PAMI_AsteriskMGMT;

class SipRegistryRepository
{
    private $config;

    function __construct(?array $config = null)
    {
        $this->config = $config;
    }

    public function getAll($filter)
    {
        $prepared_filters = array_filter($filter);
        $use_filter = !empty($prepared_filters);
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT($this->config);
        $registers = $asterisk_ami->sip_show_registry();
        foreach ($registers->getEvents() as $variable) {
            $event = $variable->getKeys();
            if ($event['event'] == 'RegistryEntry') {
                if ($use_filter) {
                    $add = false;
                    foreach (array_keys($prepared_filters) as $hdr) {
                        if ($event[$hdr] == $filter[$hdr]) {
                            $add = true;
                        }
                    }
                    if ($add) {
                        array_push($json, array(
                            'host'              => $event['host'],
                            'port'              => $event['port'],
                            'username'          => $event['username'],
                            'state'             => $event['state'],
                            'registration_time' => gmdate("D, d M Y H:i:s T", $event['registrationtime'])
                        ));
                    }
                } else {
                    array_push($json, array(
                        'host'              => $event['host'],
                        'port'              => $event['port'],
                        'username'          => $event['username'],
                        'state'             => $event['state'],
                        'registration_time' => gmdate("D, d M Y H:i:s T", $event['registrationtime'])
                    ));
                }
            }
        }

        return $json;
    }
}
