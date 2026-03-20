<?php
namespace Core;

/**
 * Service de gestion du cache Madeline
 */
class Cache {
    /**
     * Vide intégralement le cache des vues
     */
    public static function clear() {
        $cacheDir = __DIR__ . '/../storage/cache/views';
        
        // Création récursive si le dossier n'existe pas
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
            return;
        }

        $files = glob($cacheDir . '/*.php');
        foreach ($files as $file) {
            if (is_file($file)) unlink($file);
        }
    }

    /**
     * Nettoyage automatique basé sur un intervalle de temps
     * 
     * @param int $hours Intervalle en heures (défaut: 24h)
     */
    public static function autoClear($hours = 24) {
        $cacheDir = __DIR__ . '/../storage/cache';
        $marker = $cacheDir . '/last_clear.txt';
        
        // Assurer que le dossier de base du cache existe
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $now = time();

        if (!file_exists($marker)) {
            file_put_contents($marker, $now);
            return;
        }

        $lastClear = (int)file_get_contents($marker);
        $interval = $hours * 3600;

        if (($now - $lastClear) > $interval) {
            self::clear();
            file_put_contents($marker, $now);
        }
    }
}
