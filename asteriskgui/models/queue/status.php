<?php

include dirname(__FILE__) . "/../../db/asterisk.php";

class QueueStatusRepository {

    // ----------------------------------------------------------------------

    private function get_member_text_status($numeric_state) {
/*
        // TODO: Переписать на отображение этих статусов
        $states = [
            '0' => 'Неизвестно', // UNKNOWN
            '1' => 'Свободен', // NOT_INUSE
            '2' => 'Разговаривает', // INUSE
            '3' => 'Занято', // BUSY
            '4' => 'Ошибка', // INVALID
            '5' => 'Недоступен', // UNAVAILABLE
            '6' => 'Звонит', // RINGING
            '7' => 'Снята трубка', // RINGINUSE
            '8' => 'На удержании', // ONHOLD
        ];
*/
        $states = [
            '0' => 'na', // UNKNOWN
            '1' => 'aviable', // NOT_INUSE
            '2' => 'busy', // INUSE
            '3' => 'busy', // BUSY
            '4' => 'na', // INVALID
            '5' => 'na', // UNAVAILABLE
            '6' => 'ring', // RINGING
            '7' => 'na', // RINGINUSE
            '8' => 'na', // ONHOLD
        ];
        return $states[$numeric_state];
    }

    // ----------------------------------------------------------------------

    private function get_number_from_location($location) {
        if (strpos($location, '@') !== false) {
            $location = explode('@', $location)[0];
        }
        return explode('/', $location)[1];
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
        //send asterisk management command
        $asterisk_ami = new PAMI_AsteriskMGMT();
        $rows = $asterisk_ami->command('queue show')->getRawContent();

        $json = array();

        //search
        $arr_rows = explode(PHP_EOL, $rows);
        for ($first = 1; $first < count($arr_rows); $first++) {
            if (stripos($arr_rows[$first], "Response: Follows") !== false) break;
        }

        // $peers = $this->getPeers();
        $channels = $this->getChannels();
        $queues = $this->getQueuesStatuses();
        foreach ($queues as $queue_name => $queue) {
            /*
            // TODO: Переписчать под использование этой статистики, а не только мемберов и статусов
            array (
                'max' => '2',
                'strategy' => 'rrmemory',
                'calls' => '0',
                'holdtime' => '0',
                'talktime' => '0',
                'completed' => '0',
                'abandoned' => '0',
                'servicelevel' => '0',
                'servicelevelperf' => '0.0',
                'weight' => '0',
                'members' => 
                array (
                    0 => 
                    array (
                        'name' => 'SIP/999749',
                        'location' => 'SIP/999749',
                        'stateinterface' => 'SIP/999749',
                        'membership' => 'static',
                        'penalty' => '0',
                        'callstaken' => '0',
                        'lastcall' => '0',
                        'status' => '4',
                        'paused' => '0',
                    ),
                ),
            )
            */
            $cache =[
                'queue' => $queue_name,
                'callers' => array(),
                'members' => array()
            ];
            foreach ($queue['members'] as $member) {
                array_push($cache['members'], [
                    'member' => $member['name'],
                    'number' => $this->get_number_from_location($member['location']),
                    'state' => $this->get_member_text_status($member['status'])
                ]);
            }
            foreach ($channels as $channel) {
                if (($channel["application"] == "AppQueue") && ($channel["exten"] == $queue_name)) {  //
                    array_push($callers,  array(
                        'from' => $channel["connectedlineNum"],
                        'state' => $channel["channelstatedesc"],
                        'to' =>   $this->get_number_from_location($channel["bridgedchannel"]),
                        'queue' => $channel["exten"],
                        'time' => $channel["duration"]
                    ));
                }
            }
            array_push($json, $cache);
        }

        return $json;
    }
}
