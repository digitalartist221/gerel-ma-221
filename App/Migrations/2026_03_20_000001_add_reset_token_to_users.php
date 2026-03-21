<?php
namespace App\Migrations;

/**
 * Migration: Ajout des champs reset_token et reset_token_expires_at sur la table users.
 * Nécessaire pour la fonctionnalité "Mot de passe oublié".
 */
class AddResetTokenToUsers {
    public function up(\PDO $db) {
        // Récupérer les colonnes existantes
        $existing = [];
        $stmt = $db->query("DESCRIBE `users`");
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $col) {
            $existing[] = $col['Field'];
        }

        $toAdd = [
            'reset_token'            => "VARCHAR(255) NULL DEFAULT NULL",
            'reset_token_expires_at' => "DATETIME NULL DEFAULT NULL",
        ];

        foreach ($toAdd as $col => $type) {
            if (!in_array($col, $existing)) {
                $db->exec("ALTER TABLE `users` ADD COLUMN `{$col}` {$type}");
            }
        }
    }
}
