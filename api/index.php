<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

register_shutdown_function(function () {
    $error = error_get_last();

    if (! $error || ! in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        return;
    }

    if (! headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=UTF-8');
    }

    echo $error['message'].' in '.$error['file'].':'.$error['line'];
});

// Vercel rewrites every URL to this file. Keep Laravel's generated URLs on the
// original path instead of prefixing them with /api.
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__).'/public/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['DOCUMENT_ROOT'] = dirname(__DIR__).'/public';

$storagePath = '/tmp/storage';

foreach ([
    $storagePath.'/app/public',
    $storagePath.'/framework/cache/data',
    $storagePath.'/framework/sessions',
    $storagePath.'/framework/views',
    $storagePath.'/logs',
] as $directory) {
    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
}

$environment = [
    'LARAVEL_STORAGE_PATH' => $storagePath,
    'VIEW_COMPILED_PATH' => $storagePath.'/framework/views',
    'LOG_CHANNEL' => 'stderr',
    'CACHE_STORE' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'QUEUE_CONNECTION' => 'sync',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes-v7.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
];

if (! getenv('DB_CONNECTION')) {
    $database = '/tmp/database.sqlite';

    if (! is_file($database)) {
        $bundled = dirname(__DIR__).'/database/database.sqlite';

        if (is_file($bundled)) {
            copy($bundled, $database);
        } else {
            touch($database);
        }
    }

    $environment['DB_CONNECTION'] = 'sqlite';
    $environment['DB_DATABASE'] = $database;
}

foreach ($environment as $key => $value) {
    if (getenv($key) !== false && getenv($key) !== '') {
        continue;
    }

    putenv($key.'='.$value);
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

if (file_exists($maintenance = $storagePath.'/framework/maintenance.php')) {
    require $maintenance;
}

require dirname(__DIR__).'/vendor/autoload.php';

$app = require dirname(__DIR__).'/bootstrap/app.php';
$app->useStoragePath($storagePath);
$app->handleRequest(Request::capture());
