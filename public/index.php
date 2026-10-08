<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// `php artisan serve` passes APP_ENV=local, which makes Laravel load .env.local
// instead of .env. The Vercel CLI file only contains a token, so keep .env.
$localEnvFile = __DIR__.'/../.env.local';
$envFile = __DIR__.'/../.env';
if (is_file($envFile) && is_file($localEnvFile)) {
    $localEnv = (string) file_get_contents($localEnvFile);
    if (! preg_match('/^APP_KEY=\S+/m', $localEnv)) {
        putenv('APP_ENV');
        unset($_ENV['APP_ENV'], $_SERVER['APP_ENV']);
    }
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
