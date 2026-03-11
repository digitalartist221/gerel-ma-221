<?php
namespace App\Middlewares;

use Core\Middleware;

class CsrfMiddleware implements Middleware {
    public function handle(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Génère le token si absent
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Vérification du token sur les requêtes modifiant l'état
        if (in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
            
            if (!hash_equals($_SESSION['csrf_token'], $token)) {
                http_response_code(419);
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    echo json_encode(['error' => 'CSRF Token invalide']);
                } else {
                    die("Erreur 419 : Jeton de sécurité (CSRF) invalide ou expiré.");
                }
                return false;
            }
        }

        return true;
    }
    
    public static function getToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}
