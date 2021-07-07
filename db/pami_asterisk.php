<?php
require(implode(DIRECTORY_SEPARATOR, array(
    __DIR__,
    '..',
    'vendor',
    'autoload.php'
)));

use PAMI\Client\Impl\ClientImpl as PamiClient;
use PAMI\Message\Event\EventMessage;
use PAMI\Listener\IEventListener;
use PAMI\Message\Action\SIPShowRegistryAction;
use PAMI\Message\Action\SIPPeersAction;

class A implements IEventListener {
    public function handle(EventMessage $event) {
        // var_dump($event);
    }
}

class PAMI_AsteriskMGMT {
    protected $config;
    protected $pami_asterisk;
    protected $listener_id;

    function __construct() {
        $this->config = include('pami_config.php');
        $this->pami_asterisk = new PamiClient($this->config['PAMI']);
        $this->listener_id = $this->pami_asterisk->registerEventListener(new A());
    }

    function __destruct() {
        $this->pami_asterisk->unregisterEventListener($this->listener_id);
    }

    public function sip_show_registry() {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new SIPShowRegistryAction());
        $this->pami_asterisk->close();
        return $res;
    }

    public function sip_peers() {
        $this->pami_asterisk->open();
        $res = $this->pami_asterisk->send(new SIPPeersAction());
        $this->pami_asterisk->close();
        return $res;
    }
}