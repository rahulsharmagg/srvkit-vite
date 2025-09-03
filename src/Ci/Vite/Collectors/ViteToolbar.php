<?php

declare(strict_types=1);

namespace SrvKit\Ci\Vite\Collectors;

use SrvKit\Vite\Vite;
use CodeIgniter\Debug\Toolbar\Collectors\BaseCollector;

/**
 * Debug Toolbar Collector for Auth
 */
class ViteToolbar extends BaseCollector
{
    /**
     * Whether this collector has data that can
     * be displayed in the Timeline.
     *
     * @var bool
     */
    protected $hasTimeline = false;

    /**
     * Whether this collector needs to display
     * content in a tab or not.
     *
     * @var bool
     */
    protected $hasTabContent = true;

    /**
     * Whether this collector has data that
     * should be shown in the Vars tab.
     *
     * @var bool
     */
    protected $hasVarData = false;

    /**
     * The 'title' of this Collector.
     * Used to name things in the toolbar HTML.
     *
     * @var string
     */
    protected $title = 'Vite';


    /**
     * Vite status
     * 
     * @var ?bool
     */
    protected $viteStatus = null;

    protected array $info;

    public function __construct()
    {
        /** @var Vite $vite */
        $vite = service('vite', false);

        /** @var \CodeIgniter\Cache\CacheInterface|null $cache */
        $cache = service('cache');

        if (!$vite) {
           log_message('error', 'Vite service is not available.');
           $this->viteStatus = null;
           $this->info = [];
           return;
        }

        if ($cache) {
            $cachedInfo = $cache->get('__vite__');
            if($cachedInfo){
                 $this->viteStatus = $cachedInfo['status'];
                 $this->info = $cachedInfo['info'];
                 return;
            }

            $this->viteStatus = $vite->isRunning();
            $this->info = $vite->sharedInfo();

            $cache->save('__vite__', [
                'status' => $this->viteStatus,
                'info'   => $this->info
            ], 60); // cache for 60 seconds
        } else {
            log_message('warning', 'Cache service not available to store Vite status.');
            $this->viteStatus = $vite->isRunning();
            $this->info = $vite->sharedInfo();
        }
    }

    /**
     * Returns any information that should be shown next to the title.
     */
    public function getTitleDetails(): string
    {
        $status = match (true) {
            $this->viteStatus === null => ['UNINITISE', '#baff20'],
            $this->viteStatus === true => ['ACTIVE', '#baff20'],
            default                   => ['INACTIVE', '#747576'],
        };

        [$label, $color] = $status;
        $version = sprintf('<span style="display: inline-block;margin-right: 10px;font-weight: normal;color:#747576;font-family: monospace;">[v%1$s]</span>', Vite::VERSION);
        $status = sprintf(
            '<span style="--color: %1$s; font-size:x-small !important; display:inline-block; padding:2px 6px; border-radius:25px; border:1px solid var(--color, #747576); color:var(--color, #747576); font-family:sans-serif; line-height:initial;">%2$s</span>',
            $color,
            $label
        );

        return $version.$status;
    }


    /**
     * Returns the data of this collector to be formatted in the toolbar
     */
    public function display(): string
    {
        if ($this->viteStatus) {
            $port = $this->info['port'] ?? 'unknown';
            return <<<HTML
                <div style="padding: 1rem; background: #1e1e1e; border-radius: 8px; color: #baff20; font-family: 'Courier New', monospace; font-size: 1rem;">
                    <strong style="display:block; margin-bottom: 0.5rem;">🚀 Vite Dev Server</strong>
                    <span style="display:inline-block;">✅ Running on port <code style="color: #fff;">{$port}</code></span>
                </div>
            HTML;
        }

        return <<<HTML
            <div style="padding: 1rem; background: #2a2a2a; border-radius: 8px; color: #ccc; font-family: 'Courier New', monospace; font-size: 1rem;">
                <strong style="display:block; margin-bottom: 0.5rem;">🛑 Vite Dev Server</strong>
                <span>❌ Not Running</span>
            </div>
        HTML;
    }


    /**
     * Gets the "badge" value for the button.
     *
     * @return int|string|null
     */
    public function getBadgeValue()
    {
        return null;
    }

    /**
     * Display the icon.
     *
     * Icon from https://icons8.com - 1em package
     */
    public function icon(): string
    {
        return 'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxNiIgaGVpZ2h0PSIxNiIgdmlld0JveD0iMCAwIDQxMCA0MDQiIGZpbGw9Im5vbmUiPjxwYXRoIGQ9Ik0zOTkuNjQxIDU5LjUyNDZMMjE1LjY0MyAzODguNTQ1QzIxMS44NDQgMzk1LjMzOCAyMDIuMDg0IDM5NS4zNzggMTk4LjIyOCAzODguNjE4TDEwLjU4MTcgNTkuNTU2M0M2LjM4MDg3IDUyLjE4OTYgMTIuNjgwMiA0My4yNjY1IDIxLjAyODEgNDQuNzU4NkwyMDUuMjIzIDc3LjY4MjRDMjA2LjM5OCA3Ny44OTI0IDIwNy42MDEgNzcuODkwNCAyMDguNzc2IDc3LjY3NjNMMzg5LjExOSA0NC44MDU4QzM5Ny40MzkgNDMuMjg5NCA0MDMuNzY4IDUyLjE0MzQgMzk5LjY0MSA1OS41MjQ2WiIgZmlsbD0ibm9uZSIgc3Ryb2tlPSIjMDAwMDAwIiBzdHJva2Utd2lkdGg9IjIwIi8+PHBhdGggZD0iTTI5Mi45NjUgMS41NzQ0TDE1Ni44MDEgMjguMjU1MkMxNTQuNTYzIDI4LjY5MzcgMTUyLjkwNiAzMC41OTAzIDE1Mi43NzEgMzIuODY2NEwxNDQuMzk1IDE3NC4zM0MxNDQuMTk4IDE3Ny42NjIgMTQ3LjI1OCAxODAuMjQ4IDE1MC41MSAxNzkuNDk4TDE4OC40MiAxNzAuNzQ5QzE5MS45NjcgMTY5LjkzMSAxOTUuMTcyIDE3My4wNTUgMTk0LjQ0MyAxNzYuNjIyTDE4My4xOCAyMzEuNzc1QzE4Mi40MjIgMjM1LjQ4NyAxODUuOTA3IDIzOC42NjEgMTg5LjUzMiAyMzcuNTZMMjEyLjk0NyAyMzAuNDQ2QzIxNi41NzcgMjI5LjM0NCAyMjAuMDY1IDIzMi41MjcgMjE5LjI5NyAyMzYuMjQyTDIwMS4zOTggMzIyLjg3NUMyMDAuMjc4IDMyOC4yOTQgMjA3LjQ4NiAzMzEuMjQ5IDIxMC40OTIgMzI2LjYwM0wyMTIuNSAzMjMuNUwzMjMuNDU0IDEwMi4wNzJDMzI1LjMxMiA5OC4zNjQ1IDMyMi4xMDggOTQuMTM3IDMxOC4wMzYgOTQuOTIyOEwyNzkuMDE0IDEwMi40NTRDMjc1LjM0NyAxMDMuMTYxIDI3Mi4yMjcgOTkuNzQ2IDI3My4yNjIgOTYuMTU4M0wyOTguNzMxIDcuODY2ODlDMjk5Ljc2NyA0LjI3MzE0IDI5Ni42MzYgMC44NTUxODEgMjkyLjk2NSAxLjU3NDRaIiBmaWxsPSIjMDAwMDAwIi8+PC9zdmc+';
    }
}