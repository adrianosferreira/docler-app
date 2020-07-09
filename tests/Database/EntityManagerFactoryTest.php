<?php

namespace App\Tests\Database;

use App\Database\EntityManagerFactory;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class EntityManagerFactoryTest extends TestCase
{

    /**
     * @test
     */
    public function itCreatesEntityManager() {
        $subject = new EntityManagerFactory();
        $this->assertInstanceOf(EntityManagerInterface::class, $subject->create());
    }
}
