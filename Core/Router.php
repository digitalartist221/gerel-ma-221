<?php
namespace Core;

class Router {
    private static $routes = [];

    public static function get($uri, $action, $middlewares = []) {
        self::addRoute('GET', $uri, $action, $middlewares);
    }

    public static function post($uri, $action, $middlewares = []) {
        self::addRoute('POST', $uri, $action, $middlewares);
    }

    private static function addRoute($method, $uri, $action, $middlewares = []) {
        // Convertit l'URI pour la rendre absolue par rapport au web root
        $uri = '/' . ltrim($uri, '/');
        // Conversion de l'URI en regex pour capturer les paramètres
        $uriPattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_]+)', $uri);
        $uriPattern = '#^' . $uriPattern . '$#';
        
        self::$routes[] = [
            'method' => $method,
            'pattern' => $uriPattern,
            'action' => $action,
            'middlewares' => (array) $middlewares
        ];
    }
    
    public static function getRoutes() {
        return self::$routes;
    }

    public static function dispatch($url) {
        $url = '/' . ltrim($url, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        // Routes d'infrastructure par défaut
        if ($url === '/setup') {
            $setupController = new SetupController();
            return $setupController->index();
        }

        foreach (self::$routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $url, $matches)) {
                // Nettoyer les clés numériques des regex matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                $action = $route['action'];
                if (is_callable($action)) {
                    return call_user_func_array($action, $params);
                } elseif (is_array($action)) {
                    // Exécution des Middlewares Globaux (SecurityHeaders, Csrf, etc.)
                    $globalMiddlewares = \Core\Config::get('middlewares', []);
                    $routeMiddlewares = $route['middlewares'] ?? [];
                    
                    $allMiddlewares = array_merge($globalMiddlewares, $routeMiddlewares);
                    
                    foreach ($allMiddlewares as $mwClass) {
                        if (class_exists($mwClass)) {
                            $middleware = new $mwClass();
                            if (!$middleware->handle()) {
                                // Mettre fin si le middleware renvoie false (la réponse a sûrement déjà été gérée/redirigée)
                                return;
                            }
                        }
                    }

                    $controllerName = $action[0];
                    $methodName = $action[1];
                    $controller = new $controllerName();
                    return call_user_func_array([$controller, $methodName], $params);
                }
            }
        }

        // 404
        http_response_code(404);
        
        // Rendu de la vue 404 Design (si elle échoue, on fait un simple require ou echo fallback)
        try {
            echo \Packages\View\MadelineView::render('errors/404', ['url' => $url]);
        } catch (\Throwable $e) {
            echo "<h1>404 - Faranfàcce bi amul (Page non trouvée)</h1>";
            echo "<p>La route <strong>" . htmlspecialchars($url) . "</strong> n'est pas définie.</p>";
        }
    }
}
