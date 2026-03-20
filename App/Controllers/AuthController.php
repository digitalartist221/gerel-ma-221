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
            $_SESSION['user_role'] = $users[0]->role ?? 'admin';
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
        $user->role     = 'admin';
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

    public function teamList() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $members = User::fari(['parent_id' => $_SESSION['user_id']]);
        return MadelineView::render('auth/team', ['members' => $members]);
    }

    public function teamAdd() {
        return MadelineView::render('auth/team_edit', ['member' => null]);
    }

    public function teamEdit($id) {
        $member = User::fari(['id' => $id])[0] ?? null;
        return MadelineView::render('auth/team_edit', ['member' => $member]);
    }

    public function teamSave() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $data = [
            'name' => $_POST['name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'role' => $_POST['role'] ?? 'member',
            'parent_id' => $_SESSION['user_id']
        ];
        
        if (!empty($_POST['password'])) {
            $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        $id = $_POST['id'] ?? null;
        if ($id) {
            User::weccit($data, ['id' => $id]);
            $_SESSION['success'] = "Profil du collaborateur mis à jour.";
        } else {
            User::bindu($data);
            $_SESSION['success'] = "Nouveau membre ajouté à l'équipe !";
        }
        
        header('Location: /equipe');
        exit;
    }
}