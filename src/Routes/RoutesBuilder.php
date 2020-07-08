<?php

namespace App\Routes;

use Slim\App;

class RoutesBuilder
{

    public function run(App $app)
    {
        $app->get(
            '/',
            [(new Tasks()), 'getAll']
        );
    }
}