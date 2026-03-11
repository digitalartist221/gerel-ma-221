<?php
namespace App\Controllers;

/**
 * Page: Documentation API
 * Author: Digital Artist Studio
 * Version: 1.0.0
 * Framework: Madeline (Expert PHP)
 */
class DocsController {
    public function doc() {
        return [
            'description' => 'Générateur de documentation technique / OpenAPI pour le projet Madeline',
            'methods' => [
                'index' => 'Extrait dynamiquement la doc de tous les contrôleurs au format JSON / OpenAPI',
                'ui' => 'Affiche l\'interface visuelle Swagger UI'
            ]
        ];
    }

    public function guide() {
        return \Packages\View\MadelineView::render('docs/guide');
    }

    private function getApiDocs() {
        // Synchronisation intelligente : On lit les routes réellement déclarées
        $routes = \Core\Router::getRoutes();
        $paths = [];
        
        // On récupère toutes les docs des contrôleurs instanciables
        $controllersDocs = [];
        $controllerFiles = glob(__DIR__ . '/*.php');
        foreach ($controllerFiles as $file) {
            $base = basename($file, '.php');
            if ($base === 'DocsController') continue;
            
            try {
                if (!class_exists('App\\Controllers\\' . $base)) {
                    require_once $file;
                }
                $className = 'App\\Controllers\\' . $base;
                if (class_exists($className)) {
                    $instance = new $className();
                    if (method_exists($instance, 'doc')) {
                        $controllersDocs[$className] = $instance->doc();
                    }
                }
            } catch (\Throwable $e) {
                // Controller buggé ignoré silencieusement dans la doc
                error_log('[Madeline Docs] Cannot load controller ' . $base . ': ' . $e->getMessage());
            }
        }

        // On associe chaque route à sa documentation
        foreach ($routes as $route) {
            $uri = '/' . ltrim(str_replace('#^/', '', str_replace('#^', '', str_replace('$#', '', $route['pattern']))), '/');
            // Remettre {param} au lieu de la syntaxe preg_replace interne
            $uri = preg_replace('/\(\?P<([a-zA-Z0-9_]+)>\[a\-zA\-Z0\-9_\]\+\)/', '{$1}', $uri);
            
            $method = strtolower($route['method']);
            $action = $route['action'];
            
            if (is_array($action) && isset($action[0]) && isset($action[1])) {
                $controllerName = ltrim($action[0], '\\');
                $methodName = $action[1];
                
                // Retirer App\Controllers\ si présent
                $shortName = str_replace('App\\Controllers\\', '', $controllerName);
                
                $summary = "Route vers $shortName::$methodName";
                
                // Si une doc existe pour ce contrôleur
                if (isset($controllersDocs['App\\Controllers\\' . $shortName])) {
                    $doc = $controllersDocs['App\\Controllers\\' . $shortName];
                    if (isset($doc['methods'][$methodName])) {
                        $summary = $doc['methods'][$methodName];
                    }
                }
                
                if (!isset($paths[$uri])) {
                    $paths[$uri] = [];
                }
                
                $paths[$uri][$method] = [
                    'summary' => $summary,
                    'tags' => [$shortName],
                    'responses' => [
                        '200' => ['description' => 'Succès']
                    ]
                ];
                
                // Détection de paramètres d'URL {id} etc.
                preg_match_all('/\{([a-zA-Z0-9_]+)\}/', $uri, $matches);
                if (!empty($matches[1])) {
                    $paths[$uri][$method]['parameters'] = [];
                    foreach ($matches[1] as $param) {
                        $paths[$uri][$method]['parameters'][] = [
                            'name' => $param,
                            'in' => 'path',
                            'required' => true,
                            'schema' => ['type' => 'string']
                        ];
                    }
                }
            }
        }
        
        return [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Madeline App API Documentation',
                'description' => 'Documentation des API générée dynamiquement et synchronisée avec le Routeur.',
                'version' => '1.0.0',
                'framework' => 'Madeline'
            ],
            'paths' => $paths ?: new \stdClass()
        ];
    }

    public function index() {
        header('Content-Type: application/json');
        echo json_encode($this->getApiDocs(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function ui() {
        return \Packages\View\MadelineView::render('docs/api', [
            'spec' => json_encode($this->getApiDocs())
        ]);
    }
}
