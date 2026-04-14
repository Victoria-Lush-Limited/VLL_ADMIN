<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Subfolder + XAMPP: root .htaccess may internally map to public/ while REQUEST_URI still
// uses the parent path (e.g. .../vll-admin/login). Symfony then mis-computes pathInfo → 404.
if (isset($_SERVER['SCRIPT_NAME'], $_SERVER['REQUEST_URI'])) {
    $scriptName = str_replace('\\', '/', (string) $_SERVER['SCRIPT_NAME']);
    if (str_ends_with($scriptName, '/index.php')) {
        $publicBase = rtrim(dirname($scriptName), '/');
        if ($publicBase !== '') {
            $parts = parse_url($_SERVER['REQUEST_URI']);
            $path = str_replace('\\', '/', $parts['path'] ?? '/');
            if ($path !== $publicBase && ! str_starts_with($path, $publicBase.'/')) {
                $parentBase = rtrim(dirname($publicBase), '/');
                $newPath = null;
                if ($parentBase !== '' && $parentBase !== '/' && $path === $parentBase) {
                    $newPath = $publicBase.'/';
                } elseif ($parentBase !== '' && $parentBase !== '/' && str_starts_with($path, $parentBase.'/')) {
                    $suffix = substr($path, strlen($parentBase));
                    $newPath = $publicBase.($suffix === '' ? '/' : $suffix);
                }
                if ($newPath !== null) {
                    $_SERVER['REQUEST_URI'] = $newPath.(isset($parts['query']) ? '?'.$parts['query'] : '');
                }
            }
        }
    }
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
