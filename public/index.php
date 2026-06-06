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

// Models
require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/State.php';
require_once APP_PATH . '/Models/City.php';
require_once APP_PATH . '/Models/Facility.php';

// Controllers
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/AdminController.php';
require_once APP_PATH . '/Controllers/AdminFacilityController.php';
require_once APP_PATH . '/Controllers/OwnerController.php';

$router = new Router();

// Homepage
$router->get('/', function () {
    echo '<h1>ihomestay.my</h1><p>Coming soon. <a href="/login">Login</a> | <a href="/register">Register</a></p>';
});

// Auth routes
$router->get('/login',     ['AuthController', 'showLogin']);
$router->post('/login',    ['AuthController', 'handleLogin']);
$router->get('/register',  ['AuthController', 'showRegister']);
$router->post('/register', ['AuthController', 'handleRegister']);
$router->get('/logout',    ['AuthController', 'logout']);

// Admin — dashboard
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);

// Admin — facilities (specific routes before parameterised ones)
$router->get('/admin/facilities',          ['AdminFacilityController', 'index']);
$router->get('/admin/facilities/create',   ['AdminFacilityController', 'create']);
$router->post('/admin/facilities/store',   ['AdminFacilityController', 'store']);
$router->get('/admin/facilities/{id}/edit',    ['AdminFacilityController', 'edit']);
$router->post('/admin/facilities/{id}/update', ['AdminFacilityController', 'update']);
$router->post('/admin/facilities/{id}/delete', ['AdminFacilityController', 'delete']);
$router->post('/admin/facilities/{id}/toggle', ['AdminFacilityController', 'toggle']);

// Owner routes
$router->get('/owner/dashboard', ['OwnerController', 'dashboard']);

$router->dispatch();
