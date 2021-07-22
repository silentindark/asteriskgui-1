<?php

include dirname(__FILE__) . "/../../db/asterisk.php";

class DiagTotalRepository {

    public function getAll() {
        $names_dict = [
            'amiversion' => 'AMI version',
            'asteriskversion' => 'Asterisk version',
            'systemname' => 'System name',
            'coremaxcalls' => 'Core max calls',
            'coremaxloadavg' => 'Core maxload average',
            'corerunuser' => 'Run as user',
            'corerungroup' => 'Run in group',
            'coremaxfilehandles' => 'Core max file handlers',
            'corerealtimeenabled' => 'Realtime enabled?',
            'corecdrenabled' => 'CDR enabled?',
            'corehttpenabled' => 'HTTP enabled?',
            'corestartupdate' => 'Asterisk started at',
            'corestartuptime' => 'Asterisk started time',
            'corereloaddate' => 'Asterisk last reload at',
            'corereloadtime' => 'Asterisk last reload time',
            'corecurrentcalls' => 'Current calls',
        ];

        $json = array();
        $asterisk_ami = new PAMI_AsteriskMGMT();

        $data = $asterisk_ami->core_show_settings()->getKeys();
        foreach ($data as $k => $v) {
            if (array_key_exists($k, $names_dict)) {
                array_push($json, ['metric' => $names_dict[$k], 'value' => $v]);
            }
        }

        $data = $asterisk_ami->core_show_status()->getKeys();
        foreach ($data as $k => $v) {
            if (array_key_exists($k, $names_dict)) {
                array_push($json, ['metric' => $names_dict[$k], 'value' => $v]);
            }
        }

        $data = $asterisk_ami->сmd_get_sysinfo();
        foreach ($data as $k) {
            array_push($json, ['metric' => $k['key'], 'value' => $k['value']]);
        }

        $data = $asterisk_ami->сmd_get_uptime();
        foreach ($data as $k) {
            array_push($json, ['metric' => $k['key'], 'value' => $k['value']]);
        }

        return $json;
    }
}
