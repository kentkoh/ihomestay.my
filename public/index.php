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
require_once APP_PATH . '/Models/Listing.php';

// Controllers
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/AdminController.php';
require_once APP_PATH . '/Controllers/AdminFacilityController.php';
require_once APP_PATH . '/Controllers/AdminListingController.php';
require_once APP_PATH . '/Controllers/OwnerController.php';
require_once APP_PATH . '/Controllers/OwnerListingController.php';

$router = new Router();

// Homepage
$router->get('/', function () {
    echo '<h1>ihomestay.my</h1><p>Coming soon. <a href="/login">Login</a> | <a href="/register">Register</a></p>';
});

// Auth
$router->get('/login',     ['AuthController', 'showLogin']);
$router->post('/login',    ['AuthController', 'handleLogin']);
$router->get('/register',  ['AuthController', 'showRegister']);
$router->post('/register', ['AuthController', 'handleRegister']);
$router->get('/logout',    ['AuthController', 'logout']);

// Admin — dashboard
$router->get('/admin/dashboard', ['AdminController', 'dashboard']);

// Admin — facilities
$router->get('/admin/facilities',              ['AdminFacilityController', 'index']);
$router->get('/admin/facilities/create',       ['AdminFacilityController', 'create']);
$router->post('/admin/facilities/store',       ['AdminFacilityController', 'store']);
$router->get('/admin/facilities/{id}/edit',    ['AdminFacilityController', 'edit']);
$router->post('/admin/facilities/{id}/update', ['AdminFacilityController', 'update']);
$router->post('/admin/facilities/{id}/delete', ['AdminFacilityController', 'delete']);
$router->post('/admin/facilities/{id}/toggle', ['AdminFacilityController', 'toggle']);

// Admin — listings
$router->get('/admin/listings',                  ['AdminListingController', 'index']);
$router->post('/admin/listings/{id}/approve',    ['AdminListingController', 'approve']);
$router->post('/admin/listings/{id}/reject',     ['AdminListingController', 'reject']);
$router->post('/admin/listings/{id}/suspend',    ['AdminListingController', 'suspend']);

// Owner — dashboard
$router->get('/owner/dashboard', ['OwnerController', 'dashboard']);

// Owner — listings (specific routes before parameterised)
$router->get('/owner/listings',                                       ['OwnerListingController', 'index']);
$router->get('/owner/listings/create',                                ['OwnerListingController', 'create']);
$router->post('/owner/listings/store',                                ['OwnerListingController', 'store']);
$router->get('/owner/listings/{id}/edit',                             ['OwnerListingController', 'edit']);
$router->post('/owner/listings/{id}/update',                          ['OwnerListingController', 'update']);
$router->post('/owner/listings/{id}/delete',                          ['OwnerListingController', 'delete']);
$router->post('/owner/listings/{listingId}/images/{imageId}/delete',  ['OwnerListingController', 'deleteImage']);
$router->post('/owner/listings/{listingId}/images/{imageId}/primary', ['OwnerListingController', 'setPrimary']);

$router->dispatch();
