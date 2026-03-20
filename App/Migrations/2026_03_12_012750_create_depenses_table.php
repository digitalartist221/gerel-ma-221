<?php
namespace App\Migrations;

class CreateDepensesTable {
    public function up(\PDO $db) {
        $db->exec("CREATE TABLE IF NOT EXISTS depenses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titre VARCHAR(255) NOT NULL,
            montant DECIMAL(15,2) NOT NULL,
            categorie VARCHAR(100) DEFAULT 'autre',
            date_depense DATE NOT NULL,
            entreprise_id INT NOT NULL,
            user_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }
}