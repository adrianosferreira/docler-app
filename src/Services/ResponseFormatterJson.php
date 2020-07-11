<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseFormatterJson implements ResponseFormatterInterface
{

    public function format(
        Response $response,
        $data = null,
        $error = null,
        $created = false
    ) {
        $res  = [];
        $code = 200;

        if ($created) {
            $code = 201;
        } elseif ($error) {
            $code = 400;
        }

        if ($error) {
            $res['error'] = $error->getMessage();
        } elseif (is_string($data)) {
            $res['msg'] = $data;
        } else {
            $res = $data;
        }

        $response->getBody()->write(
            json_encode($res, JSON_THROW_ON_ERROR, 512)
        );

        return $response->withHeader('Content-Type', 'application/json')
            ->withStatus($code);
    }
}
