<?php
namespace App\Migrations;

/**
 * Migration: Conversion de contenu_json en TEXT pour supporter les documents
 * avec de nombreuses lignes d'articles. VARCHAR(255) était trop court.
 * Également ajoute les colonnes manquantes sur les tables existantes.
 */
class FixContenusJsonAndSchema {
    public function up(\PDO $db) {
        // Fix contenu_json sur documents (était VARCHAR(255), too short)
        $db->exec("ALTER TABLE `documents` MODIFY COLUMN `contenu_json` LONGTEXT NULL");
        
        // Fix contenu_json sur contrats
        try {
            $db->exec("ALTER TABLE `contrats` MODIFY COLUMN `contenu_json` LONGTEXT NULL");
        } catch (\Exception $e) {
            // La colonne n'existe peut-être pas encore, on la rajoute
            $db->exec("ALTER TABLE `contrats` ADD COLUMN IF NOT EXISTS `contenu_json` LONGTEXT NULL");
        }
        
        // S'assurer que tous les champs nécessaires existent sur contrats
        $existing = [];
        $stmt = $db->query("DESCRIBE `contrats`");
        foreach ($stmt->fetchAll(\PDO::FETCH_ASSOC) as $col) {
            $existing[] = $col['Field'];
        }
        
        $toAdd = [
            'is_read'   => "INT NOT NULL DEFAULT 0",
            'read_at'   => "VARCHAR(255) NULL",
            'sent_at'   => "VARCHAR(255) NULL",
            'notes'     => "TEXT NULL",
        ];
        
        foreach ($toAdd as $col => $type) {
            if (!in_array($col, $existing)) {
                $db->exec("ALTER TABLE `contrats` ADD COLUMN `{$col}` {$type}");
            }
        }
    }
}
