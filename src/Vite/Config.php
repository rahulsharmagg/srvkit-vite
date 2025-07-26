<?php

namespace SrvKit\Vite; 

/**
 * Vite Configuration
 * 
 * @since 0.1.5
 */
abstract class Config{
	/**
	 * Vite Port
	 * @var integer
	 */
	public int $port = 5173;

	/**
	 * Vite Host example:`localhost`, `127.0.0.1`
	 * 
	 * @var string
	 */
	public string $host = 'localhost';

	/**
	 * Vite config file path like:`./vite.config.js`
	 *
	 * If null then it will auto detect in root foler
	 * 
	 * @var null
	 */
	public ?string $viteConfigPath = null;

	/**
	 * Vite manifest file path like `.vite/manifest.json`
	 * @var null
	 */
	public ?string $viteManifestPath = null;
}
