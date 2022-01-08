<?php

use app\handlers\EmptyHtmlHandler;
use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->addRoute('GET', '/report/cdr', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/report/extended', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/report/noanswer', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/report/group', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/queue', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/diag/database', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/diag/total', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/sip/channels', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/sip/channelstats', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/sip/peers', EmptyHtmlHandler::class);
    $r->addRoute('GET', '/sip/registry', EmptyHtmlHandler::class);
};
