<?php

namespace App\Midleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandlerMiddleware
{

    private ResponseFactoryInterface $responseFactory;

    public function __construct(ResponseFactoryInterface $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    public function get(
        ServerRequestInterface $request,
        $exception
    ) {
        $response = $this->responseFactory->createResponse();
        $response->getBody()->write(
            json_encode(['error' => $exception->getMessage()], JSON_THROW_ON_ERROR)
        );
        return $response->withStatus($exception->getCode());
    }
}
