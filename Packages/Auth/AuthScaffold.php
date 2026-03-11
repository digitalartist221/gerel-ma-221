<?php
namespace Packages\Auth;

class AuthScaffold {
    public static function install(string $basePath) {
        self::createController($basePath);
        self::createModel($basePath);
        self::createViews($basePath);
        self::createMiddleware($basePath);
        self::injectRoutes($basePath);
        
        echo "✅ Scaffolding Auth (Login, Register, Dashboard, Middleware) installé avec succès !\n";
        echo "💡 Les routes ont été injectées automatiquement dans routes.php.\n";
        echo "👉 Vous pouvez maintenant exécuter 'php ligeey serve' et visiter /login.\n";
    }

    public static function uninstall(string $basePath) {
        // Liste des fichiers à supprimer
        $files = [
            "/App/Controllers/AuthController.php",
            "/App/Controllers/DashboardController.php",
            "/App/Models/User.php",
            "/App/Middlewares/AuthMiddleware.php",
            "/App/Views/auth/layout.madeline.php",
            "/App/Views/auth/login.madeline.php",
            "/App/Views/auth/register.madeline.php",
            "/App/Views/auth/dashboard.madeline.php",
        ];

        foreach ($files as $file) {
            if (file_exists($basePath . $file)) {
                unlink($basePath . $file);
                echo "🗑️  Supprimé: $file\n";
            }
        }

        // Nettoyage des dossiers s'ils sont vides
        foreach (["/App/Views/auth"] as $dir) {
            if (is_dir($basePath . $dir) && count(scandir($basePath . $dir)) <= 2) {
                rmdir($basePath . $dir);
            }
        }

        // Nettoyage des routes
        $routesFile = "$basePath/routes.php";
        if (file_exists($routesFile)) {
            $routesCode = file_get_contents($routesFile);
            $cleanCode = preg_replace('/\/\/ ==== ROUTES: SCATTERED AUTH SCAFFOLD ==== \/\/.*?\/\/ ========================================= \/\//s', '', $routesCode);
            file_put_contents($routesFile, $cleanCode);
            echo "🧹 Routes d'authentification retirées de routes.php\n";
        }

        echo "✅ Authentification désinstallée avec succès.\n";
    }

    private static function createController($basePath) {
        @mkdir("$basePath/App/Controllers", 0777, true);

        // --- AUTH CONTROLLER ---
        $authControllerContent = <<<PHP
<?php
namespace App\Controllers;

use Packages\View\MadelineView;
use App\Models\User;

/**
 * Controller: Authentification
 */
class AuthController {
    public function doc() {
        return ['description' => "Gère la connexion, l'inscription et la déconnexion."];
    }

    public function login() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!empty(\$_SESSION['user_id'])) { header('Location: /dashboard'); exit; }
        return MadelineView::render('auth/login');
    }

    public function loginPOST() {
        \$email    = trim(\$_POST['email'] ?? '');
        \$password = \$_POST['password'] ?? '';
        
        if (empty(\$email) || empty(\$password)) {
            return MadelineView::render('auth/login', ['error' => 'Veuillez remplir tous les champs.']);
        }
        
        \$userModel = new User();
        \$users = \$userModel->fari(['email' => \$email]);
        
        if (!empty(\$users) && password_verify(\$password, \$users[0]->password)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            session_regenerate_id(true);
            \$_SESSION['user_id']   = \$users[0]->id;
            \$_SESSION['user_name'] = \$users[0]->name;
            header('Location: /dashboard');
            exit;
        }
        return MadelineView::render('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
    }

    public function register() {
        return MadelineView::render('auth/register');
    }

    public function registerPOST() {
        \$user = new User();
        \$user->name     = trim(\$_POST['name'] ?? '');
        \$user->email    = trim(\$_POST['email'] ?? '');
        \$user->password = password_hash(\$_POST['password'] ?? '', PASSWORD_DEFAULT);
        \$user->bindu();
        header('Location: /login');
        exit;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        \$_SESSION = [];
        if (ini_get('session.use_cookies')) {
            \$p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, \$p['path'], \$p['domain'], \$p['secure'], \$p['httponly']);
        }
        session_destroy();
        header('Location: /login');
        exit;
    }
}
PHP;
        file_put_contents("$basePath/App/Controllers/AuthController.php", $authControllerContent);

        // --- DASHBOARD CONTROLLER ---
        $dashControllerContent = <<<PHP
<?php
namespace App\Controllers;

use Packages\View\MadelineView;

/**
 * Controller: Tableau de Bord (Protégé)
 */
class DashboardController {
    public function doc() {
        return ['description' => "Tableau de bord privé - nécessite une connexion."];
    }

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        \$userName = \$_SESSION['user_name'] ?? 'Utilisateur';

        return MadelineView::render('auth/dashboard', [
            'name' => \$userName
        ]);
    }
}
PHP;
        file_put_contents("$basePath/App/Controllers/DashboardController.php", $dashControllerContent);
    }

    private static function createModel($basePath) {
        @mkdir("$basePath/App/Models", 0777, true);

        $modelContent = <<<PHP
<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Utilisateur (Auto-Migré)
 */
class User extends MadelineORM {
    public int \$id;
    public string \$name;
    public string \$email;
    public string \$password;
}
PHP;
        file_put_contents("$basePath/App/Models/User.php", $modelContent);
    }
    
    private static function createMiddleware($basePath) {
        @mkdir("$basePath/App/Middlewares", 0777, true);

        $mwContent = <<<PHP
<?php
namespace App\Middlewares;

/**
 * Middleware: Protège l'accès aux routes privées
 */
class AuthMiddleware {
    public function handle(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty(\$_SESSION['user_id'])) {
            // Utilisateur non connecté : on coupe la route et on redirige
            header('Location: /login');
            exit;
            return false;
        }

        return true; // Continue vers le contrôleur
    }
}
PHP;
        file_put_contents("$basePath/App/Middlewares/AuthMiddleware.php", $mwContent);
    }

    private static function createViews($basePath) {
        @mkdir("$basePath/App/Views/auth", 0777, true);
        
        // Layout partagé
        $layoutHtml = <<<HTML
<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Madeline Auth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 dark:bg-[#050507] text-gray-800 dark:text-gray-200 antialiased min-h-screen">
    @biir('content')
</body>
</html>
HTML;
        file_put_contents("$basePath/App/Views/auth/layout.madeline.php", $layoutHtml);

        // Login View
        $loginHtml = <<<HTML
@indi('auth/layout')

@def('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-[-10%] left-[-10%] w-[50vw] h-[50vw] bg-blue-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>
    <div class="absolute bottom-[-20%] right-[-10%] w-[40vw] h-[40vw] bg-purple-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-100 dark:border-white/10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-purple-600 mb-2">Bienvenue</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Connectez-vous à votre espace personnel</p>
        </div>
        
        @ndax(isset(\$error))
            <div class="bg-red-500/10 text-red-500 text-sm p-4 rounded-xl mb-6 border border-red-500/20 text-center font-medium">
                {{ \$error }}
            </div>
        @jeexndax
        
        <form method="POST" action="/login" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1.5 dark:text-gray-300">Adresse Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 dark:text-gray-300">Mot de passe</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl py-3.5 mt-8 transition-all hover:shadow-lg hover:shadow-blue-500/30">Se Connecter</button>
        </form>
        <p class="mt-8 text-center text-sm dark:text-gray-400">Pas encore de compte ? <a href="/register" class="text-blue-500 hover:text-blue-400 font-semibold transition-colors">S'inscrire</a></p>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/login.madeline.php", $loginHtml);

        // Register View
        $registerHtml = <<<HTML
@indi('auth/layout')

@def('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute top-[-10%] right-[-10%] w-[50vw] h-[50vw] bg-green-600/10 rounded-full filter blur-[100px] pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 bg-white dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-gray-100 dark:border-white/10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-600 mb-2">Rejoignez-nous</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Créez votre compte en quelques secondes</p>
        </div>
        
        <form method="POST" action="/register" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1.5 dark:text-gray-300">Nom Complet</label>
                <input type="text" name="name" required class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-green-500 focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 dark:text-gray-300">Adresse Email</label>
                <input type="email" name="email" required class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-green-500 focus:outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1.5 dark:text-gray-300">Mot de passe</label>
                <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-white/10 focus:ring-2 focus:ring-green-500 focus:outline-none transition-all">
            </div>
            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl py-3.5 mt-8 transition-all hover:shadow-lg hover:shadow-green-500/30">Créer mon compte</button>
        </form>
        <p class="mt-8 text-center text-sm dark:text-gray-400">Déjà inscrit ? <a href="/login" class="text-green-500 hover:text-green-400 font-semibold transition-colors">Se connecter</a></p>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/register.madeline.php", $registerHtml);

        // Dashboard View
        $dashboardHtml = <<<HTML
@indi('auth/layout')

@def('content')
<div class="min-h-screen bg-gray-50 dark:bg-[#050507]">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800/50 backdrop-blur-lg border-b border-gray-200 dark:border-white/10 p-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded bg-blue-600 text-white flex items-center justify-center font-bold">M</div>
                <span class="font-semibold text-lg tracking-tight">Madeline APPS</span>
            </div>
            <a href="/logout" class="px-4 py-2 rounded-full border border-red-500/50 text-red-500 hover:bg-red-500 hover:text-white transition-colors text-sm font-medium">Déconnexion</a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto p-8 mt-10">
        <header class="mb-10">
            <h1 class="text-4xl font-bold mb-2">Bonjour, {{ \$name }} ! 👋</h1>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Bienvenue sur votre espace sécurisé.</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-gray-800/40 rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Performances</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Le middleware d'authentification a bloqué l'accès au portail en millisecondes.</p>
            </div>
            
            <!-- Card 2 -->
            <div class="bg-white dark:bg-gray-800/40 rounded-3xl p-6 border border-gray-100 dark:border-white/5 shadow-sm">
                <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <h3 class="text-lg font-semibold mb-2">Sécurité Active</h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">Votre mot de passe est encrypté (Bcrypt) et l'accès est entièrement contrôlé.</p>
            </div>
        </div>
    </main>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/dashboard.madeline.php", $dashboardHtml);
    }

    private static function injectRoutes($basePath) {
        $routesFile = "$basePath/routes.php";
        if (file_exists($routesFile)) {
            $routesCode = file_get_contents($routesFile);
            
            // Si les routes n'ont pas déjà été injectées
            if (strpos($routesCode, '/logout') === false) {
                $injection = <<<PHP

// ==== ROUTES: SCATTERED AUTH SCAFFOLD ==== //
Router::get('/login', ['\App\Controllers\AuthController', 'login']);
Router::post('/login', ['\App\Controllers\AuthController', 'loginPOST']);
Router::get('/register', ['\App\Controllers\AuthController', 'register']);
Router::post('/register', ['\App\Controllers\AuthController', 'registerPOST']);
Router::get('/logout', ['\App\Controllers\AuthController', 'logout']);

// Route Sécurisée par l'AuthMiddleware
Router::get('/dashboard', ['\App\Controllers\DashboardController', 'index'], ['\App\Middlewares\AuthMiddleware']);
// ========================================= //
PHP;
                file_put_contents($routesFile, $routesCode . $injection);
            }
        }
    }
}
