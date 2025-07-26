<?php

namespace SrvKit\Ci\Vite\Config;

use SrvKit\Vite\Vite;
use CodeIgniter\Config\BaseService;
use SrvKit\Ci\Vite\Config\Vite as ViteConfig;

class Services extends BaseService{
	public static function vite(bool $getShared = true): Vite
	{
	    if ($getShared) {
	        return static::getSharedInstance('vite');
	    }

	    /** @var ViteConfig $config */
	    $config = config('Vite');
	    return new Vite($config);
	}
}