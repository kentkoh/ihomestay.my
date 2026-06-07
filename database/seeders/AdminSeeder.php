<?php
/**
 * Admin Seeder — run once to create the first admin account.
 * Usage: php database/seeders/AdminSeeder.php
 * Run from the project root directory.
 */

define('ROOT_PATH', dirname(__DIR__, 2));
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/app.php';
require_once ROOT_PATH . '/app/Core/Database.php';

$db = Database::get();

$email    = 'admin@ihomestay.my';
$password = 'Admin@1234';        // Change this immediately after first login
$name     = 'Admin';

// Check if already exists
$stmt = $db->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin account already exists.\n";
    exit;
}

$now = date('Y-m-d H:i:s');
$stmt = $db->prepare('
    INSERT INTO users (name, email, password, role, verification_status, status, created_at, updated_at)
    VALUES (?, ?, ?, "admin", "verified", "active", ?, ?)
');
$stmt->execute([
    $name,
    $email,
    password_hash($password, PASSWORD_DEFAULT),
    $now,
    $now,
]);

echo "Admin account created.\n";
echo "Email:    $email\n";
echo "Password: $password\n";
echo "IMPORTANT: Change this password immediately after first login.\n";
