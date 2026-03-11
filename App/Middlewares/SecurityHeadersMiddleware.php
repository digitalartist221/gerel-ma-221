<?php
namespace App\Middlewares;

use Core\Middleware;

class SecurityHeadersMiddleware implements Middleware {
    public function handle(): bool {
        // En-têtes pour prévenir le Clickjacking
        header("X-Frame-Options: SAMEORIGIN");
        
        // Active le filtre XSS natif du navigateur
        header("X-XSS-Protection: 1; mode=block");
        
        // Empêche le MIME-sniffing
        header("X-Content-Type-Options: nosniff");
        
        // HSTS (Strict-Transport-Security) si HTTPS détecté
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
        }
        
        // Politique de sécurité du contenu de base (CSP) - Peut être restreinte selon les besoins
        // Autorise les styles/scripts inline pour Tailwind et MadelineJS, ainsi que cdn.tailwindcss.com
        header("Content-Security-Policy: default-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://unpkg.com; img-src 'self' data: https:;");
        
        return true;
    }
}
