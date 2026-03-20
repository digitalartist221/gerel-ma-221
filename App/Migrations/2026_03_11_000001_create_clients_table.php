<?php
namespace App\Migrations;

class CreateClientsTable {
    public function up(\PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS clients (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NULL,
            telephone VARCHAR(50) NULL,
            adresse TEXT NULL,
            ville VARCHAR(100) NULL,
            entreprise_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    }
}
