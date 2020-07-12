<?php

namespace App\Services\Routes;

use App\Controllers\ControllerInterface;
use Slim\App;

interface RoutesBuilderInterface
{

    public function __construct(App $app, ControllerInterface $controller);

    public function build();
}
