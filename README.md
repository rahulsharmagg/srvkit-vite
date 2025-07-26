# SrvKit Vite Plugin

[![PHPUnit](https://github.com/rahulsharmagg/srvkit-vite/actions/workflows/phpunit.yml/badge.svg)](https://github.com/rahulsharmagg/srvkit-vite/actions/workflows/phpunit.yml)

[![Latest Version](https://img.shields.io/packagist/v/srvkit/vite?style=flat-square)](https://packagist.org/packages/srvkit/vite)
[![License](https://img.shields.io/github/license/rahulsharmagg/srvkit-vite?style=flat-square)](LICENSE)
[![CI4 Compatible](https://img.shields.io/badge/codeigniter-4.x-orange.svg?style=flat-square)](https://codeigniter.com)

Integrate [Vite](https://vitejs.dev/) with your php app for modern frontend tooling with automatic manifest parsing, dev server detection, and optional debug toolbar collector.

---

## 🚀 Features

- ✅ Detect if Vite dev server is running
- ✅ Auto-parse Vite `manifest.json`
- ✅ Optional debug toolbar integration
- ✅ CodeIgniter 4 config auto-discovery support
- ✅ Compatible with PSR-4 and Composer

---

## 📦 Installation

Install via Composer in your CI4 project:

```bash
composer require srvkit/vite
```

For local development (monorepo):

```json
"repositories": [
    {
        "type": "path",
        "url": "../srvkit-vite",
        "options": {
            "symlink": true
        }
    }
],
"minimum-stability": "dev",
"prefer-stable": true
```

Then:

```bash
composer require srvkit/vite:* --prefer-source
```

&copy; 2025 - SrvKit