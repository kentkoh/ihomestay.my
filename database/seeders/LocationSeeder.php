<?php

define('ROOT_PATH', dirname(__DIR__, 2));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH', ROOT_PATH . '/app');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';

$pdo = Database::get();

function makeSlug(string $name): string {
    $slug = strtolower($name);
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-');
}

$count = (int) $pdo->query("SELECT COUNT(*) FROM states")->fetchColumn();
if ($count > 0) {
    echo "Locations already seeded. Skipping.\n";
    exit;
}

$statesAndCities = [
    'Johor'            => ['Johor Bahru', 'Batu Pahat', 'Muar', 'Kluang', 'Mersing', 'Kota Tinggi', 'Pontian', 'Kulai', 'Iskandar Puteri', 'Desaru'],
    'Kedah'            => ['Alor Setar', 'Sungai Petani', 'Kulim', 'Langkawi', 'Kuala Kedah', 'Baling'],
    'Kelantan'         => ['Kota Bharu', 'Rantau Panjang', 'Pasir Mas', 'Gua Musang', 'Tanah Merah', 'Bachok'],
    'Melaka'           => ['Melaka City', 'Ayer Keroh', 'Jasin', 'Merlimau', 'Alor Gajah'],
    'Negeri Sembilan'  => ['Seremban', 'Port Dickson', 'Nilai', 'Rembau', 'Tampin', 'Kuala Pilah'],
    'Pahang'           => ['Kuantan', 'Cameron Highlands', 'Genting Highlands', 'Bentong', 'Temerloh', 'Cherating', 'Pekan', 'Rompin', 'Raub', 'Jerantut'],
    'Perak'            => ['Ipoh', 'Taiping', 'Teluk Intan', 'Lumut', 'Pangkor', 'Manjung', 'Kuala Kangsar', 'Sitiawan', 'Batu Gajah', 'Gopeng'],
    'Perlis'           => ['Kangar', 'Padang Besar', 'Arau'],
    'Pulau Pinang'     => ['Georgetown', 'Batu Ferringhi', 'Butterworth', 'Balik Pulau', 'Ayer Itam', 'Tanjung Bungah'],
    'Sabah'            => ['Kota Kinabalu', 'Sandakan', 'Tawau', 'Lahad Datu', 'Keningau', 'Semporna', 'Kudat', 'Ranau', 'Kota Belud'],
    'Sarawak'          => ['Kuching', 'Miri', 'Sibu', 'Bintulu', 'Kota Samarahan', 'Sri Aman', 'Limbang', 'Lundu', 'Sarikei'],
    'Selangor'         => ['Shah Alam', 'Petaling Jaya', 'Subang Jaya', 'Klang', 'Ampang', 'Sepang', 'Gombak', 'Kajang', 'Puchong', 'Cyberjaya', 'Rawang', 'Kuala Selangor', 'Banting', 'Cheras', 'Balakong'],
    'Terengganu'       => ['Kuala Terengganu', 'Dungun', 'Kemaman', 'Marang', 'Setiu', 'Besut', 'Hulu Terengganu', 'Pulau Redang', 'Pulau Perhentian'],
    'Kuala Lumpur'     => ['Bukit Bintang', 'Chow Kit', 'Bangsar', 'Mont Kiara', 'Kepong', 'Cheras', 'Setapak', 'Wangsa Maju', 'Titiwangsa', 'Sentul', 'Desa ParkCity'],
    'Labuan'           => ['Labuan Town', 'Victoria'],
    'Putrajaya'        => ['Putrajaya'],
];

$pdo->beginTransaction();

$stateStmt = $pdo->prepare("INSERT INTO states (name, slug) VALUES (?, ?)");
$cityStmt  = $pdo->prepare("INSERT INTO cities (state_id, name, slug) VALUES (?, ?, ?)");

$stateCount = 0;
$cityCount  = 0;

foreach ($statesAndCities as $state => $cities) {
    $stateStmt->execute([$state, makeSlug($state)]);
    $stateId = (int) $pdo->lastInsertId();
    $stateCount++;

    foreach ($cities as $city) {
        $cityStmt->execute([$stateId, $city, makeSlug($city)]);
        $cityCount++;
    }
}

$pdo->commit();

echo "States seeded: $stateCount\n";
echo "Cities seeded: $cityCount\n";
echo "Done.\n";
