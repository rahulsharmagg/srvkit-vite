<?php

namespace SrvKit\Vite\CI\Config;

use CodeIgniter\Config\BaseConfig;

class Vite extends BaseConfig
{
	public ?string $configPath = null;

	public ?int $port = 5173;

	public ?string $host = 'localhost';
}
