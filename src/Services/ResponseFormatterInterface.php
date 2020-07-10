<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;

interface ResponseFormatterInterface
{

    public function format(Response $response, $data = null, $exception = null);
}
