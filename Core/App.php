<?php
namespace Core;

class App {
    public function run() {
        // Obtenir l'URL (compatible Apache et PHP built-in server)
        $url = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        
        // Charger la configuration globale du framework
        Config::load();

        // Support du mode .htaccess
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = '/' . ltrim($_GET['url'], '/');
        }

        $url = rtrim($url, '/');
        if (empty($url)) {
            $url = '/';
        }

        // Configuration de base ou DB
        $isConfigured = $this->isConfigured();
        
        $isSetupUrl = (trim($url, '/') === 'setup');

        if (!$isConfigured && !$isSetupUrl) {
            // Pas de config du tout -> Assistant d'installation
            header('Location: /setup');
            exit;
        }

        // Charger les routes définies par l'utilisateur (si le fichier existe)
        $routesFile = __DIR__ . '/../routes.php';
        if (file_exists($routesFile)) {
            require_once $routesFile;
        }

        // Lancement du Routeur
        $response = Router::dispatch($url);
        
        // Si le routeur renvoie une chaîne (comme une vue compilée), on l'affiche
        if (is_string($response)) {
            echo $response;
        }
    }

    private function isConfigured() {
        // Le framework est considéré comme configuré si l'un de ces fichiers existe
        return file_exists(__DIR__ . '/../config/app.php') || 
               file_exists(__DIR__ . '/../config.php');
    }
}
