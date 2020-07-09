<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ResponseBuilderJson implements ResponseBuilderInterface
{

    public function get(Request $request, Response $response, $args, $data)
    {
        $response->getBody()->write($data);

        return $response->withHeader('Content-Type', 'application/json');
    }
}