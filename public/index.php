<?php
/**
 * Madeline Framework - Point d'entrée principal
 */

if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if ($path && is_file($path)) {
        return false;
    }
}

require_once __DIR__ . '/../Core/App.php';
require_once __DIR__ . '/../Core/Router.php';

// Auto-chargement (Composer a la priorité s'il est installé)
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
} else {
    // Fallback Auto-chargement basique des classes (PSR-4 simplifié)
    spl_autoload_register(function ($class) {
        $classFile = str_replace('\\', '/', $class) . '.php';
        $baseDir = __DIR__ . '/../';
        
        if (file_exists($baseDir . $classFile)) {
            require_once $baseDir . $classFile;
        }
    });
}

use Core\App;
use Core\ExceptionHandler;

// Activation du gestionnaire d'erreurs avancé
ExceptionHandler::register();

// Initialisation de l'application
$app = new App();
$app->run();
