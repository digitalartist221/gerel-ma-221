<?php
namespace Packages\ORM;

class Migrator {
    private \PDO $db;
    
    public function __construct(\PDO $db) {
        $this->db = $db;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable() {
        $this->db->exec("CREATE TABLE IF NOT EXISTS `migrations` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `migration` VARCHAR(255) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
    }

    public function migrate() {
        // En CLI on echo les retours
        $cliOuput = (php_sapi_name() === 'cli');
        
        if($cliOuput) echo "🔍 Démarrage des migrations Ligeey...\n";
        
        $files = glob(__DIR__ . '/../../App/Migrations/*.php');
        if (empty($files)) {
            if($cliOuput) echo "✅ Aucune migration trouvée dans App/Migrations.\n";
            return;
        }
        
        $stmt = $this->db->query("SELECT migration FROM migrations");
        $rana = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        $migrated = 0;
        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!in_array($name, $rana)) {
                require_once $file;
                
                // Parser le nom (ex: 2024_03_10_HHMMSS_create_users_table => CreateUsersTable)
                $classPart = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $name);
                $className = str_replace(' ', '', ucwords(str_replace('_', ' ', $classPart)));
                $className = 'App\\Migrations\\' . $className;
                
                if (class_exists($className)) {
                    $migration = new $className();
                    $migration->up($this->db);
                    
                    $stmt = $this->db->prepare("INSERT INTO migrations (migration) VALUES (?)");
                    $stmt->execute([$name]);
                    if($cliOuput) echo "✅ Migré: $name\n";
                    $migrated++;
                } else {
                    if($cliOuput) echo "❌ Classe $className non trouvée dans $name\n";
                }
            }
        }
        if($cliOuput) {
            if ($migrated == 0) echo "✅ Rien à migrer.\n";
            else echo "✓ Migrations terminées ($migrated).\n";
        }
    }
}
