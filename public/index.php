<?php

use App\Kernel;
use Symfony\Component\ErrorHandler\Debug;
use Symfony\Component\HttpFoundation\Request;

require_once dirname(__DIR__).'/vendor/autoload.php'; // Utilise dirname(__DIR__) pour pointer vers le bon dossier

// Assigner manuellement les variables d'environnement
$_SERVER['APP_ENV'] = $_SERVER['APP_ENV'] ?? 'prod'; // Utilisation de la coalescence nulle pour plus de clarté
$_SERVER['APP_DEBUG'] = (int) ($_SERVER['APP_DEBUG'] ?? 0); // Conversion en entier pour garantir une valeur correcte

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
    Debug::enable();
}

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);