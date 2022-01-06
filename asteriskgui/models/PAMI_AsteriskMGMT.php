<?php

namespace app\models;

use app\models\eventListeners\A;
use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Action\CoreShowChannelsAction;
use PAMI\Message\Action\CoreSettingsAction;
use PAMI\Message\Action\CoreStatusAction;
use PAMI\Message\Action\CommandAction;
use PAMI\Message\Action\DBGetAction;
use PAMI\Message\Action\SIPPeersAction;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\QueueStatusAction;
use PAMI\Message\Action\QueueSummaryAction;

class PAMI_AsteriskMGMT
{
    /** @var array */
    protected $config;
    /** @var PamiClient */
    protected $pami_asterisk;
    /** @var string */
    protected $listener_id;

    function __construct(?array $config = null)
    {
        $this->config = $config;
        $this->pami_asterisk = new PamiClient($this->config['PAMI']);
        $this->listener_id = $this->pami_asterisk->registerEventListener(new A());
    }

    function __destruct()
    {
        $this->pami_asterisk->unregisterEventListener($this->listener_id);
    }

    public function core_show_channels()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new CoreShowChannelsAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function core_show_settings()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new CoreSettingsAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function core_show_status()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new CoreStatusAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function command($cmd)
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new CommandAction($cmd));
        $this->pami_asterisk->close();
        return $res;
    }

    public function dbget($family = '', $key = '')
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new DBGetAction($family, $key));
        $this->pami_asterisk->close();
        return $res;
    }

    public function сmd_get_database()
    {
        $res = [];
        $this->pami_asterisk->open();
        $output = $this->pami_asterisk->send(new CommandAction('database show'));
        $this->pami_asterisk->close();
        $raw_data = explode("\n", array_pop(explode("\r\n", $output->getRawContent())));
        // do some clenup - remove last 2 elements
        unset($raw_data[count($raw_data) - 1]);
        unset($raw_data[count($raw_data) - 1]);
        foreach ($raw_data as $line) {
            $db_record = explode(' : ', preg_replace("/\s+/", " ", trim($line)));
            array_push($res, ['key' => $db_record[0], 'value' => $db_record[1]]);
        }

        return $res;
    }

    public function сmd_get_sysinfo()
    {
        $res = [];
        $this->pami_asterisk->open();
        $output = $this->pami_asterisk->send(new CommandAction('core show sysinfo'));
        $this->pami_asterisk->close();
        $raw_data = explode("\n", array_pop(explode("\r\n", $output->getRawContent())));
        // do some clenup - remove last 2 elements
        $counter = count($raw_data);
        unset($raw_data[$counter - 1]);
        unset($raw_data[$counter - 2]);
        unset($raw_data[0]);
        unset($raw_data[1]);
        unset($raw_data[2]);
        foreach ($raw_data as $line) {
            if ($line != '') {
                $db_record = explode(': ', preg_replace("/\s+/", " ", trim($line)));
                array_push($res, ['key' => $db_record[0], 'value' => $db_record[1]]);
            }
        }

        return $res;
    }

    public function сmd_get_uptime()
    {
        $res = [];
        $this->pami_asterisk->open();
        $output = $this->pami_asterisk->send(new CommandAction('core show uptime'));
        $this->pami_asterisk->close();
        $raw_data = explode("\n", array_pop(explode("\r\n", $output->getRawContent())));
        // do some clenup - remove last element
        unset($raw_data[count($raw_data) - 1]);
        foreach ($raw_data as $line) {
            $db_record = explode(': ', preg_replace("/\s+/", " ", trim($line)));
            array_push($res, ['key' => $db_record[0], 'value' => $db_record[1]]);
        }

        return $res;
    }

    public function сmd_sip_show_channelstats()
    {
        $res = [];
        $this->pami_asterisk->open();
        $output = $this->pami_asterisk->send(new CommandAction('sip show channelstats'));
        $this->pami_asterisk->close();
        // Parse output like:
        // Response: Follows\r\nPrivilege: Command\r\nActionID: 1626259472.5077\r\nPeer             Call ID      Duration Recv: Pack  Lost       (     %) Jitter Send: Pack  Lost       (     %) Jitter\n10.80.0.96       593547436    00:17:19 0000051987  0000000002 ( 0.00%) 0.0000 0000051987  0000000012 ( 0.02%) 0.0104\n1 active SIP channel\n--END COMMAND--
        $raw_data = explode("\n", array_pop(explode("\r\n", $output->getRawContent())));
        // do some cleanup - remove first and last 2 elements
        unset($raw_data[0]);
        unset($raw_data[count($raw_data)]);
        unset($raw_data[count($raw_data)]);

        foreach ($raw_data as $line) {
            $line = preg_replace("/(\(|%\))/", "", $line);
            $chan_info = explode(" ", preg_replace("/\s+/", " ", $line));
            array_push($res, [
                'peer'              => $chan_info[0],
                'callid'            => $chan_info[1],
                'duration'          => $chan_info[2],
                'recv_pack'         => $chan_info[3],
                'recv_lost'         => $chan_info[4],
                'recv_lost_percent' => $chan_info[5],
                'recv_jitter'       => $chan_info[6],
                'send_pack'         => $chan_info[7],
                'send_lost'         => $chan_info[8],
                'send_lost_percent' => $chan_info[9],
                'send_jitter'       => $chan_info[10]
            ]);
        }

        return $res;
    }

    public function sip_peers()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new SIPPeersAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function sip_show_registry()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new SIPShowRegistryAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function queue_status()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new QueueStatusAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function queue_summary()
    {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new QueueSummaryAction());
        $this->pami_asterisk->close();
        return $res;
    }
}
