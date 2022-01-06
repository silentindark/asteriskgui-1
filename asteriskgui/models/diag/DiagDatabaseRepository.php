<?php

namespace app\models\diag;

use app\models\PAMI_AsteriskMGMT;

class DiagDatabaseRepository
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
        $database = $asterisk_ami->сmd_get_database();
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