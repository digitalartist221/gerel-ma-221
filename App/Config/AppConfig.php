<?php
namespace App\Config;

/**
 * Configuration Principale de l'Application Gerel Ma
 *
 * Toutes les variables d'environnement, accès base de données,
 * et réglages globaux se trouvent ici.
 */
class AppConfig {
    
    public static function get() {
        return [
            /*
            |--------------------------------------------------------------------------
            | Nom de l'application
            |--------------------------------------------------------------------------
            */
            'name' => 'Gerel Ma',

            /*
            |--------------------------------------------------------------------------
            | Environnement et Debug
            |--------------------------------------------------------------------------
            | Valeurs: 'local', 'production'
            */
            'env' => 'production',
            'debug' => false,

            /*
            |--------------------------------------------------------------------------
            | URL de base
            |--------------------------------------------------------------------------
            */
            'url' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost'),

            /*
            |--------------------------------------------------------------------------
            | Gestion du Cache des Vues (Automatique Intelligente)
            |--------------------------------------------------------------------------
            | view_cache_lifetime: Durée en secondes avant vérification/recompilation
            | - En mode 'local', le cache est toujours invalidé (recompilé en direct)
            | - Si = 0, il ne se recompile jamais sauf si le fichier source a changé
            | Exemple: 3600 = vérifie/recompile le cache source au bout d'une heure max
            */
            'view_cache_lifetime' => 3600,

            /*
            |--------------------------------------------------------------------------
            | Configuration de la Base de Données
            |--------------------------------------------------------------------------
            | Laissez db_name vide pour déclencher le SetupController interactif.
            */
            'database' => [
                'host' => 'localhost',
                'name' => 'madeline_db', // Remplaced by setup if empty
                'user' => 'root',
                'pass' => '',
                'charset' => 'utf8mb4'
            ],

            /*
            |--------------------------------------------------------------------------
            | Middlewares Globaux
            |--------------------------------------------------------------------------
            | Ces middlewares seront exécutés à CHAQUE requête.
            */
            'middlewares' => [
                \App\Middlewares\SecurityHeadersMiddleware::class,
                \App\Middlewares\CsrfMiddleware::class,
            ],

            /*
            |--------------------------------------------------------------------------
            | Configuration Mail (SMTP)
            |--------------------------------------------------------------------------
            | Configurez votre serveur SMTP ici pour l'envoi d'e-mails en production.
            | Laissez host vide pour utiliser mail() natif (hébergement mutualisé).
            */
            'mail' => [
                'from_email' => 'noreply@gerelma.com',
                'from_name'  => 'Gerel Ma Business',
                'host'       => '',   // ex: smtp.gmail.com ou smtp.mailgun.org
                'port'       => 587,
                'username'   => '',
                'password'   => '',
                'encryption' => 'tls',
            ]
        ];
    }
}
