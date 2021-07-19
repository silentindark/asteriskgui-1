<?php

include dirname(__FILE__) . "/../../db/asterisk.php";

class DiagDatabaseRepository {

    public function getAll($filter) {
        $prepared_filters = array_filter($filter);
        $use_filter = !empty($prepared_filters);
        $json = array();

        $asterisk_ami = new PAMI_AsteriskMGMT();
        $database = $asterisk_ami->get_database();
        foreach ($database as $record) {
            if ($use_filter) {
                $add = false;
                foreach (array_keys($prepared_filters) as $hdr) {
                    if (strpos($record[$hdr], $filter[$hdr])) {
                        $add = true;
                    }
                }
                if ($add) {
                    array_push($json, $record);
                }
            } else {
                array_push($json, $record);
            }
        }

        return $json;
    }
}