<?php

include dirname(__FILE__) . "/../../db/pami_asterisk.php";

class SipChannelsRepository {
    //Event: CoreShowChannel
    //-Channel: SIP/141-00005049
    //UniqueID: 1477634487.141041
    //-Context: macro-dialout-trunk
    //-Extension: s
    //Priority: 20
    //ChannelState: 4
    //-ChannelStateDesc: Ring
    //-Application: Dial
    //-ApplicationData: SIP/78007328911/79112125438,300,Tt
    //-CallerIDnum: 74959990535
    //-CallerIDname:
    //ConnectedLineNum:
    //ConnectedLineName:
    //-Duration: 00:00:07
    //AccountCode:
    //BridgedChannel:
    //BridgedUniqueID:

    public function getAll($filter) {
        $prepared_filters = array_filter($filter);
        $use_filter = !empty($prepared_filters);
        $json = array();

        //send asterisk management command
        $asterisk_ami = new PAMI_AsteriskMGMT();
        $core_channels = $asterisk_ami->core_show_channels();

        foreach ($core_channels->getEvents() as $variable) {
            $event = $variable->getKeys();
            if ($event['event'] == 'CoreShowChannel') {
                if ($use_filter) {
                    $add = false;
                    foreach (array_keys($prepared_filters) as $hdr) {
                        if ($event[$hdr] == $filter[$hdr]) {
                            $add = true;
                        }
                    }
                    if ($add) {
                        array_push($json,  array(
                            'channel' => $event['channel'],
                            // 'context' =>  $event['context'],
                            // 'extension' =>  $event['extension'],
                            'duration' =>  $event['duration'],
                            'bridgedchannel' =>  $event['bridgedchannel'],
                            'channelstatedesc' =>  $event['channelstatedesc'],
                            'application' =>  $event['application'],
                            'applicationdata' =>  $event['applicationdata'],
                            'calleridnum' =>  $event['calleridnum'],
                            'calleridname' => $event['calleridname']
                        ));
                    }
                } else {
                    array_push($json,  array(
                        'channel' => $event['channel'],
                        // 'context' =>  $event['context'],
                        // 'extension' =>  $event['extension'],
                        'duration' =>  $event['duration'],
                        'bridgedchannel' =>  $event['bridgedchannel'],
                        'channelstatedesc' =>  $event['channelstatedesc'],
                        'application' =>  $event['application'],
                        'applicationdata' =>  $event['applicationdata'],
                        'calleridnum' =>  $event['calleridnum'],
                        'calleridname' => $event['calleridname']
                    ));
                }
            }
        }

        return $json;
    }
}
