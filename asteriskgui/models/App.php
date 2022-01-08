<?php

namespace app\models;

use app\middlewares\TwigTemplateHtml;
use FastRoute\Dispatcher;
use Middlewares\Emitter;
use Middlewares\FastRoute;
use Middlewares\RequestHandler;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Relay\Relay;

class App
{
    /** @var ResponseFactoryInterface */
    private $responseFactory;
    /** @var ServerRequestCreatorInterface */
    private $creator;
    /** @var ServerRequestInterface */
    private $request;
    /** @var ResponseInterface */
    private $response;

    /** @var Dispatcher */
    private $dispatcher;

    /** @var ContainerInterface */
    private $container;

    public function init()
    {
        $this->request = $this->creator->fromGlobals();
        $this->response = $this->responseFactory->createResponse();
    }

    public function run()
    {
        $middlewareQueue[] = new Emitter();
        $middlewareQueue[] = new FastRoute($this->dispatcher);
        $middlewareQueue[] = new TwigTemplateHtml($this->container);
        $middlewareQueue[] = new RequestHandler($this->container);

        $requestHandler = new Relay($middlewareQueue);
        $this->response = $requestHandler->handle($this->request);
    }
}