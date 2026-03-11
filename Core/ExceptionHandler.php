<?php
namespace Core;

class ExceptionHandler {
    public static function register() {
        // En mode local, on affiche les erreurs détaillées
        $env = class_exists('\Core\Config') ? \Core\Config::get('env', 'production') : 'production';
        
        if ($env === 'local') {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(E_ALL);
        }

        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError($level, $message, $file = '', $line = 0) {
        if (error_reporting() & $level) {
            throw new \ErrorException($message, 0, $level, $file, $line);
        }
    }

    public static function handleException(\Throwable $exception) {
        $env = class_exists('\Core\Config') ? \Core\Config::get('env', 'production') : 'production';
        
        http_response_code(500);

        if ($env !== 'local') {
            try {
                echo \Packages\View\MadelineView::render('errors/500');
            } catch (\Throwable $e) {
                echo "<h1>500 - Erreur Interne du Serveur</h1>";
                echo "<p>Une erreur inattendue s'est produite.</p>";
            }
            exit;
        }

        self::renderPrettyError($exception);
        exit;
    }

    public static function handleShutdown() {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            self::handleException(new \ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']));
        }
    }

    private static function renderPrettyError(\Throwable $exception) {
        $class = get_class($exception);
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = $exception->getTraceAsString();

        // Récupérer un extrait du code (quelques lignes autour de l'erreur)
        $codeSnippet = '';
        if (file_exists($file)) {
            $lines = file($file);
            $start = max(0, $line - 6);
            $end = min(count($lines), $line + 5);
            for ($i = $start; $i < $end; $i++) {
                $num = $i + 1;
                $active = ($num === $line) ? 'bg-red-500/20 border-l-4 border-red-500' : 'opacity-70';
                $codeSnippet .= "<div class='flex $active px-4 py-1'><span class='w-10 text-gray-500 inline-block'>$num</span><pre class='m-0 text-sm'><code>" . htmlspecialchars($lines[$i]) . "</code></pre></div>";
            }
        }

        echo <<<HTML
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Erreur - Madeline Framework</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; }
        .mesh-bg { position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: -1; pointer-events: none; opacity: 0.15; background: radial-gradient(circle at 15% 50%, rgba(220, 38, 38, 0.4), transparent 50%), radial-gradient(circle at 85% 30%, rgba(220, 38, 38, 0.4), transparent 50%); }
    </style>
</head>
<body class="bg-[#0f0f12] text-white antialiased overflow-y-scroll">
    <div class="mesh-bg"></div>
    
    <div class="max-w-7xl mx-auto px-4 py-8">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-red-500 mb-2">$class</h1>
            <p class="text-xl text-gray-300 break-words">$message</p>
        </header>

        <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-2xl overflow-hidden mb-8">
            <div class="bg-gray-800 px-6 py-3 border-b border-gray-700 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-300">$file : $line</span>
            </div>
            
            <div class="bg-[#1e1e24] py-4 overflow-x-auto text-gray-300">
                $codeSnippet
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-lg shadow-2xl overflow-hidden mb-8">
            <div class="bg-gray-800 px-6 py-3 border-b border-gray-700">
                <h3 class="text-sm font-semibold text-gray-300">Stack Trace</h3>
            </div>
            <div class="p-6 overflow-x-auto">
                <pre class="text-xs text-gray-400 break-all whitespace-pre-wrap leading-relaxed">{$trace}</pre>
            </div>
        </div>
        
        <div class="text-center text-gray-600 text-sm mt-12 pb-8">
            Madeline Exception Handler
        </div>
    </div>
</body>
</html>
HTML;
    }
}
