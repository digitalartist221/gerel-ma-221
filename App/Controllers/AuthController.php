<?php
namespace App\Controllers;

use Packages\View\MadelineView;
use App\Models\User;
use Packages\Mail\Mail;
use Core\Config;

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
            $_SESSION['user_role'] = $users[0]->role ?? 'member'; // Fallback sécurisé : member, pas admin
            header('Location: /dashboard');
            exit;
        }
        return MadelineView::render('auth/login', ['error' => 'Email ou mot de passe incorrect.']);
    }

    public function register() {
        return MadelineView::render('auth/register');
    }

    public function registerPOST() {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($password)) {
            return MadelineView::render('auth/register', ['error' => 'Tous les champs sont obligatoires.']);
        }

        // Vérifier si c'est le tout premier utilisateur : il devient admin, sinon member
        $existing = User::fari();
        $role = empty($existing) ? 'admin' : 'member';

        $user = new User();
        $user->name     = $name;
        $user->email    = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role     = $role;
        $user->bindu();
        header('Location: /login?registered=1');
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
        $_SESSION['success'] = "Votre profil a été mis à jour avec succès.";
        header('Location: /profile');
        exit;
    }

    public function forgotPassword() {
        return MadelineView::render('auth/forgot_password');
    }

    public function forgotPasswordPOST() {
        $email = trim($_POST['email'] ?? '');
        if (empty($email)) {
            return MadelineView::render('auth/forgot_password', ['error' => 'Veuillez saisir votre adresse email.']);
        }

        $users = User::fari(['email' => $email]);
        // Pour la sécurité, on montre toujours le même message (pas d'information sur l'existence du compte)
        if (!empty($users)) {
            $user = $users[0];
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            User::weccit([
                'reset_token'            => $token,
                'reset_token_expires_at' => $expires
            ], ['id' => $user->id]);

            $baseUrl = rtrim(Config::get('app.url', 'http://localhost:8000'), '/');
            $resetUrl = $baseUrl . '/reset-password/' . $token;

            $body  = "Bonjour {$user->name},<br><br>";
            $body .= "Vous avez demandé la réinitialisation de votre mot de passe.<br>";
            $body .= "Ce lien est valable <b>1 heure</b>.<br><br>";
            $body .= "<a href='{$resetUrl}' style='background:#050510; color:white; padding:15px 30px; text-decoration:none; border-radius:50px; font-weight:bold; display:inline-block;'>Réinitialiser mon mot de passe</a>";
            $body .= "<br><br><small>Si vous n'avez pas demandé cette action, ignorez cet email.</small>";

            Mail::to($user->email, '[Maye] Réinitialisation de votre mot de passe', $body);
        }

        return MadelineView::render('auth/forgot_password', ['success' => 'Si cet email existe, un lien de réinitialisation vous a été envoyé.']);
    }

    public function resetPassword($token) {
        $users = User::fari(['reset_token' => $token]);
        $user  = $users[0] ?? null;

        if (!$user || empty($user->reset_token_expires_at) || strtotime($user->reset_token_expires_at) < time()) {
            return MadelineView::render('auth/forgot_password', ['error' => 'Ce lien de réinitialisation est invalide ou a expiré.']);
        }

        return MadelineView::render('auth/reset_password', ['token' => $token]);
    }

    public function resetPasswordPOST($token) {
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 8) {
            return MadelineView::render('auth/reset_password', ['token' => $token, 'error' => 'Le mot de passe doit faire au moins 8 caractères.']);
        }
        if ($password !== $confirm) {
            return MadelineView::render('auth/reset_password', ['token' => $token, 'error' => 'Les mots de passe ne correspondent pas.']);
        }

        $users = User::fari(['reset_token' => $token]);
        $user  = $users[0] ?? null;

        if (!$user || empty($user->reset_token_expires_at) || strtotime($user->reset_token_expires_at) < time()) {
            return MadelineView::render('auth/forgot_password', ['error' => 'Ce lien de réinitialisation est invalide ou a expiré.']);
        }

        User::weccit([
            'password'               => password_hash($password, PASSWORD_DEFAULT),
            'reset_token'            => null,
            'reset_token_expires_at' => null
        ], ['id' => $user->id]);

        header('Location: /login?reset=1');
        exit;
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
        
        if (empty($_POST['name']) || empty($_POST['email'])) {
            $_SESSION['error'] = "Le nom et l'email sont obligatoires pour un collaborateur.";
            header('Location: /equipe');
            exit;
        }

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