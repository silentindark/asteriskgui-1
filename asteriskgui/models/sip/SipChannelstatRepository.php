<?php

namespace app\models\sip;

use app\models\PAMI_AsteriskMGMT;

class SipChannelstatRepository
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

        //send asterisk management command
        $asterisk_ami = new PAMI_AsteriskMGMT($this->config);
        $sip_channelstats = $asterisk_ami->сmd_sip_show_channelstats();
        foreach ($sip_channelstats as $chanelstat) {
            array_push($json, $chanelstat);
        }

        return $json;
    }
}
