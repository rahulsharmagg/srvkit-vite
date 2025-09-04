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

	/**
	 * Compatibility for Codeigniter4 cache
	 * @param  array  $an_array
	 * @return Vite          
	 */
	public static function __set_state(array $an_array): object
    {
        $obj = new static();
        foreach ($an_array as $key => $value) {
            if (property_exists($obj, $key)) {
                $obj->$key = $value;
            }
        }
        return $obj;
    }
}
