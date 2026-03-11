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

// Routes Documentation & Swagger
Router::get('/docs', ['App\Controllers\DocsController', 'guide']);
Router::get('/api/docs', ['App\Controllers\DocsController', 'index']);
Router::get('/api/docs/ui', ['App\Controllers\DocsController', 'ui']);

// Route secrète LiveReload pour le dev backend
Router::get('/api/dev/livereload', ['App\Controllers\DevController', 'livereload']);

