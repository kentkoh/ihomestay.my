<?php

define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Models/Listing.php';
require_once APP_PATH . '/Models/ListingBlockedDate.php';

$db   = Database::get();
$rows = $db->query(
    "SELECT id, ical_import_url FROM listings
     WHERE ical_import_url IS NOT NULL AND ical_import_url != '' AND status = 'published'"
)->fetchAll(PDO::FETCH_ASSOC);

$ok  = 0;
$err = 0;

foreach ($rows as $row) {
    $synced = ListingBlockedDate::syncFromIcal((int) $row['id'], $row['ical_import_url']);
    if ($synced) {
        $ok++;
    } else {
        $err++;
        error_log('[iCal sync] Failed for listing ' . $row['id'] . ' — ' . $row['ical_import_url']);
    }
}

echo date('Y-m-d H:i:s') . " — iCal sync done. OK: $ok, Failed: $err\n";
