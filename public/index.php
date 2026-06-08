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
require_once APP_PATH . '/Core/Mailer.php';

// Models
require_once APP_PATH . '/Models/User.php';
require_once APP_PATH . '/Models/State.php';
require_once APP_PATH . '/Models/City.php';
require_once APP_PATH . '/Models/Facility.php';
require_once APP_PATH . '/Models/Listing.php';
require_once APP_PATH . '/Models/Article.php';
require_once APP_PATH . '/Models/FeaturedPackage.php';
require_once APP_PATH . '/Models/Payment.php';
require_once APP_PATH . '/Models/VerificationRequest.php';
require_once APP_PATH . '/Models/ListingPromotion.php';
require_once APP_PATH . '/Models/ListingBlockedDate.php';

// Controllers
require_once APP_PATH . '/Controllers/AuthController.php';
require_once APP_PATH . '/Controllers/AdminController.php';
require_once APP_PATH . '/Controllers/AdminFacilityController.php';
require_once APP_PATH . '/Controllers/AdminListingController.php';
require_once APP_PATH . '/Controllers/OwnerController.php';
require_once APP_PATH . '/Controllers/OwnerListingController.php';
require_once APP_PATH . '/Controllers/AdminArticleController.php';
require_once APP_PATH . '/Controllers/AdminOwnerController.php';
require_once APP_PATH . '/Controllers/PublicController.php';
require_once APP_PATH . '/Controllers/PaymentController.php';
require_once APP_PATH . '/Controllers/AdminFeaturedPackageController.php';
require_once APP_PATH . '/Controllers/VerificationController.php';
require_once APP_PATH . '/Controllers/AdminVerificationController.php';
require_once APP_PATH . '/Controllers/OwnerPromotionController.php';
require_once APP_PATH . '/Controllers/OwnerAvailabilityController.php';

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
$router->get('/logout',           ['AuthController', 'logout']);
$router->get('/auth/google',          ['AuthController', 'googleRedirect']);
$router->get('/auth/google/callback', ['AuthController', 'googleCallback']);
$router->get('/forgot-password',  ['AuthController', 'showForgotPassword']);
$router->post('/forgot-password', ['AuthController', 'handleForgotPassword']);
$router->get('/reset-password',   ['AuthController', 'showResetPassword']);
$router->post('/reset-password',  ['AuthController', 'handleResetPassword']);

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
$router->get('/admin/listings',                    ['AdminListingController', 'index']);
$router->get('/admin/listings/{id}/edit',          ['AdminListingController', 'edit']);
$router->post('/admin/listings/{id}/update',       ['AdminListingController', 'update']);
$router->post('/admin/listings/{id}/delete',       ['AdminListingController', 'deleteListing']);
$router->post('/admin/listings/{id}/approve',      ['AdminListingController', 'approve']);
$router->post('/admin/listings/{id}/reject',       ['AdminListingController', 'reject']);
$router->post('/admin/listings/{id}/suspend',      ['AdminListingController', 'suspend']);
$router->post('/admin/listings/{id}/feature',      ['AdminListingController', 'feature']);
$router->post('/admin/listings/{id}/unfeature',    ['AdminListingController', 'unfeature']);

// Admin — owners
$router->get('/admin/owners',                    ['AdminOwnerController', 'index']);
$router->post('/admin/owners/{id}/verify',       ['AdminOwnerController', 'verify']);
$router->post('/admin/owners/{id}/unverify',     ['AdminOwnerController', 'unverify']);

// Admin — articles (fixed routes before parameterised)
$router->get('/admin/articles',                  ['AdminArticleController', 'index']);
$router->get('/admin/articles/create',           ['AdminArticleController', 'create']);
$router->post('/admin/articles/store',           ['AdminArticleController', 'store']);
$router->post('/admin/articles/upload-image',    ['AdminArticleController', 'uploadImage']);
$router->get('/admin/articles/{id}/edit',        ['AdminArticleController', 'edit']);
$router->post('/admin/articles/{id}/update',     ['AdminArticleController', 'update']);
$router->post('/admin/articles/{id}/delete',     ['AdminArticleController', 'delete']);
$router->post('/admin/articles/{id}/toggle',     ['AdminArticleController', 'toggle']);

// Owner — dashboard & profile
$router->get('/owner/dashboard',                ['OwnerController', 'dashboard']);
$router->get('/owner/profile',                  ['OwnerController', 'profile']);
$router->post('/owner/profile/update',          ['OwnerController', 'updateProfile']);
$router->post('/owner/profile/change-password', ['OwnerController', 'changePassword']);

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

// Featured listing purchase
$router->get('/feature/{listingId}',          ['PaymentController', 'showFeaturePage']);
$router->post('/feature/{listingId}/checkout',['PaymentController', 'checkout']);
$router->post('/payment/callback',            ['PaymentController', 'callback']);
$router->get('/payment/return',               ['PaymentController', 'returnPage']);

// Admin — featured packages
$router->get('/admin/featured-packages',                    ['AdminFeaturedPackageController', 'index']);
$router->get('/admin/featured-packages/{id}/edit',          ['AdminFeaturedPackageController', 'edit']);
$router->post('/admin/featured-packages/{id}/update',       ['AdminFeaturedPackageController', 'update']);

// Admin — verifications
$router->get('/admin/verifications',                        ['AdminVerificationController', 'index']);
$router->get('/admin/verifications/{id}/document',          ['AdminVerificationController', 'streamDocument']);
$router->get('/admin/verifications/{id}/selfie',            ['AdminVerificationController', 'streamSelfie']);
$router->post('/admin/verifications/{id}/approve',          ['AdminVerificationController', 'approve']);
$router->post('/admin/verifications/{id}/reject',           ['AdminVerificationController', 'reject']);

// Owner — Promotions
$router->get('/owner/listings/{id}/promotions',                        ['OwnerPromotionController', 'index']);
$router->post('/owner/listings/{id}/promotions',                       ['OwnerPromotionController', 'store']);
$router->post('/owner/listings/{id}/promotions/{promoId}/delete',      ['OwnerPromotionController', 'destroy']);
$router->post('/owner/listings/{id}/promotions/{promoId}/toggle',      ['OwnerPromotionController', 'toggle']);

// Owner — Availability & iCal
$router->get('/owner/listings/{id}/availability',                      ['OwnerAvailabilityController', 'index']);
$router->post('/owner/listings/{id}/availability/toggle',              ['OwnerAvailabilityController', 'toggle']);
$router->post('/owner/listings/{id}/availability/ical',                ['OwnerAvailabilityController', 'saveIcal']);
$router->post('/owner/listings/{id}/availability/sync',                ['OwnerAvailabilityController', 'syncIcal']);

// Verified Host — public landing
$router->get('/get-verified', ['VerificationController', 'showPage']);

// Verified Host — owner application
$router->get('/owner/verify',   ['VerificationController', 'showApplyForm']);
$router->post('/owner/verify',  ['VerificationController', 'submitApplication']);

// Verified Host — payment callbacks
$router->post('/payment/verify-callback', ['VerificationController', 'callback']);
$router->get('/payment/verify-return',    ['VerificationController', 'returnPage']);

// Public static pages
$router->get('/about',   ['PublicController', 'about']);
$router->get('/contact', ['PublicController', 'contact']);
$router->get('/terms',   ['PublicController', 'terms']);

// Public articles (fixed routes — must be before parameterised state/city)
$router->get('/articles',          ['PublicController', 'articles']);
$router->get('/articles/{slug}',   ['PublicController', 'articleDetail']);

// Public state/city pages (parameterised — must be last)
$router->get('/{stateSlug}',             ['PublicController', 'stateListings']);
$router->get('/{stateSlug}/{citySlug}',  ['PublicController', 'cityListings']);

$router->dispatch();
