<?php

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Router.php';
require_once APP_PATH . '/Core/Auth.php';
require_once APP_PATH . '/Core/CSRF.php';
require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/AdminController.php';
require_once APP_PATH . '/Controllers/OwnerController.php';

$router = new Router();

// Homepage
$router->get('/', function () {
    echo '<h1>ihomestay.my</h1><p>Coming soon. <a href="/login">Login</a> | <a href="/register">Register</a></p>';
});

// Auth routes
$router->get('/login',    ['AuthController', 'showLogin']);
$router->post('/login',   ['AuthController', 'handleLogin']);
$router->get('/register', ['AuthController', 'showRegister']);
$router->post('/register',['AuthController', 'handleRegister']);
$router->get('/logout',   ['AuthController', 'logout']);

// Admin routes
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);

// Owner routes
$router->get('/owner/dashboard', ['OwnerController', 'dashboard']);

$router->dispatch();
