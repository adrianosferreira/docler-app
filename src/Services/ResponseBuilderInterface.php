<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface ResponseBuilderInterface
{

    public function get(Request $request, Response $response, $data);
}