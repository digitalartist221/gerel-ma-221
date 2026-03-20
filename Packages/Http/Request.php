<?php
namespace Packages\Http;

/**
 * Request: Gestionnaire de formulaires et requêtes HTTP
 * Framework: Madeline
 */
class Request {
    private array $params;
    private array $json = [];
    private array $errors = [];
    private array $files = [];
    private array $cookies = [];

    public function __construct() {
        $this->params = array_merge($_GET, $_POST);
        $this->files = $_FILES;
        $this->cookies = $_COOKIE;
        
        // Support JSON
        $input = file_get_contents('php://input');
        if (!empty($input)) {
            $decoded = json_decode($input, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->json = $decoded;
                $this->params = array_merge($this->params, $this->json);
            }
        }
    }

    /**
     * Récupère une valeur (POST, GET ou JSON)
     * Aliases Wolof: jël()
     */
    public function input(string $key, $default = null) {
        return $this->params[$key] ?? $default;
    }

    public function jël(string $key, $default = null) {
        return $this->input($key, $default);
    }

    /**
     * Récupère tous les paramètres
     */
    public function all(array $only = []): array {
        if (!empty($only)) {
            $result = [];
            foreach ($only as $key) {
                $result[$key] = $this->input($key);
            }
            return $result;
        }
        return $this->params;
    }

    /**
     * Gestion des fichiers
     */
    public function file(string $key) {
        return $this->files[$key] ?? null;
    }

    public function hasFile(string $key): bool {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }

    /**
     * Cookies
     */
    public function cookie(string $key, $default = null) {
        return $this->cookies[$key] ?? $default;
    }

    /**
     * Session helpers
     */
    public function session(string $key, $default = null) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verbes HTTP
     */
    public function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function isMethod(string $method): bool {
        return $this->method() === strtoupper($method);
    }

    public function isPost(): bool { return $this->isMethod('POST'); }
    public function isGet(): bool { return $this->isMethod('GET'); }
    public function isPut(): bool { return $this->isMethod('PUT'); }
    public function isDelete(): bool { return $this->isMethod('DELETE'); }

    /**
     * En-têtes (Headers)
     */
    public function header(string $key, $default = null) {
        $header = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $_SERVER[$header] ?? $_SERVER[strtoupper($key)] ?? $default;
    }

    /**
     * Métadonnées
     */
    public function ip(): string {
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function uri(): string {
        return $_SERVER['REQUEST_URI'] ?? '/';
    }

    public function userAgent(): string {
        return $this->header('User-Agent', '');
    }

    /**
     * Validation avancée
     * Aliases Wolof: seet()
     */
    public function validate(array $rules): bool {
        foreach ($rules as $field => $fieldRules) {
            $value = $this->input($field);
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $parameters = [];
                if (strpos($rule, ':') !== false) {
                    list($rule, $params) = explode(':', $rule);
                    $parameters = explode(',', $params);
                }

                $this->applyRule($field, $value, $rule, $parameters);
            }
        }
        return empty($this->errors);
    }

    private function applyRule($field, $value, $rule, $parameters) {
        switch ($rule) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field] = "Le champ [{$field}] est obligatoire.";
                }
                break;
            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = "L'adresse email est invalide.";
                }
                break;
            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->errors[$field] = "Le champ [{$field}] doit être un nombre.";
                }
                break;
            case 'min':
                if (!empty($value) && strlen($value) < $parameters[0]) {
                    $this->errors[$field] = "Le champ [{$field}] doit faire au moins {$parameters[0]} caractères.";
                }
                break;
            case 'max':
                if (!empty($value) && strlen($value) > $parameters[0]) {
                    $this->errors[$field] = "Le champ [{$field}] ne doit pas dépasser {$parameters[0]} caractères.";
                }
                break;
            case 'boolean':
                if (!empty($value) && !is_bool($value) && !in_array($value, [0, 1, '0', '1', 'true', 'false'])) {
                    $this->errors[$field] = "Le champ [{$field}] doit être un booléen.";
                }
                break;
            case 'url':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$field] = "Le champ [{$field}] doit être une URL valide.";
                }
                break;
        }
    }

    public function seet(array $rules): bool {
        return $this->validate($rules);
    }

    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }
}
