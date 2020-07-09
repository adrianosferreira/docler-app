<?php

namespace App\Services;

class SerializerJson implements SerializerInterface
{
    public function parse($data)
    {
        return json_encode($data);
    }
}