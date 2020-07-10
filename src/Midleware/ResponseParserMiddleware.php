<?php

namespace App\Midleware;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class ResponseParserMiddleware implements MiddlewareInterface
{

    public function process(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getBody()->getContents();
        parse_str($body, $contents);
        $request = $request->withParsedBody($contents);

        if (
            strpos($request->getHeaderLine('Content-Type'), 'application/json')
            !== false
        ) {
            $contents = json_decode(
                $body,
                true,
                512,
                JSON_THROW_ON_ERROR
            );
            if (json_last_error() === JSON_ERROR_NONE) {
                $request = $request->withParsedBody($contents);
            }
        }

        return $handler->handle($request);
    }
}
