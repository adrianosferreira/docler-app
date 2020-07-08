<?php

use App\Database\EntityManagerFactory;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once __DIR__ . '/vendor/autoload.php';

return ConsoleRunner::createHelperSet((new EntityManagerFactory())->create());