<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatterJson implements ResponseFormatterInterface
{

    public function format(
        Response $response,
        $data = null,
        \Throwable $exception = null
    ) {
        $res = [];

        if ($exception) {
            $res['error'] = $exception->getMessage();
        } elseif (is_string($data)) {
            $res['msg'] = $data;
        } else {
            $res = $data;
        }

        $response->getBody()->write(
            json_encode($res, JSON_THROW_ON_ERROR, 512)
        );

        return $response->withHeader('Content-Type', 'application/json');
    }
}