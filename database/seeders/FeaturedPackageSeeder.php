<?php

define('ROOT_PATH', dirname(__DIR__, 2));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH',    ROOT_PATH . '/app');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';

$db = Database::get();

$count = (int) $db->query("SELECT COUNT(*) FROM featured_packages")->fetchColumn();
if ($count > 0) {
    echo "Featured packages already seeded. Skipping.\n";
    exit;
}

$packages = [
    ['label' => '7-Day Boost',   'days' => 7,  'normal_price' => 19.00, 'sort_order' => 1],
    ['label' => '14-Day Boost',  'days' => 14, 'normal_price' => 25.00, 'sort_order' => 2],
    ['label' => '30-Day Boost',  'days' => 30, 'normal_price' => 35.00, 'sort_order' => 3],
];

$stmt = $db->prepare("
    INSERT INTO featured_packages (label, days, normal_price, promo_price, is_active, sort_order)
    VALUES (?, ?, ?, NULL, 1, ?)
");

foreach ($packages as $p) {
    $stmt->execute([$p['label'], $p['days'], $p['normal_price'], $p['sort_order']]);
}

echo "Featured packages seeded: " . count($packages) . "\n";
