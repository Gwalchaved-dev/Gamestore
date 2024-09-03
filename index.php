<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;


require_once __DIR__.'/vendor/autoload.php';

// Assign environment variables manuallyphp bin/console cache:clear
$_SERVER['APP_ENV'] = getenv('APP_ENV') ?: 'prod';
$_SERVER['APP_DEBUG'] = getenv('APP_DEBUG') ?: '0';

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);