<?php
namespace Packages\ORM;

abstract class MadelineORM {
    protected static \PDO $db;
    protected string $table;
    protected array $properties = [];
    protected array $joins = [];
    
    // Pour des questions de perfs, on peut le stocker dans un cache au lieu de statique local
    protected static array $migratedTables = [];

    public function __construct() {
        self::initDB();
        $this->table = $this->resolveTableName();
        $this->discoverProperties();

        // Auto-Migration Feature "Zéro-Migration"
        if (!in_array($this->table, self::$migratedTables)) {
            $blueprint = new Blueprint(self::$db);
            $blueprint->autoMigrate($this->table, $this->properties);
            self::$migratedTables[] = $this->table;
        }
    }

    protected static function initDB() {
        if (!isset(self::$db)) {
            // 1. Lecture via Core\Config (qui priorise AppConfig)
            if (class_exists('\\Core\\Config')) {
                $dbConfig = \Core\Config::get('database');
                if (!empty($dbConfig['name'])) {
                    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']};charset={$dbConfig['charset']}";
                    self::$db = new \PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
                        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
                    ]);
                    return;
                }
            }
            // 2. Rétrocompatibilité avec le config.php généré par /setup
            $configFile = __DIR__ . '/../../config.php';
            if (!file_exists($configFile)) {
                throw new \Exception("Aucune configuration de base de donnés. Configurez AppConfig ou lancez /setup.");
            }
            $config = require $configFile;
            $dsn = "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8mb4";
            self::$db = new \PDO($dsn, $config['db_user'], $config['db_pass'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
        }
    }

    public static function getConnection() {
        self::initDB();
        return self::$db;
    }

    private function resolveTableName() {
        if (isset($this->table) && !empty($this->table)) return $this->table;
        
        $class = new \ReflectionClass($this);
        $name = $class->getShortName();
        // Convertit CamelCase en snake_case_pluriel (User -> users)
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name)) . 's';
    }

    private function discoverProperties() {
        $class = new \ReflectionClass($this);
        $props = $class->getProperties(\ReflectionProperty::IS_PUBLIC); // Seules les props public
        foreach ($props as $prop) {
            $type = 'string';
            $reflectionType = $prop->getType();
            if ($reflectionType instanceof \ReflectionNamedType) {
                $type = $reflectionType->getName();
                if ($reflectionType->allowsNull()) {
                    $type = '?' . $type;
                }
            }
            $this->properties[$prop->getName()] = $type;
        }
    }

    private function sanitizeName(string $name): string {
        // N'autorise que les caractères alphanumériques et underscores pour prévenir toute injection SQL au niveau de la structure
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
            throw new \Exception("Invalid table/column name detected: " . htmlspecialchars($name));
        }
        return $name;
    }

    // --- ACCÈS MAGIQUE (STATIQUE & INSTANCE) --- //

    public function __call($method, $args) {
        $internal = "_" . $method;
        if (method_exists($this, $internal)) {
            return $this->$internal(...$args);
        }
        throw new \Exception("Méthode [{$method}] non trouvée dans le modèle " . get_called_class());
    }

    public static function __callStatic($method, $args) {
        $instance = new static();
        $internal = "_" . $method;
        if (method_exists($instance, $internal)) {
            return $instance->$internal(...$args);
        }
        throw new \Exception("Méthode Statique [{$method}] non trouvée dans le modèle " . get_called_class());
    }

    // --- SYNTAXE WOLOF DÉDIÉE (LOGIQUE INTERNE) --- //

    /**
     * _fari : Récupérer/Chercher. (SELECT)
     * @param array $conditions (ex: ['id' => 1])
     */
    protected function _fari($conditions = [], $orderBy = null) {
        $safeTable = $this->sanitizeName($this->table);
        $sql = "SELECT `{$safeTable}`.*";
        
        // Selects joints si existants
        if(!empty($this->joins)) {
            foreach($this->joins as $join) {
                $safeJoin = $this->sanitizeName($join['table']);
                $sql .= ", `{$safeJoin}`.*";
            }
        }
        
        $sql .= " FROM `{$safeTable}`";
        
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $safeJoin = $this->sanitizeName($join['table']);
                $sql .= " LEFT JOIN `{$safeJoin}` ON {$join['on']}";
            }
        }
 
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $key => $val) {
                $safeKey = $this->sanitizeName($key);
                $clauses[] = "`{$safeTable}`.`$safeKey` = :$safeKey";
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }

        if ($orderBy) {
             // Basic validation for orderBy to prevent raw injection
             // We allow only letters, numbers, spaces, dots, and common keywords like DESC/ASC
             if (preg_match('/^[a-zA-Z0-9_\. ,]+$/', $orderBy)) {
                 $sql .= " ORDER BY " . $orderBy;
             }
        }
 
        $stmt = self::$db->prepare($sql);
        $stmt->execute($conditions);
        
        $this->joins = []; // clean state
        
        // Return object instances instead of array
        $rows = $stmt->fetchAll();
        $instances = [];
        $className = get_called_class();
        foreach($rows as $row) {
            $instance = new $className();
            foreach($row as $key => $value) {
                $instance->$key = $value;
            }
            $instances[] = $instance;
        }
        
        return $instances;
    }

    /**
     * _loko : Associer / Joindre. (JOIN)
     */
    protected function _loko($table, $on) {
        $this->joins[] = ['table' => $table, 'on' => $on];
        return $this;
    }

    /**
     * _bindu: Insérer / Enregistrer. (INSERT)
     */
    protected function _bindu($data = null) {
        $safeTable = $this->sanitizeName($this->table);
        
        // Si des données sont passées en argument (appel statique ou direct)
        if (is_array($data)) {
            foreach ($data as $k => $v) { $this->$k = $v; }
        }

        $payload = [];
        $colsArray = [];
        $valsArray = [];

        foreach ($this->properties as $prop => $type) {
            if ($prop !== 'id' && isset($this->$prop)) {
                $safeProp = $this->sanitizeName($prop);
                $payload[$safeProp] = $this->$prop;
                $colsArray[] = "`$safeProp`";
                $valsArray[] = ":$safeProp";
            }
        }

        $cols = implode(', ', $colsArray);
        $vals = implode(', ', $valsArray);
        
        $sql = "INSERT INTO `{$safeTable}` ($cols) VALUES ($vals)";
        $stmt = self::$db->prepare($sql);
        $stmt->execute($payload);
        
        $this->id = self::$db->lastInsertId();
        return $this;
    }

    /**
     * _weccit: Changer / Mettre à jour. (UPDATE)
     * Supporte $data + $conditions pour appel statique rapide
     */
    protected function _weccit($data = null, $conditions = null) {
        $safeTable = $this->sanitizeName($this->table);
        
        // --- CAS 1: Appel statique avec data et conditions ---
        if (is_array($data) && is_array($conditions)) {
            $set = [];
            $params = [];
            foreach ($data as $key => $val) {
                $safeKey = $this->sanitizeName($key);
                $set[] = "`$safeKey` = :set_$safeKey";
                $params["set_$safeKey"] = $val;
            }
            $where = [];
            foreach ($conditions as $key => $val) {
                $safeKey = $this->sanitizeName($key);
                $where[] = "`$safeKey` = :where_$safeKey";
                $params["where_$safeKey"] = $val;
            }
            $sql = "UPDATE `{$safeTable}` SET " . implode(', ', $set) . " WHERE " . implode(' AND ', $where);
            $stmt = self::$db->prepare($sql);
            return $stmt->execute($params);
        }

        // --- CAS 2: Appel d'instance via ID ---
        if (!isset($this->id)) throw new \Exception("Cannot update without ID or conditions");

        $payload = [];
        $set = [];
        foreach ($this->properties as $prop => $type) {
            if ($prop !== 'id' && isset($this->$prop)) {
                $safeProp = $this->sanitizeName($prop);
                $payload[$safeProp] = $this->$prop;
                $set[] = "`$safeProp` = :set_$safeProp";
            }
        }

        $sql = "UPDATE `{$safeTable}` SET " . implode(', ', $set) . " WHERE `id` = :id";
        
        $boundData = ['id' => $this->id];
        foreach($payload as $k => $v) { $boundData["set_$k"] = $v; }

        $stmt = self::$db->prepare($sql);
        return $stmt->execute($boundData);
    }

    /**
     * _far: Supprimer / Effacer. (DELETE)
     */
    protected function _far($conditions = null) {
        $safeTable = $this->sanitizeName($this->table);
        
        if (is_array($conditions)) {
            $where = [];
            foreach ($conditions as $key => $val) {
                $safeKey = $this->sanitizeName($key);
                $where[] = "`$safeKey` = :$safeKey";
            }
            $sql = "DELETE FROM `{$safeTable}` WHERE " . implode(' AND ', $where);
            $stmt = self::$db->prepare($sql);
            return $stmt->execute($conditions);
        }

        if (!isset($this->id)) throw new \Exception("Cannot delete without ID or conditions");
        $sql = "DELETE FROM `{$safeTable}` WHERE `id` = :id";
        $stmt = self::$db->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }
}
