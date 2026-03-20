<?php
namespace Packages\Auth;

class AuthScaffold {
    public static function install(string $basePath) {
        self::createController($basePath);
        self::createModel($basePath);
        self::createViews($basePath);
        self::createMiddleware($basePath);
        self::injectRoutes($basePath);
        
        echo "✅ Scaffolding Auth (Login, Register, Dashboard, Profile, Forgot Password) installé avec succès !\n";
        echo "💡 Les routes ont été injectées automatiquement dans routes.php.\n";
        echo "👉 Vous pouvez maintenant exécuter 'php ligeey serve' et visiter /login.\n";
    }

    /**
     * Vérifie si l'utilisateur est connecté
     */
    public static function check(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return !empty($_SESSION['user_id']);
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     */
    public static function user() {
        if (self::check()) {
            return $_SESSION['user_id'];
        }
        return null;
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
            "/App/Views/auth/profile.madeline.php",
            "/App/Views/auth/forgot_password.madeline.php",
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
        $authControllerContent = <<<'PHP'
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
        if (!empty($_SESSION['user_id'])) { header('Location: /dashboard'); exit; }
        return MadelineView::render('auth/login');
    }

    public function loginPOST() {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($email) || empty($password)) {
            return MadelineView::render('auth/login', ['error' => 'Veuillez remplir tous les champs.']);
        }
        
        $userModel = new User();
        $users = $userModel->fari(['email' => $email]);
        
        if (!empty($users) && password_verify($password, $users[0]->password)) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            session_regenerate_id(true);
            $_SESSION['user_id']   = $users[0]->id;
            $_SESSION['user_name'] = $users[0]->name;
            header('Location: /dashboard');
            exit;
        }
        return MadelineView::render('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
    }

    public function register() {
        return MadelineView::render('auth/register');
    }

    public function registerPOST() {
        $user = new User();
        $user->name     = trim($_POST['name'] ?? '');
        $user->email    = trim($_POST['email'] ?? '');
        $user->password = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
        $user->bindu();
        header('Location: /login');
        exit;
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // Vider le cache des vues
        $cacheDir = __DIR__ . '/../../storage/cache/views';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '/*');
            foreach ($files as $file) {
                if (is_file($file)) unlink($file);
            }
        }

        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        header('Location: /login');
        exit;
    }

    public function profile() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = User::fari(['id' => $_SESSION['user_id']])[0] ?? null;
        return MadelineView::render('auth/profile', ['user' => $user]);
    }

    public function updateProfile() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        User::weccit($data, ['id' => $_SESSION['user_id']]);
        $_SESSION['user_name'] = $data['name'];
        header('Location: /profile?updated=1');
        exit;
    }

    public function forgotPassword() {
        return MadelineView::render('auth/forgot_password');
    }

    public function forgotPasswordPOST() {
        return MadelineView::render('auth/forgot_password', ['success' => 'Si cet email existe, un lien de réinitialisation a été envoyé.']);
    }
}
PHP;
        file_put_contents("$basePath/App/Controllers/AuthController.php", $authControllerContent);

        // --- DASHBOARD CONTROLLER ---
        $dashControllerContent = <<<'PHP'
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
        $userName = $_SESSION['user_name'] ?? 'Utilisateur';

        return MadelineView::render('auth/dashboard', [
            'name' => $userName
        ]);
    }
}
PHP;
        file_put_contents("$basePath/App/Controllers/DashboardController.php", $dashControllerContent);
    }

    private static function createModel($basePath) {
        @mkdir("$basePath/App/Models", 0777, true);

        $modelContent = <<<'PHP'
<?php
namespace App\Models;

use Packages\ORM\MadelineORM;

/**
 * Modèle: Utilisateur (Auto-Migré)
 */
class User extends MadelineORM {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
}
PHP;
        file_put_contents("$basePath/App/Models/User.php", $modelContent);
    }
    
    private static function createMiddleware($basePath) {
        @mkdir("$basePath/App/Middlewares", 0777, true);

        $mwContent = <<<'PHP'
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

        if (empty($_SESSION['user_id'])) {
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
        
        // Login View
        $loginHtml = <<<'HTML'
@indi('layout')

@def('pageTitle')Connexion — Madeline Business@jeexdef

@def('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full floating-card p-12 rounded-6xl border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-[#050510] tracking-tight mb-4">Bon retour.</h2>
            <p class="text-gray-400 text-sm font-medium">Accédez à votre cockpit de gestion.</p>
        </div>
        
        @ndax(isset($error))
            <div class="bg-red-50 text-red-500 text-[11px] font-bold uppercase tracking-widest p-4 rounded-2xl mb-8 border border-red-100 text-center">
                {{ $error }}
            </div>
        @jeexndax
        
        <form method="POST" action="/login" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Email Address</label>
                <input type="email" name="email" required placeholder="name@company.com" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <button type="submit" class="w-full btn-dark rounded-full py-5 text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-black/10 mt-4">
                Se Connecter ↗
            </button>
        </form>
        
        <div class="mt-10 text-center">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Nouveau ici ? <a href="/register" class="text-brand-500 hover:underline">Créer un compte —</a>
            </p>
        </div>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/login.madeline.php", $loginHtml);

        // Register View
        $registerHtml = <<<'HTML'
@indi('layout')

@def('pageTitle')Inscription — Madeline Business@jeexdef

@def('content')
<div class="min-h-[70vh] flex items-center justify-center">
    <div class="max-w-md w-full floating-card p-12 rounded-6xl border-gray-100">
        <div class="text-center mb-10">
            <h2 class="text-4xl font-extrabold text-[#050510] tracking-tight mb-4">Commencer.</h2>
            <p class="text-gray-400 text-sm font-medium">Créez votre compte Business en un instant.</p>
        </div>
        
        <form method="POST" action="/register" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Nom Complet</label>
                <input type="text" name="name" required placeholder="John Doe" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Email Address</label>
                <input type="email" name="email" required placeholder="name@company.com" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-4">Password</label>
                <input type="password" name="password" required placeholder="••••••••" class="w-full px-6 py-4 rounded-full bg-gray-50 border border-gray-100 focus:bg-white focus:ring-2 focus:ring-brand-500 focus:outline-none transition-all text-sm font-medium">
            </div>
            <button type="submit" class="w-full btn-dark rounded-full py-5 text-[11px] font-bold uppercase tracking-widest shadow-2xl shadow-black/10 mt-4">
                S'inscrire ↗
            </button>
        </form>
        
        <div class="mt-10 text-center">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest">
                Déjà inscrit ? <a href="/login" class="text-brand-500 hover:underline">Se connecter —</a>
            </p>
        </div>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/register.madeline.php", $registerHtml);

        // Dashboard View
        $dashboardHtml = <<<'HTML'
@indi('layout')

@def('content')
<div class="py-12">
    <header class="mb-12">
        <h1 class="text-5xl font-black text-[#050510] tracking-tight mb-4">Bonjour, {{ $name }} ! 👋</h1>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-[11px]">Bienvenue sur votre cockpit de gestion sécurisé.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="floating-card p-10 rounded-[3rem] bg-white border-gray-100">
            <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-500 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">Performances</h3>
            <p class="text-gray-400 text-sm font-medium">Accès sécurisé contrôlé par le middleware Madeline.</p>
        </div>
        
        <div class="floating-card p-10 rounded-[3rem] bg-white border-gray-100">
            <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-500 flex items-center justify-center mb-6">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            </div>
            <h3 class="text-lg font-bold mb-2">Sécurité Active</h3>
            <p class="text-gray-400 text-sm font-medium">Chiffrement Bcrypt et gestion de session native.</p>
        </div>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/dashboard.madeline.php", $dashboardHtml);

        // Profile View
        $profileHtml = <<<'HTML'
@indi('layout')

@def('content')
<div class="max-w-4xl mx-auto py-12">
    <div class="mb-12">
        <h1 class="text-4xl font-black text-[#050510] tracking-tight mb-4">Votre Profil</h1>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px]">Gérez vos informations personnelles et votre sécurité.</p>
    </div>

    @ndax(isset($_GET['updated']))
        <div class="mb-8 p-6 bg-green-50 border border-green-100 rounded-3xl text-green-600 text-[10px] font-black uppercase tracking-widest">
            Profil mis à jour avec succès ! ✨
        </div>
    @jeexndax

    <div class="floating-card p-12 rounded-[3rem] bg-white border-gray-100 shadow-[0_40px_100px_rgba(0,0,0,0.03)]">
        <form action="/profile/update" method="POST" class="space-y-10">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Nom Complet</label>
                    <input type="text" name="name" value="{{ $user->name }}" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Email Professionnel</label>
                    <input type="email" name="email" value="{{ $user->email }}" required class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
            </div>

            <div class="pt-10 border-t border-gray-50">
                <div class="space-y-3 max-w-md">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 ml-2">Nouveau Mot de Passe</label>
                    <input type="password" name="password" placeholder="Laisser vide pour ne pas changer" class="w-full bg-gray-50/50 border border-gray-100 rounded-2xl px-8 py-5 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
            </div>

            <div class="pt-8 flex justify-end">
                <button type="submit" class="px-12 py-5 rounded-full btn-dark text-[10px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-black/10">
                    Sauvegarder les modifications ↗
                </button>
            </div>
        </form>
    </div>
</div>
@jeexdef
HTML;
        file_put_contents("$basePath/App/Views/auth/profile.madeline.php", $profileHtml);

        // Forgot Password View
        $forgotHtml = <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié — Madeline</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen flex items-center justify-center p-6 bg-white">
    <div class="w-full max-w-md space-y-12">
        <div class="text-center">
            <h1 class="text-4xl font-black tracking-tight mb-4">Récupération.</h1>
            <p class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-400">Entrez votre email pour continuer</p>
        </div>

        @ndax(isset($success))
            <div class="p-8 rounded-3xl bg-green-50 border border-green-100 text-green-600 text-center space-y-4">
                <p class="text-[10px] font-black uppercase tracking-widest">{{ $success }}</p>
                <a href="/login" class="block text-[10px] font-black uppercase tracking-widest underline">Retour à la connexion</a>
            </div>
        @jeexndax

        @ndax(!isset($success))
            <form action="/forgot-password" method="POST" class="space-y-6">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 ml-2">Email</label>
                    <input type="email" name="email" required placeholder="nom@entreprise.com" class="w-full bg-gray-50/50 border border-gray-100 rounded-3xl px-8 py-6 text-sm font-bold focus:ring-2 focus:ring-brand-500 transition-all">
                </div>
                <button type="submit" class="w-full bg-[#050510] text-white rounded-3xl py-6 text-[10px] font-black uppercase tracking-[0.3em] shadow-2xl shadow-black/10 hover:scale-[1.02] transition-all">
                    Envoyer le lien ↗
                </button>
            </form>
        @jeexndax
    </div>
</body>
</html>
HTML;
        file_put_contents("$basePath/App/Views/auth/forgot_password.madeline.php", $forgotHtml);
    }

    private static function injectRoutes($basePath) {
        $routesFile = "$basePath/routes.php";
        if (file_exists($routesFile)) {
            $routesCode = file_get_contents($routesFile);
            
            // Si les routes n'ont pas déjà été injectées
            if (strpos($routesCode, '/logout') === false) {
                $injection = <<<'PHP'

// ==== ROUTES: SCATTERED AUTH SCAFFOLD ==== //
Router::get('/login', ['\App\Controllers\AuthController', 'login']);
Router::post('/login', ['\App\Controllers\AuthController', 'loginPOST']);
Router::get('/register', ['\App\Controllers\AuthController', 'register']);
Router::post('/register', ['\App\Controllers\AuthController', 'registerPOST']);
Router::get('/logout', ['\App\Controllers\AuthController', 'logout']);

// Profil & Sécurité
Router::get('/profile', ['\App\Controllers\AuthController', 'profile'], ['\App\Middlewares\AuthMiddleware']);
Router::post('/profile/update', ['\App\Controllers\AuthController', 'updateProfile'], ['\App\Middlewares\AuthMiddleware']);
Router::get('/forgot-password', ['\App\Controllers\AuthController', 'forgotPassword']);
Router::post('/forgot-password', ['\App\Controllers\AuthController', 'forgotPasswordPOST']);

// Route Sécurisée par l'AuthMiddleware
Router::get('/dashboard', ['\App\Controllers\DashboardController', 'index'], ['\App\Middlewares\AuthMiddleware']);
// ========================================= //
PHP;
                file_put_contents($routesFile, $routesCode . $injection);
            }
        }
    }
}
