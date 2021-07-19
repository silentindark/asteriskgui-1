<?php

include dirname(__FILE__) . "/../../db/asterisk.php";
include dirname(__FILE__) . "/../../db/pami_asterisk.php";

class QueueStatusRepository {

    // ----------------------------------------------------------------------

    private function SearchBusyInChannelList($channels, $number, $state) {
        for ($i = 1; $i < count($channels); $i++) {
            if ($channels[$i]["CallerIDNum"] == $number) {
                $state = 'busy';
                break;
            }
        }
        return $state;
    }

    // ----------------------------------------------------------------------

    private function SearchNumberInString($str) {
        //Local/104@from-queue-00000038;2
        $step3 = substr($str, 0, strpos($str, '@'));
        $step4 = substr($step3, strpos($step3, '/') + 1, 15);
        return $step4;
    }

    // ----------------------------------------------------------------------

    private function SearchNumberInQueuestring($str) {
        $step1 = explode('(', trim($str));
        $step2 = explode(' ', trim($step1[1]));
        $step3 = substr($step2[0], 0, strpos($step2[0], '@'));
        $step4 = substr($step3, strpos($step2[0], '/') + 1, 15);
        return $step4;
    }

    // ----------------------------------------------------------------------

    private function SearchStatusInPeersList($peers, $number) {
        $state = 'na';
        for ($i = 0; $i < count($peers); $i++) {
            if ($peers[$i]["name"] == $number) {
                if (stripos($peers[$i]["state"], "OK") !== false) {
                    $state = 'aviable';
                    break;
                }
            }
        }
        return $state;
    }

    // ----------------------------------------------------------------------

    private function getChannels() {
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $data = $asterisk_ami->core_show_channels();

        foreach ($data->getEvents() as $record) {
            $event = $record->getKeys();
            if ($event['event'] == 'CoreShowChannel') {
                unset($event['event']);
                unset($event['actionid']);
                array_push($json, $event);
            }
        }

        return $json;
    }

    private function getPeers() {
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $data = $asterisk_ami->sip_peers();

        foreach ($data->getEvents() as $record) {
            $event = $record->getKeys();
            if ($event['event'] == 'PeerEntry') {
                array_push($json,  array(
                    'objectname' => $event['objectname'],
                    'ipaddress' =>  $event['ipaddress'],
                    'status' =>  $event['status'],
                ));
            }
        }

        return $json;
    }

    private function getQueuesStatuses() {
        $json = array();
        $queues = array();
        $members = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $data = $asterisk_ami->queue_status();

        foreach ($data->getEvents() as $record) {
            $event = $record->getKeys();
            if ($event['event'] == 'QueueParams') {
                unset($event['event']);
                unset($event['actionid']);
                array_push($queues, $event);
            } elseif ($event['event'] == 'QueueMember') {
                unset($event['event']);
                unset($event['actionid']);
                array_push($members, $event);
            }
        }

        foreach ($queues as $queue) {
            $name = $queue['queue'];
            unset($queue['queue']);
            $json[$name] = $queue;
            $json[$name]['members'] = array();
        }

        foreach ($members as $member) {
            $queue = $member['queue'];
            unset($member['queue']);
            array_push($json[$queue]['members'], $member);
        }

        return $json;
    }

    // ----------------------------------------------------------------------

    public function getAll($filter) {
        // filter vars
        if ($filter["filter"]) {
            $ffilter = $filter["filter"];
        } else {
            $ffilter = '';
        }

        //send asterisk management command
        $asterisk = new AsteriskMGMT();
        $rows = $asterisk->Command('queue show');

        $json = array();

        //search
        $arr_rows = explode(PHP_EOL, $rows);
        for ($first = 1; $first < count($arr_rows); $first++) {
            if (stripos($arr_rows[$first], "Response: Follows") !== false) break;
        }

        $peers = $this->getPeers();
        $channels = $this->getChannels();

        for ($i = $first; $i < count($arr_rows); $i++) {

            $find = true;
            $queue_num = '';
            $queue = array();
            $callers = array();
            $W = '';

            // Parse string from asterisk response
            if (stripos($arr_rows[$i], "--END COMMAND--") !== false) break; //if find end message, then break
            if (stripos($arr_rows[$i], "Privilege: Command") !== false) continue;
            if (stripos($arr_rows[$i], "Response: Follows") !== false) continue;
            if (stripos($arr_rows[$i], "Members:") !== false) continue;
            $str = $arr_rows[$i];
            if (empty($str)) continue; //if find space string then skip line

            $q = explode(" ", trim($str));
            $queue_num = $q[0]; //

            $i++;
            $i++;

            $callersmode = false;

            //Parse queue members until find empty string
            for ($n = $i; $n < count($arr_rows); $n++, $i++) {
                $find = true;
                $str = $arr_rows[$n];
                if ((stripos($str, "Privilege: Command") !== false)) continue;
                if ((stripos($str, "No Members") !== false)) {
                    $i++;
                    continue;
                }
                if ((stripos($str, "Callers:") !== false)) {
                    $i++;
                    $callersmode = true;
                    continue;
                }
                if ((stripos($str, "No Callers") !== false)) {
                    $i++;
                    break;
                }
                if (!$callersmode) { //search callers if true and members if false
                    $number = '';
                    if (empty($str)) break; //if find empty message, then break
                    //Search
                    if (($ffilter) && (stripos($str, $ffilter) === false)) {
                        $find = false;
                    }

                    // Parse member string
                    $q = explode("(", trim($str));
                    $member = $q[0];

                    $number = $this->SearchNumberInQueuestring($str);

                    $state = 'na';
                    if (stripos($str, '(Unavailable)') !== false)  $state = 'na';
                    if (stripos($str, '(Not in use)') !== false)  $state = 'aviable';
                    if (stripos($str, '(In use)') !== false)  $state = 'busy';
                    if (stripos($str, '(Busy)') !== false)  $state = 'busy';
                    if (stripos($str, '(Ringing)') !== false)  $state = 'ring';

                    //   $state = $this->SearchStatusInPeersList($peers, $number);
                    // $state = $this->SearchBusyInChannelList($channels, $number, $state);

                    //made array of members
                    if (!empty($str))
                        if ($find) array_push($queue,  array(
                            'member' => $member,
                            'number' => $number,
                            'state' => $state
                        ));
                }
            }
            foreach ($channels as $channel) {
                if (($channel["application"] == "AppQueue") && ($channel["exten"] == $queue_num)) {  //
                    array_push($callers,  array(
                        'from' => $str["connectedlineNum"],
                        'state' => $str["channelstatedesc"],
                        'to' =>   $this->SearchNumberInString($str["channel"]),
                        'queue' => $str["exten"],
                        'time' => $str["duration"]
                    ));
                }
            }
            // push final record to array
            array_push($json,  array(
                'queue' => $queue_num,
                'callers' => $callers,
                'members' => $queue
            ));
        }

        return $json;
    }
}
