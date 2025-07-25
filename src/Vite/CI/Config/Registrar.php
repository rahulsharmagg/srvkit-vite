<?php

declare(strict_types=1);

/**
 * This file is part of SrvKit Vite.
 *
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace SrvKit\Vite\CI\Config;

use SrvKit\Vite\CI\ToolbarCollector;

class Registrar
{
    public static function Toolbar(): array
    {
        return [
            'collectors' => [
            	ToolbarCollector::class
            ],
        ];
    }
}
