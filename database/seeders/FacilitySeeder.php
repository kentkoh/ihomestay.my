<?php

define('ROOT_PATH', dirname(__DIR__, 2));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';

$pdo = Database::get();

$count = (int) $pdo->query("SELECT COUNT(*) FROM facilities")->fetchColumn();
if ($count > 0) {
    echo "Facilities already seeded. Skipping.\n";
    exit;
}

$facilities = [
    ['category' => 'Internet & TV',        'name' => 'WiFi / Internet',            'sort_order' => 1],
    ['category' => 'Internet & TV',        'name' => 'Astro / Cable TV',           'sort_order' => 2],
    ['category' => 'Internet & TV',        'name' => 'Smart TV / Netflix',         'sort_order' => 3],
    ['category' => 'Internet & TV',        'name' => 'Work Desk',                  'sort_order' => 4],

    ['category' => 'Kitchen',              'name' => 'Full Kitchen',               'sort_order' => 1],
    ['category' => 'Kitchen',              'name' => 'Mini Kitchen / Pantry',      'sort_order' => 2],
    ['category' => 'Kitchen',              'name' => 'Refrigerator',               'sort_order' => 3],
    ['category' => 'Kitchen',              'name' => 'Microwave',                  'sort_order' => 4],
    ['category' => 'Kitchen',              'name' => 'Rice Cooker',                'sort_order' => 5],
    ['category' => 'Kitchen',              'name' => 'Kettle / Water Boiler',      'sort_order' => 6],
    ['category' => 'Kitchen',              'name' => 'Dining Table & Chairs',      'sort_order' => 7],
    ['category' => 'Kitchen',              'name' => 'Cooking Utensils & Crockery','sort_order' => 8],

    ['category' => 'Bedroom & Bathroom',   'name' => 'Air Conditioning',           'sort_order' => 1],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Ceiling Fan',                'sort_order' => 2],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Hot Water Shower',           'sort_order' => 3],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Bathtub',                    'sort_order' => 4],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Hair Dryer',                 'sort_order' => 5],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Iron & Ironing Board',       'sort_order' => 6],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Wardrobe / Closet',          'sort_order' => 7],
    ['category' => 'Bedroom & Bathroom',   'name' => 'Extra Towels & Linen',       'sort_order' => 8],

    ['category' => 'Outdoor & Recreation', 'name' => 'Swimming Pool',              'sort_order' => 1],
    ['category' => 'Outdoor & Recreation', 'name' => 'Private Pool',               'sort_order' => 2],
    ['category' => 'Outdoor & Recreation', 'name' => 'BBQ Pit / Grill',            'sort_order' => 3],
    ['category' => 'Outdoor & Recreation', 'name' => 'Garden / Yard',              'sort_order' => 4],
    ['category' => 'Outdoor & Recreation', 'name' => 'Balcony / Terrace',          'sort_order' => 5],
    ['category' => 'Outdoor & Recreation', 'name' => 'Playground',                 'sort_order' => 6],

    ['category' => 'Parking',              'name' => 'Free Parking (On-site)',     'sort_order' => 1],
    ['category' => 'Parking',              'name' => 'Street Parking Available',   'sort_order' => 2],

    ['category' => 'Safety & Security',    'name' => 'CCTV',                       'sort_order' => 1],
    ['category' => 'Safety & Security',    'name' => 'Gated & Guarded',            'sort_order' => 2],
    ['category' => 'Safety & Security',    'name' => 'Smart Door Lock',            'sort_order' => 3],
    ['category' => 'Safety & Security',    'name' => 'First Aid Kit',              'sort_order' => 4],
    ['category' => 'Safety & Security',    'name' => 'Fire Extinguisher',          'sort_order' => 5],

    ['category' => 'Family Friendly',      'name' => 'Baby Cot',                   'sort_order' => 1],
    ['category' => 'Family Friendly',      'name' => 'High Chair',                 'sort_order' => 2],
    ['category' => 'Family Friendly',      'name' => 'Kids Play Area',             'sort_order' => 3],

    ['category' => 'Laundry',              'name' => 'Washing Machine',            'sort_order' => 1],
    ['category' => 'Laundry',              'name' => 'Dryer',                      'sort_order' => 2],

    ['category' => 'Muslim Friendly',      'name' => 'Prayer Room / Surau',        'sort_order' => 1],
    ['category' => 'Muslim Friendly',      'name' => 'Halal Kitchen',              'sort_order' => 2],
    ['category' => 'Muslim Friendly',      'name' => 'Muslim-Friendly',            'sort_order' => 3],
];

$pdo->beginTransaction();

$stmt = $pdo->prepare(
    "INSERT INTO facilities (name, category, sort_order, is_active) VALUES (?, ?, ?, 1)"
);

foreach ($facilities as $f) {
    $stmt->execute([$f['name'], $f['category'], $f['sort_order']]);
}

$pdo->commit();

echo "Facilities seeded: " . count($facilities) . "\n";
echo "Done.\n";
