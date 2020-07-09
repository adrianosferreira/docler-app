<?php

namespace App\Services;

use App\Controllers\ControllerInterface;
use Slim\App;

interface RoutesBuilderInterface
{

    public function __construct(App $app, ControllerInterface $controller);

    public function build();
}