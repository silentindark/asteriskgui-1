<?php

include dirname(__FILE__) . "/../../db/asterisk.php";

class SipChannelstatRepository {
    public function getAll($filter) {
        $prepared_filters = array_filter($filter);
        $use_filter = !empty($prepared_filters);
        $json = array();

        //send asterisk management command
        $asterisk_ami = new PAMI_AsteriskMGMT();
        $sip_channelstats = $asterisk_ami->sip_show_channelstats();
        foreach ($sip_channelstats as $chanelstat) {
            array_push($json, $chanelstat);
        }

        return $json;
    }
}
