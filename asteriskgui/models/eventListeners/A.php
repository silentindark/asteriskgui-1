<?php

namespace app\models\eventListeners;

use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;

class A implements IEventListener
{
    public function handle(EventMessage $event)
    {
// var_dump($event);
    }
}