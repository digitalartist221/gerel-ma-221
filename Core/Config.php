<?php
namespace Core;

abstract class Config {
    private static $items = [];

    public static function load() {
        // 1. Priorité Principale : La nouvelle classe AppConfig orientée objet
        if (class_exists('\\App\\Config\\AppConfig')) {
            self::$items = \App\Config\AppConfig::get();
        } 
        // 2. Rétrocompatibilité 1 : Ancien fichier array dans config/
        else {
            $configFile = __DIR__ . '/../config/app.php';
            if (file_exists($configFile)) {
                self::$items = require $configFile;
            }
        }
        
        // Compatibilité avec l'ancien config.php à la racine (généré par l'ancien setup)
        $legacyConfig = __DIR__ . '/../config.php';
        if (file_exists($legacyConfig)) {
            $legacyDB = require $legacyConfig;
            self::$items['database']['host'] = $legacyDB['db_host'] ?? self::$items['database']['host'];
            self::$items['database']['name'] = $legacyDB['db_name'] ?? self::$items['database']['name'];
            self::$items['database']['user'] = $legacyDB['db_user'] ?? self::$items['database']['user'];
            self::$items['database']['pass'] = $legacyDB['db_pass'] ?? self::$items['database']['pass'];
        }
    }

    public static function get($key, $default = null) {
        if (empty(self::$items)) {
            self::load();
        }

        $keys = explode('.', $key);
        $array = self::$items;

        foreach ($keys as $k) {
            if (isset($array[$k])) {
                $array = $array[$k];
            } else {
                return $default;
            }
        }
        return $array;
    }
    
    public static function set($key, $value) {
        if (empty(self::$items)) {
            self::load();
        }
        // Support des clés imbriquées type 'database.name'
        $keys = explode('.', $key);
        $array = &self::$items;
        foreach ($keys as $k) {
            if (!isset($array[$k]) || !is_array($array[$k])) {
                $array[$k] = [];
            }
            $array = &$array[$k];
        }
        $array = $value;
    }
}
