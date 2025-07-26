<?php

use SrvKit\Vite\Vite;
use SrvKit\Vite\Config as ViteConfig;
use PHPUnit\Framework\TestCase;

class ViteTest extends TestCase
{   
    protected ViteConfig $config;
    protected function setUp(): void
    {
        $this->config = $this->config = $this->createMock(ViteConfig::class);
    }

    public function testViteRunning()
    {
    	$vite = new Vite($this->config);
    	$this->assertIsBool($vite->isRunning());
    }
}