<?php

namespace app\middlewares;

use Exception;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Twig\Error\LoaderError;
use Twig_Environment;

/**
 * Шаблонизатор на основе Twig для text/html
 */
class TwigTemplateHtml implements MiddlewareInterface
{
    /**
     * @var ContainerInterface Used to resolve the handlers
     */
    private $container;

    /** @inheritDoc */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = $handler->handle($request);

        if (!in_array('text/html', $response->getHeader('Content-Type'))) {
            return $response;
        }

        /** @var StreamFactoryInterface $responseFactory */
        $responseFactory = $this->container->get(StreamFactoryInterface::class);
        /** @var Twig_Environment $template */
        $template = $this->container->get('Template');

        $path = $request->getUri()->getPath();
        $path = strtolower($path);

        $response->getBody()->rewind();
        $oldBody = $response->getBody()->getContents();

        try {
            $body = $template->render($path . '.html', ['content' => $oldBody]);
            $stream = $responseFactory->createStream($body);
            $stream->rewind();
            echo ($stream->getContents());
            die;
            return $response->withBody($stream);
        } catch (LoaderError $e) {
            $stream = $responseFactory->createStream($e->getMessage());
            return $response->withStatus(404)->withBody($stream);
        } catch (Exception $e) {
            $stream = $responseFactory->createStream($e->getMessage());
            return $response->withStatus(500)->withBody($stream);
        }
    }
}
