<?php

declare(strict_types=1);

namespace SrvKit\Ci\Vite\Config;

use SrvKit\Ci\Vite\Collectors\ViteToolbar;

class Registrar
{
    public static function Toolbar(): array
    {
        return [
            'collectors' => [
                ViteToolbar::class
            ],
        ];
    }
}
