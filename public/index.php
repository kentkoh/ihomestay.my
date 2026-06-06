<?php

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Router.php';

$router = new Router();

// Routes will be registered in Stage 1+
// Placeholder: show a basic status page for Stage 0 testing
$router->get('/', function () {
    echo '<h1>ihomestay.my — new.ihomestay.my</h1>';
    echo '<p>Stage 0: Project skeleton ready.</p>';
});

$router->dispatch();
