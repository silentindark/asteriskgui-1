<?php

namespace app\handlers;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
//use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\RequestHandlerInterface;

class EmptyHtmlHandler
{
    /** @var ResponseInterface */
    private $response;
    /** @var ContainerInterface */
    private $container;

    public function __construct(ResponseInterface $response, ContainerInterface $container)
    {
        $this->response = $response;
        $this->container = $container;
    }

    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
//        /** @var StreamFactoryInterface $responseFactory */
//        $responseFactory = $this->container->get(StreamFactoryInterface::class);
//        $stream = $responseFactory->createStream('');
//        return $this->response->withHeader('Content-Type', 'text/html')->withBody($stream);

        return $this->response->withHeader('Content-Type', 'text/html');
    }
}