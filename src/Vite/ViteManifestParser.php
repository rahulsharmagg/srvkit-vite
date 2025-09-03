<?php

namespace SrvKit\Vite;

use RuntimeException;

class ViteManifestParser{
	protected string $path;

	public function __construct(?string $manifestPath = null)
	{
		$this->setPath($manifestPath ?? '.');
	}

	protected function setPath(string $outDir): void
	{
		$this->path = rtrim($outDir, DIRECTORY_SEPARATOR)
							. DIRECTORY_SEPARATOR
							. '.vite'
							. DIRECTORY_SEPARATOR
							. 'manifest.json';
	}

	/**
	 * Get manifest data from vite build
	 * @return array
	 */
	public function parse(): array
	{
		$manifestPath = $this->path;
		if (!file_exists($manifestPath)) {
		    throw new RuntimeException("Vite manifest file not found at {$manifestPath}");
		}

		$jsonContent = file_get_contents($manifestPath);
		if ($jsonContent === false) {
		    throw new RuntimeException("Failed to read Vite manifest file at {$manifestPath}");
		}

		$manifestData = json_decode($jsonContent, true);
		if (json_last_error() !== JSON_ERROR_NONE) {
		    throw new RuntimeException("Error decoding Vite manifest file at {$manifestPath}: " . json_last_error_msg());
		}

		return $manifestData;
	}
}