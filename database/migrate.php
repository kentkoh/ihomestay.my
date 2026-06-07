<?php
/**
 * Migration Runner
 * Usage: php database/migrate.php
 * Run from the project root directory.
 */

define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/app.php';
require_once ROOT_PATH . '/app/Core/Database.php';

// Force error visibility in CLI regardless of APP_DEBUG setting
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = Database::get();

// Create migrations log table first
$db->exec("
    CREATE TABLE IF NOT EXISTS migrations_log (
        id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        filename VARCHAR(255) NOT NULL UNIQUE,
        run_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
");

// Get already-run migrations
$stmt = $db->query('SELECT filename FROM migrations_log');
$ran  = array_column($stmt->fetchAll(), 'filename');

// Get all SQL migration files in order
$files = glob(ROOT_PATH . '/database/migrations/*.sql');
sort($files);

$count = 0;
foreach ($files as $file) {
    $filename = basename($file);

    if (in_array($filename, $ran)) {
        echo "  [skip] $filename\n";
        continue;
    }

    $sql = file_get_contents($file);
    try {
        $db->exec($sql);
    } catch (PDOException $e) {
        echo "  [ERROR] $filename: " . $e->getMessage() . "\n";
        exit(1);
    }

    $stmt = $db->prepare('INSERT INTO migrations_log (filename, run_at) VALUES (?, ?)');
    $stmt->execute([$filename, date('Y-m-d H:i:s')]);

    echo "  [done] $filename\n";
    $count++;
}

echo "\nMigration complete. $count new migration(s) run.\n";
