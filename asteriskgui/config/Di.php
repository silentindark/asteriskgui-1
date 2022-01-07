<?php

use app\handlers\report\Cdr;
use app\models\App;
use function DI\create;
use function DI\get;
use function FastRoute\simpleDispatcher;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;

$route = include __DIR__ . '/Route.php';

return [
    App::class                  => create(App::class)
        ->property('dispatcher', get('dispatcher'))
        ->property('container', get('container'))
        ->property('creator', get(ServerRequestCreator::class))
        ->property('responseFactory', get(Psr17Factory::class)),
    Psr17Factory::class         => create(Psr17Factory::class),
    ServerRequestCreator::class => create(ServerRequestCreator::class)
        ->constructor(
            get(Psr17Factory::class),
            get(Psr17Factory::class),
            get(Psr17Factory::class),
            get(Psr17Factory::class)
        ),
    'dispatcher'                => function () use ($route) {
        return simpleDispatcher($route);
    },
    'Response'                  => function () {
        return new Response();
    },

    Cdr::class => create(Cdr::class)->constructor(get('Response')),
];