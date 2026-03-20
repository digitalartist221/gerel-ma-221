<?php
namespace App\Middlewares;

/**
 * Middleware: Protège l'accès aux routes privées
 */
class AuthMiddleware {
    public function handle(string $role = null): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        // Vérification du rôle si spécifié
        if ($role && ($_SESSION['user_role'] ?? 'member') !== $role && ($_SESSION['user_role'] ?? '') !== 'admin') {
            http_response_code(403);
            echo "Accès interdit : Rôle {$role} requis.";
            exit;
        }

        return true;
    }
}