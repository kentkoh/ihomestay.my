<?php
/**
 * One-off fix: normalise WhatsApp numbers from 01xxxx → 601xxxx
 * Numbers already starting with 60 are untouched.
 * Run once: php database/fix_whatsapp_prefix.php
 */

define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');

require_once CONFIG_PATH . '/app.php';
require_once ROOT_PATH . '/app/Core/Database.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = Database::get();

// Preview first
$preview = $db->query(
    "SELECT id, name, whatsapp FROM users WHERE whatsapp LIKE '0%'"
)->fetchAll(PDO::FETCH_ASSOC);

if (empty($preview)) {
    echo "No numbers need fixing — all WhatsApp numbers already have the correct prefix.\n";
    exit;
}

echo "Numbers to be updated:\n";
foreach ($preview as $row) {
    $fixed = '6' . $row['whatsapp'];
    echo "  ID {$row['id']}  {$row['name']}:  {$row['whatsapp']}  →  {$fixed}\n";
}
echo "\nTotal: " . count($preview) . " record(s)\n\n";

// Apply fix
$stmt = $db->prepare(
    "UPDATE users SET whatsapp = CONCAT('6', whatsapp), updated_at = NOW()
     WHERE whatsapp LIKE '0%'"
);
$stmt->execute();
$affected = $stmt->rowCount();

echo "Done. {$affected} record(s) updated.\n";
