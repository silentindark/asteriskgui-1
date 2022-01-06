<?php

namespace app\models\report;

use mysqli;

class ReportCdrRepository
{
    /** @var mysqli */
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    private function read($row)
    {
        $result = new ReportCdr();

        $result->calldate = $row["calldate"];
        if (strlen($row["clid"]) < 3) {
            $result->clid = $row["whois"];
        } else {
            $result->clid = $row["clid"];
        }
        $result->src = $row["src"];
        if (strlen($row["clidrb"]) > 2) {
            $result->clidrb = "V";
        }
        $result->cc = $row["cc"];
        $result->src = $row["src"];

        $result->dst = $row["dst"];
        $result->ext = $row["ext"];
        $result->whois = $row["whois"];
        $result->dcontext = $row["dcontext"];
        $result->duration = $row["duration"];
        $result->billsec = $row["billsec"];
        $result->recordingfile = $row["recordingfile"];
        $result->dstchannel = $row["dstchannel"];
        $result->lastapp = $row["lastapp"];
        $result->lastdata = $row["lastdata"];
        if (strlen($row["cc"]) > 1) {
            $result->disposition = "ANSWERED";
        } else {
            $result->disposition = "NO ANSWER";
        }

        return $result;
    }

    private function read_cdr($row)
    {
        $result = new ReportCdr();

        $result->calldate = $row["calldate"];
        $result->src = $row["src"];
        $result->dst = $row["dst"];
        $result->disposition = $row["disposition"];
        $result->duration = $row["duration"];
        $result->billsec = $row["billsec"];
        $result->recording = $row["recording"];

        return $result;
    }

    private function generate_conditions(&$filter)
    {
        $result = [];
        $filter['calldate_range_is_set'] = false;
        if (!is_null($filter["start"]) && $filter["start"] != '0' && !is_null($filter["end"]) && $filter["end"] != '0') {
            array_push($result, "calldate BETWEEN '" . $filter["start"] . " 00:00:00' AND '" . $filter["end"] . " 23:59:59'");
            $filter['calldate_range_is_set'] = true;
        }
        if (!is_null($filter["search_number"]) && $filter["search_number"] != '') {
            array_push($result, "(src LIKE '%" . $filter["search_number"] . "%' OR dst LIKE '%" . $filter["search_number"] . "%')");
        }

        if (!is_null($filter["search_state"]) && $filter["search_state"] != '') {
            array_push($result, "disposition = '" . $filter["search_state"] . "'");
        }

        return $result;
    }

    public function getAll($filter)
    {
        $conditions = $this->generate_conditions($filter);
        $condition = '';
        if ($conditions) {
            $condition = 'WHERE ' . implode(' AND ', $conditions);
        }

        $limit = '';
        if (!$filter['calldate_range_is_set']) {
            if (is_null($filter["calls_limit"]) || $filter["calls_limit"] == '' || $filter["calls_limit"] == '0') {
                $limit = 'LIMIT 20';
            } else {
                $limit = "LIMIT " . $filter["calls_limit"];
            }
        }

        $sql = "SELECT calldate, src, dst, disposition, duration, billsec, recording FROM cdr " . $condition . " ORDER BY calldate DESC " . $limit;
        $result = array();
        $rows = $this->db->query($sql);
        //$q->execute();
        while ($row = $rows->fetch_assoc()) {
            array_push($result, $this->read_cdr($row));
        }

        return $result;
    }
}
