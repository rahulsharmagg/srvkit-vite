<?php

namespace SrvKit;

class Vite{
	protected ?string $host;

	protected ?int $port;

	public function __construct(string $host, int $port)
	{
		$this->host = $host;
		$this->port = $port;
	}

	public function isRunning() {
	    $connection = @fsockopen($this->host, $this->port, $errno, $errstr, 1); // 1-second timeout
	    
	    if ($connection) {
	        fclose($connection);
	        return true;
	    }

	    return false;
	}
}
