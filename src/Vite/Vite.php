<?php
declare(strict_types=1);

namespace SrvKit\Vite;

defined('ROOTPATH') || define('ROOTPATH', realpath('.' . DIRECTORY_SEPARATOR));

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

	public function getConfig(): array
	{
		$configPath = $this->config->viteConfigPath;
		if($configPath === null){
			$configPath = ROOTPATH;
		}

		$parser = new ViteConfigParser($configPath);
		return $parser->parse();
	}

	public function getManifest(): array
	{
		$manifestPath = $this->config->viteManifestPath;
		if($manifestPath === null){
			$viteConfig = $this->getConfig();
			$rootDir = $viteConfig['root'];
			$manifestPath = ROOTPATH . $rootDir . DIRECTORY_SEPARATOR . $viteConfig['build']['outDir'];
		}
		$parser = new ViteManifestParser($manifestPath);
		return $parser->parse();
	}

	public function getBuild(): array
	{
		$viteConfig = $this->getConfig();
		$serverOrigin = isset($viteConfig['base']) ? $viteConfig['base'] : '/';
		$build = [];
		$manifest = $this->getManifest();
		foreach($manifest as $file => $buildInfo){
		    if(isset($buildInfo["css"])){
		        foreach($buildInfo["css"] as $_css){
		            $build['styles'][] =$serverOrigin . $_css;
		        }
		    }
		    if(isset($buildInfo["isEntry"]) && $buildInfo["isEntry"]){
		        $build['javascripts'][] = $serverOrigin . $buildInfo["file"];
		    }
		}

		return $build;
	}
}