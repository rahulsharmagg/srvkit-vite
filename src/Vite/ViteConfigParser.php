<?php

namespace SrvKit\Vite;

class ViteConfigParser
{
    protected string $path;

    public function __construct(string $projectRoot)
    {
        $this->path = rtrim($projectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'vite.config.js';
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Parse the vite.config.js file
     * @return array{base: string, root: string, server: array, build: array}
     */
    public function parse(): array
    {
        if (!file_exists($this->path)) {
            throw new \RuntimeException("vite.config.js not found at: {$this->path}");
        }

        $content = file_get_contents($this->path);
        $config = ['base' => '/', 'root' => '.' ];

        // Match base: '/myapp/'
        if (preg_match("/base:\s*['\"]([^'\"]+)['\"]/", $content, $matches)) {
            $config['base'] = $matches[1];
        }

        // Match root: 'resources'
        if (preg_match("/root:\s*['\"]([^'\"]+)['\"]/", $content, $matches)) {
            $config['root'] = $matches[1];
        }

        // Match plugins: 'react, tailwindcss etc.'
        $plugins = function () use($content): array
        {
            if(preg_match('/plugins\s*:\s*\[(.*?)\]/s', $content, $matches)){
                $pluginBlock = $matches[0];
                preg_match_all('/\b([a-zA-Z0-9_]+)\s*\(/', $pluginBlock, $pluginMatches);
                $pluginNames = $pluginMatches[1] ?? [];
                return $pluginNames;
            }
            return [];
        };

        $server = function () use($content): array
        {
            if (!preg_match('/server\s*:\s*\{(.*?)\}/s', $content, $matches)) {
                return [];
            }
            $serverBlock = $matches[0];
            preg_match('/port\s*:\s*(\d+)/', $serverBlock, $matches);
            [1=> $port] = isset($matches[1]) ? $matches : [1 => null];

            preg_match('/origin\s*:\s*([\"\'](.*?)[\"\'])/', $serverBlock, $matches);
            [2 => $origin] = isset($matches[2]) ? $matches : [2 => ""];

            preg_match('/cors\s*:\s*(true|false)/', $serverBlock, $matches);
            $cors = isset($matches[1]) ? $matches === 'true' : false;

            preg_match('/watch\s*:\s*(null|\{\s*(.*?)\s*\})/s', $serverBlock, $matches);
            $watch = isset($matches[2]) ? ["root", $matches[2]] : ["root"];

            return ['port' => (int) $port, 'origin' => $origin, 'cors' => $cors, 'watch' => $watch];
        };

        $build = function () use($content): array
        {
            if (!preg_match('/(\s*)build\s*:\s*\{(.*)\1\}/s', $content, $matches)) {
                return [];
            }

            $buildBlock = $matches[2];
            preg_match('/outDir\s*:\s*[\"\'](.*)[\"\'],/s', $buildBlock, $matches);
            $outDir = isset($matches[1]) ? $matches[1] : "dist";

            preg_match('/manifest\s*:\s*(.*?),/', $buildBlock, $matches);
            $manifest = isset($matches[1]) ? $matches[1] : false;

            preg_match('/assetsDir\s*:\s*(\S+),/', $buildBlock, $matches);
            $assetsDir = isset($matches[1]) ? $matches[1] : "";

            return ['outDir' => $outDir, 'manifest' => $manifest];
        };

        $config['plugins'] = $plugins();
        $config['server'] = $server();
        $config['build'] = $build();

        return $config;
    }

    private function parseResolve(string $str): ?string
    {
        // Match path.resolve(__dirname, 'some/path')
        if (!preg_match("/path\.resolve\s*\(\s*__dirname\s*,\s*['\"](.+?)['\"]\s*\)/", $str, $match)) {
            return null;
        }

        $relativePath = $match[1];

        // Get the directory where vite.config.js is located
        $viteConfigDir = dirname(realpath($this->path));

        return realpath($viteConfigDir . DIRECTORY_SEPARATOR . $relativePath);
    }
}
