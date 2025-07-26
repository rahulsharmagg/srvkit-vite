<?php
declare(strict_types=1);

namespace SrvKit\Vite;

use SrvKit\Vite\Config;

class Vite{
	public const VERSION = '0.1.5';

	protected ?string $host;

	protected ?int $port;

	public function __construct(protected Config $config)
	{
		$this->host = $this->config->host;
		$this->port = $this->config->port;
	}

	public function isRunning() {
	    $connection = @fsockopen($this->host, $this->port, $errno, $errstr, 1); // 1-second timeout
	    
	    if ($connection) {
	        fclose($connection);
	        return true;
	    }

	    return false;
	}

	public function sharedInfo(): array
	{
		$data = [
			'host' => $this->host,
			'port' => $this->port,
			'vite.config' => $this->config->viteConfigPath,
			'vite.manifest' => $this->config->viteManifestPath
		];

		return $data;
	}

	private function parseViteConfig(): array
	{
		return [];
	}
}