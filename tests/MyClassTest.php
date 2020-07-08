<?php

namespace App\Tests;

use App\MyClass;
use PHPUnit\Framework\TestCase;

class MyClassTest extends TestCase
{

    /**
     * @test
     */
    public function testSomething() {
        $this->assertEquals(1, (new MyClass())->methodSomething());
    }
}