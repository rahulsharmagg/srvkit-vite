<?php

use PHPUnit\Framework\TestCase;
use SrvKit\Vite;

class ViteTest extends TestCase
{
    protected function setUp(): void
    {

    }

    public function testExample()
    {
    	$this->assertTrue(true);
    }

    public function testViteRunning()
    {
    	$vite = new Vite('localhost', 5173);
    	$this->assertIsBool($vite->isRunning());
    }
}