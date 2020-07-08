<?php

namespace App\Database;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{

    public function create(): EntityManager
    {
        $paths     = array('./src/Entities');
        $isDevMode = false;

        $dbParams = array(
            'driver'   => 'pdo_mysql',
            'user'     => 'root',
            'password' => '1',
            'dbname'   => 'app',
            'host'     => 'db',
        );

        $config = Setup::createAnnotationMetadataConfiguration(
            $paths,
            $isDevMode,
            null,
            null,
            false
        );

        return EntityManager::create($dbParams, $config);
    }
}