<?php

namespace App\Services\Response;

use Psr\Http\Message\ResponseInterface as Response;

interface ResponseFormatterInterface
{

    public function format(Response $response, $data = null, $exception = null, $created = false);
}
