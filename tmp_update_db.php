<?php
require 'vendor/autoload.php';

$pdo = new PDO('mysql:host=localhost;dbname=madeline_db;charset=utf8mb4', 'root', '');
try {
    $pdo->exec("ALTER TABLE contrats ADD COLUMN signature_client_hash LONGTEXT NULL");
    $pdo->exec("ALTER TABLE contrats ADD COLUMN signed_client_at DATETIME NULL");
    echo "Columns added to contrats successfully.\n";
} catch (Exception $e) {
    echo "DB alteration error (might already exist): " . $e->getMessage() . "\n";
}

try {
    $pdo->exec("ALTER TABLE articles_juridiques MODIFY COLUMN description LONGTEXT NULL");
    echo "Articles table modified.\n";
} catch (Exception $e) {
    
}
