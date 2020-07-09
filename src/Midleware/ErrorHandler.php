<?php

namespace App\Midleware;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

class ErrorHandler
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
        if ( $exception->getCode() === 404 ) {
            $response = $this->responseFactory->createResponse();
            $response->getBody()->write(
                json_encode(['error' => 'Route not found'])
            );

            return $response;
        }
    }
}