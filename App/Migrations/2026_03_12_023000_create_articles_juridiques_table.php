<?php
namespace App\Migrations;

class CreateArticlesJuridiquesTable {
    public function up(\PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS articles_juridiques (
            id INT AUTO_INCREMENT PRIMARY KEY,
            numero VARCHAR(50) NOT NULL,
            nom VARCHAR(255) NOT NULL,
            description TEXT NULL,
            entreprise_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
}
