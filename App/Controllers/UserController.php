<?php
namespace App\Controllers;

use App\Models\User;
use Packages\View\MadelineView;

/**
 * Page: Gestion des Utilisateurs
 * Author: Digital Artist Studio
 * Version: 1.0.0
 * Framework: Madeline (Expert PHP)
 */
class UserController {
    /**
     * Documentation in-app exigée pour l'API.
     */
    public function doc() {
        return [
            'description' => 'Contrôleur gérant les utilisateurs du système',
            'methods' => [
                'index' => 'Liste tous les utilisateurs',
                'show' => 'Affiche un utilisateur spécifique (ID)'
            ]
        ];
    }
    
    public function index() {
        $userModel = new User();
        // Utilisation de la méthode Wolof : fari() pour un SELECT dynamique
        $users = $userModel->fari();
        
        return MadelineView::render('users/index', ['users' => $users]);
    }

    public function show($id) {
        $userModel = new User();
        $user = $userModel->fari(['id' => $id]);
        
        if (empty($user)) {
            // Vue de base avec 404
            return MadelineView::render('errors/404', ['message' => 'Wut ngéén diko wut amul (Utilisateur non trouvé)']);
        }
        
        return MadelineView::render('users/show', ['user' => $user[0]]);
    }
}
