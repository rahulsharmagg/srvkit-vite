<?php
declare(strict_types=1);

namespace SrvKit\Ci\Vite\Config;

// use CodeIgniter\Config\BaseConfig;
use SrvKit\Vite\Config;

class Vite extends Config {
	public int $port = 5173;

	public string $host = 'localhost';

	public ?string $viteConfigPath = ROOTPATH;

	public ?string $viteManifestPath = null;
}
