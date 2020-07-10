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

        if ($exception->getCode() === 404) {
            $response->getBody()->write(
                json_encode(['error' => $exception->getDescription()], JSON_THROW_ON_ERROR)
            );
        }

        return $response;
    }
}
