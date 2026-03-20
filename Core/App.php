<?php
namespace Core;

class App {
    public function run() {
        // Charger la configuration globale du framework
        Config::load();

        // DETECTION DU CHEMIN DE BASE (Zero-Config Subdirectory support)
        $scriptName = $_SERVER['SCRIPT_NAME'];
        // Si le script est public/index.php, le baseDir est ce qui précède /public
        if (str_contains($scriptName, '/public/index.php')) {
            $baseDir = str_replace('/public/index.php', '', $scriptName);
        } else {
            // Si on pointe directement sur public/, le scriptName est /index.php 
            // et le baseDir est vide (on est à la racine)
            $baseDir = str_replace('/index.php', '', $scriptName);
        }
        Config::set('app.base_path', rtrim($baseDir, '/'));

        // Obtenir l'URL (compatible Apache, LWS, et PHP built-in server)
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $urlPath = parse_url($requestUri, PHP_URL_PATH);
        
        // Priorité absolue : le paramètre 'url' passé par .htaccess (mode redirection)
        // Cela règle 100% des problèmes si le .htaccess est présent
        if (isset($_GET['url']) && !empty($_GET['url'])) {
            $url = '/' . ltrim($_GET['url'], '/');
        } else {
            // Sinon (mode serveur intégré PHP ou redirection transparente), 
            // on nettoie le base_path de l'URL pour le routage interne
            $url = $urlPath;
            if (!empty($baseDir) && $baseDir !== '/' && strpos($url, $baseDir) === 0) {
                $url = substr($url, strlen($baseDir));
            }
        }

        // Nettoyage final : on vire les slashs en trop et le trailing slash
        $url = '/' . trim($url, '/');
        
        // Cache auto-cleaning (Périodique)
        if (class_exists('\\Core\\Cache')) {
            \Core\Cache::autoClear();
        }

        // Configuration de base ou DB
        $isConfigured = self::isConfigured();
        
        // Gestion du bypass (Ignorer le setup)
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_GET['skip_setup'])) {
            $_SESSION['m_skip_setup'] = true;
        }
        $isSkipped = $_SESSION['m_skip_setup'] ?? false;

        $isSetupUrl = (trim($url, '/') === 'setup');

        if (!$isConfigured && !$isSetupUrl && !$isSkipped) {
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

    public static function isConfigured() {
        // Le framework est considéré comme configuré si l'un de ces fichiers existe
        return class_exists('\\App\\Config\\AppConfig') || 
               file_exists(__DIR__ . '/../App/Config/AppConfig.php') ||
               file_exists(__DIR__ . '/../config.php');
    }
}
