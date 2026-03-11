<?php
namespace App\Controllers;

class DevController {
    
    /**
     * Endpoint Server-Sent Events pour le Live Reload (Utilisable uniquement en Local)
     */
    public function livereload() {
        if (\Core\Config::get('env') !== 'local' || !\Core\Config::get('debug')) {
            http_response_code(403);
            die("LiveReload is disabled in production.");
        }

        header('Content-Type: text/event-stream');
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');

        $baseDir = realpath(__DIR__ . '/../../');
        
        // Initial setup
        $lastHash = $this->getDirHash($baseDir);
        
        // Timeout technique (le navigateur reconnectera de toute façon après)
        $endTime = time() + 30; // 30 sec par requête au max pour garder PHP libre

        while (time() < $endTime) {
            clearstatcache();
            $currentHash = $this->getDirHash($baseDir);

            if ($lastHash !== $currentHash) {
                // Fichier modifié détecté !
                echo "data: reload\n\n";
                ob_flush();
                flush();
                // Assure de ne pas bombarder le client
                break;
            }

            // Ping pour garder la connexion vivante
            echo ": ping\n\n";
            ob_flush();
            flush();
            
            // Wait 1 sec
            sleep(1);
        }
    }

    /**
     * Calcule un hash en fonction de la date de modification (mtime)
     * des dossiers critiques (App, Core, Packages, config)
     */
    private function getDirHash($baseDir) {
        $dirsToWatch = ['/App', '/Core', '/Packages', '/config', '/routes.php'];
        $maxMtime = 0;

        foreach ($dirsToWatch as $path) {
            $fullPath = $baseDir . $path;
            if (is_dir($fullPath)) {
                $dirIter = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($fullPath, \FilesystemIterator::SKIP_DOTS));
                foreach ($dirIter as $file) {
                    if ($file->isFile() && $file->getMTime() > $maxMtime) {
                        $maxMtime = $file->getMTime();
                    }
                }
            } elseif (is_file($fullPath)) {
                $mtime = filemtime($fullPath);
                if ($mtime > $maxMtime) {
                    $maxMtime = $mtime;
                }
            }
        }
        
        return $maxMtime;
    }
}
