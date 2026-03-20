<?php
namespace App\Migrations;

class AddRoleToUsers {
    public function up(\PDO $db) {
        $db->exec("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'admin'");
        $db->exec("ALTER TABLE users ADD COLUMN parent_id INT DEFAULT NULL");
        $db->exec("ALTER TABLE users ADD COLUMN entreprise_id INT DEFAULT NULL");
    }
}