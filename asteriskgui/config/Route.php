<?php

use app\handlers\report\Cdr;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/report/cdr', Cdr::class);
};
