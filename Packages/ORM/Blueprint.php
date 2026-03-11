<?php
namespace Packages\ORM;

class Blueprint {
    private \PDO $db;

    public function __construct(\PDO $db) {
        $this->db = $db;
    }

    public function autoMigrate(string $table, array $properties) {
        // Validation stricte du nom de table avant tout usage SQL
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $table)) {
            throw new \Exception("Nom de table invalide : " . htmlspecialchars($table));
        }
        
        try {
            // Requête préparée pour SHOW TABLES (sécurité)
            $stmt = $this->db->prepare("SHOW TABLES LIKE ?");
            $stmt->execute([$table]);
            
            if ($stmt->rowCount() == 0) {
                $this->createTable($table, $properties);
                return;
            }

            // Si la table existe, on compare avec DESCRIBE
            $stmt = $this->db->query("DESCRIBE `{$table}`");
            $columns = $stmt->fetchAll(\PDO::FETCH_COLUMN);

            foreach ($properties as $prop => $type) {
                // Valider le nom de colonne
                if (!preg_match('/^[a-zA-Z0-9_]+$/', $prop)) continue;
                if (!in_array($prop, $columns)) {
                    $sqlType = $this->mapType($type);
                    $this->db->exec("ALTER TABLE `{$table}` ADD COLUMN `{$prop}` $sqlType");
                }
            }
        } catch (\PDOException $e) {
            // Echec silencieux de la migration (log si disponible)
            error_log('[Madeline ORM] AutoMigrate failed for table `' . $table . '`: ' . $e->getMessage());
        }
    }

    private function createTable($table, $properties) {
        $sql = "CREATE TABLE `$table` (\n";
        $columns = [];
        
        // Garanti que 'id' soit toujours créé même si non défini dans la classe (bien que recommandé)
        if (!array_key_exists('id', $properties)) {
            $columns[] = "`id` INT AUTO_INCREMENT PRIMARY KEY";
        }
        
        foreach ($properties as $prop => $type) {
            $sqlType = $this->mapType($type);
            if ($prop === 'id') {
                $columns[] = "`$prop` $sqlType AUTO_INCREMENT PRIMARY KEY";
            } else {
                $columns[] = "`$prop` $sqlType";
            }
        }
        $sql .= implode(",\n", $columns);
        $sql .= "\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
        $this->db->exec($sql);
    }

    private function mapType($phpType) {
        switch ($phpType) {
            case 'int': return 'INT';
            case 'float': return 'FLOAT';
            case 'bool': return 'TINYINT(1)';
            case 'DateTime': return 'DATETIME';
            case 'array': return 'JSON';
            default: return 'VARCHAR(255)';
        }
    }
}
