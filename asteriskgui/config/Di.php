<?php

use app\handlers\EmptyHtmlHandler;
use app\models\App;
use function DI\create;
use function DI\get;
use function FastRoute\simpleDispatcher;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7Server\ServerRequestCreator;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\UploadedFileFactoryInterface;
use Psr\Http\Message\UriFactoryInterface;

$route = include __DIR__ . '/Route.php';

return [
    App::class => create(App::class)
        ->property('dispatcher', get('Dispatcher'))
        ->property('container', get('container'))
        ->property('creator', get(ServerRequestCreator::class))
        ->property('responseFactory', get(ResponseFactoryInterface::class)),

    Psr17Factory::class                  => create(Psr17Factory::class),
    ResponseFactoryInterface::class      => get(Psr17Factory::class),
    StreamFactoryInterface::class        => get(Psr17Factory::class),
    ServerRequestFactoryInterface::class => get(Psr17Factory::class),
    UriFactoryInterface::class           => get(Psr17Factory::class),
    UploadedFileFactoryInterface::class  => get(Psr17Factory::class),

    ServerRequestCreator::class => create(ServerRequestCreator::class)
        ->constructor(
            get(ServerRequestFactoryInterface::class),
            get(UriFactoryInterface::class),
            get(UploadedFileFactoryInterface::class),
            get(StreamFactoryInterface::class)
        ),

    'Dispatcher' => function () use ($route) {
        return simpleDispatcher($route);
    },
    'Response'   => create(Response::class),

    'TemplatePath'    => __DIR__ . '/../view',
    'TemplateOptions' => [],
    'TemplateLoader'  => create(Twig_Loader_Filesystem::class)
        ->constructor(get('TemplatePath')),
    'Template'        => create(Twig_Environment::class)
        ->constructor(get('TemplateLoader'), get('TemplateOptions')),

    EmptyHtmlHandler::class => create(EmptyHtmlHandler::class)
        ->constructor(get('Response'), get('container'))
    ,
];