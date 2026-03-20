<?php
use Core\Router;

use Packages\View\MadelineView;

// Routes par défaut - Bienvenue
Router::get('/', function() {
    return MadelineView::render('welcome');
});

// Route MVC Users
Router::get('/users', ['App\Controllers\UserController', 'index']);
Router::get('/users/{id}', ['App\Controllers\UserController', 'show']);

// --- MADELINE BUSINESS SUITE ---

// Management MVC
// Les routes dashboard sont gérées par le scaffold en bas avec middleware
// Management MVC (Protégé)
Router::get('/entreprises', ['App\Controllers\EntrepriseController', 'index'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/entreprises/nouveau', ['App\Controllers\EntrepriseController', 'amul'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/entreprises/edit/{id}', ['App\Controllers\EntrepriseController', 'edit'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::post('/entreprises/save', ['App\Controllers\EntrepriseController', 'bindu'], ['\App\Middlewares\AuthMiddleware:admin']);

Router::get('/produits', ['App\Controllers\ProduitController', 'index'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/produits/nouveau', ['App\Controllers\ProduitController', 'amul'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/produits/edit/{id}', ['App\Controllers\ProduitController', 'edit'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::post('/produits/save', ['App\Controllers\ProduitController', 'bindu'], ['\App\Middlewares\AuthMiddleware:commercial']);

Router::get('/clients', ['App\Controllers\ClientController', 'index'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/clients/nouveau', ['App\Controllers\ClientController', 'amul'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/clients/edit/{id}', ['App\Controllers\ClientController', 'edit'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/clients/view/{id}', ['App\Controllers\ClientController', 'view'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::post('/clients/save', ['App\Controllers\ClientController', 'bindu'], ['\App\Middlewares\AuthMiddleware:commercial']);

// Documents & Contrats (Protégés)
Router::get('/documents', ['App\Controllers\DocumentController', 'index'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/documents/nouveau', ['App\Controllers\DocumentController', 'amul'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/documents/edit/{id}', ['App\Controllers\DocumentController', 'edit'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::post('/documents/save', ['App\Controllers\DocumentController', 'bindu'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/documents/transform/{id}/{toType}', ['App\Controllers\DocumentController', 'transform'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/documents/send/{id}', ['App\Controllers\DocumentController', 'sendEmail'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/documents/print/{id}', ['App\Controllers\DocumentController', 'print'], ['\App\Middlewares\AuthMiddleware:commercial']);

Router::get('/contrats', ['App\Controllers\ContratController', 'index'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/contrats/nouveau', ['App\Controllers\ContratController', 'create'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/contrats/edit/{id}', ['App\Controllers\ContratController', 'edit'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::post('/contrats/save', ['App\Controllers\ContratController', 'bindu'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/contrats/send/{id}', ['App\Controllers\ContratController', 'sendEmail'], ['\App\Middlewares\AuthMiddleware:commercial']);
Router::get('/contrats/print/{id}', ['App\Controllers\ContratController', 'print'], ['\App\Middlewares\AuthMiddleware:commercial']);

// Journal de Caisse & Fiscalité (Protégés : Admin ou Comptable)
Router::get('/caisse', ['App\Controllers\CaisseController', 'index'], ['\App\Middlewares\AuthMiddleware:comptable']);
Router::get('/caisse/nouveau', ['App\Controllers\CaisseController', 'amul'], ['\App\Middlewares\AuthMiddleware:comptable']);
Router::get('/caisse/edit/{id}', ['App\Controllers\CaisseController', 'edit'], ['\App\Middlewares\AuthMiddleware:comptable']);
Router::post('/caisse/save', ['App\Controllers\CaisseController', 'bindu'], ['\App\Middlewares\AuthMiddleware:comptable']);
Router::get('/caisse/delete/{id}', ['App\Controllers\CaisseController', 'delete'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/caisse/rapport', ['App\Controllers\CaisseController', 'report'], ['\App\Middlewares\AuthMiddleware:comptable']);

Router::get('/equipe', ['App\Controllers\AuthController', 'teamList'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/equipe/nouveau', ['App\Controllers\AuthController', 'teamAdd'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/equipe/edit/{id}', ['App\Controllers\AuthController', 'teamEdit'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::post('/equipe/save', ['App\Controllers\AuthController', 'teamSave'], ['\App\Middlewares\AuthMiddleware:admin']);

// Super-Admin & Waitlist (Module 5)
Router::get('/admin/waitlist', ['App\Controllers\AdminController', 'waitlist'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::get('/admin/liste-utilisateurs', ['App\Controllers\AdminController', 'users'], ['\App\Middlewares\AuthMiddleware:admin']);
Router::post('/api/waitlist/join', ['App\Controllers\AdminController', 'joinWaitlist']); // Public

// Vue Publique (Token Bridge)
Router::get('/view/contrat/{token}', ['App\Controllers\ContratController', 'publicView']);
Router::post('/view/contrat/{token}/action', ['App\Controllers\ContratController', 'publicAction']);

Router::get('/view/{type}/{token}', ['App\Controllers\DocumentController', 'publicView']);
Router::post('/view/{type}/{token}/action', ['App\Controllers\DocumentController', 'publicAction']);

// Paiement Paytech
Router::get('/payment/init/{doc_id}', ['App\Controllers\PaytechController', 'init']);
Router::get('/payment/success', ['App\Controllers\PaytechController', 'success']);
Router::get('/payment/cancel', ['App\Controllers\PaytechController', 'cancel']);


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