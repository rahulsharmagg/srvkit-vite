<?php
declare(strict_types=1);

namespace SrvKit\Ci\Vite\Config;

use SrvKit\Vite\Config;

class Vite extends Config {
	public int $port = 5173;

	public string $host = 'localhost';

	public ?string $viteConfigPath = ROOTPATH;

	public ?string $viteManifestPath = null;

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
