<?php

namespace app\handlers\report;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Cdr
{
    /** @var ResponseInterface */
    private $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __invoke(RequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        ob_start();
        include __DIR__ . '/../../view/rep.cdr.php';
        $body = ob_get_contents();
        ob_end_clean();

        $response = $this->response->withHeader('Content-Type', 'text/html');
        $response->getBody()->write($body);

        return $response;
    }
}