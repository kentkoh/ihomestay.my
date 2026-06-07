<?php

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

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
require_once APP_PATH . '/Models/Article.php';

// Controllers
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/AdminController.php';
require_once APP_PATH . '/Controllers/AdminFacilityController.php';
require_once APP_PATH . '/Controllers/AdminListingController.php';
require_once APP_PATH . '/Controllers/OwnerController.php';
require_once APP_PATH . '/Controllers/OwnerListingController.php';
require_once APP_PATH . '/Controllers/AdminArticleController.php';
require_once APP_PATH . '/Controllers/PublicController.php';

$router = new Router();

// Homepage
$router->get('/', ['PublicController', 'home']);

// Public search & listing detail (must be before parameterised state/city routes)
$router->get('/search',         ['PublicController', 'search']);
$router->get('/listing/{slug}', ['PublicController', 'listingDetail']);

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

// Admin — articles (fixed routes before parameterised)
$router->get('/admin/articles',                  ['AdminArticleController', 'index']);
$router->get('/admin/articles/create',           ['AdminArticleController', 'create']);
$router->post('/admin/articles/store',           ['AdminArticleController', 'store']);
$router->post('/admin/articles/upload-image',    ['AdminArticleController', 'uploadImage']);
$router->get('/admin/articles/{id}/edit',        ['AdminArticleController', 'edit']);
$router->post('/admin/articles/{id}/update',     ['AdminArticleController', 'update']);
$router->post('/admin/articles/{id}/delete',     ['AdminArticleController', 'delete']);
$router->post('/admin/articles/{id}/toggle',     ['AdminArticleController', 'toggle']);

// Owner — dashboard
$router->get('/owner/dashboard', ['OwnerController', 'dashboard']);

// Owner — listings (specific routes before parameterised)
$router->get('/owner/listings',                                       ['OwnerListingController', 'index']);
$router->get('/owner/listings/create',                                ['OwnerListingController', 'create']);
$router->post('/owner/listings/store',                                ['OwnerListingController', 'store']);
$router->get('/owner/listings/{id}/edit',                             ['OwnerListingController', 'edit']);
$router->post('/owner/listings/{id}/update',                          ['OwnerListingController', 'update']);
$router->post('/owner/listings/{id}/delete',                          ['OwnerListingController', 'delete']);
$router->post('/owner/listings/{id}/images/upload',                   ['OwnerListingController', 'uploadImages']);
$router->post('/owner/listings/{listingId}/images/{imageId}/delete',  ['OwnerListingController', 'deleteImage']);
$router->post('/owner/listings/{listingId}/images/{imageId}/primary', ['OwnerListingController', 'setPrimary']);

// Public state/city pages (parameterised — must be last)
$router->get('/{stateSlug}',             ['PublicController', 'stateListings']);
$router->get('/{stateSlug}/{citySlug}',  ['PublicController', 'cityListings']);

$router->dispatch();
