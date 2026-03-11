<?php
namespace Packages\Storage;

class Storage {
    private static function getStoragePath() {
        $path = __DIR__ . '/../../storage/app/public';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        return realpath($path);
    }

    /**
     * Uploader un fichier sécurisé via $_FILES
     * Exemple : Storage::upload($_FILES['avatar'], 'users')
     */
    public static function upload($fileArray, $directory = '') {
        if (!isset($fileArray['tmp_name']) || empty($fileArray['tmp_name'])) {
            return false;
        }

        $extension = pathinfo($fileArray['name'], PATHINFO_EXTENSION);
        $filename = uniqid('file_') . '_' . time() . '.' . $extension;
        
        $targetDir = self::getStoragePath();
        if ($directory !== '') {
            $targetDir .= '/' . trim($directory, '/');
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
        }

        $targetFile = $targetDir . '/' . $filename;
        
        if (move_uploaded_file($fileArray['tmp_name'], $targetFile)) {
            return $directory !== '' ? trim($directory, '/') . '/' . $filename : $filename;
        }
        
        return false;
    }

    /**
     * Écrire du texte brut dans un fichier
     */
    public static function put($path, $content) {
        $fullPath = self::getStoragePath() . '/' . ltrim($path, '/');
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return file_put_contents($fullPath, $content) !== false;
    }

    /**
     * Lire le contenu d'un fichier
     */
    public static function get($path) {
        $fullPath = self::getStoragePath() . '/' . ltrim($path, '/');
        if (file_exists($fullPath)) {
            return file_get_contents($fullPath);
        }
        return null;
    }

    /**
     * Supprimer un fichier
     */
    public static function delete($path) {
        $fullPath = self::getStoragePath() . '/' . ltrim($path, '/');
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * Obtenir l'URL publique du fichier (n'est valide que si storage:link a été fait)
     */
    public static function url($path) {
        return '/storage/' . ltrim($path, '/');
    }
}
