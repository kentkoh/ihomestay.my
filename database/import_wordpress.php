<?php
/**
 * WordPress/HivePress → ihomestay.my one-off migration script.
 * Run ONCE on cPanel terminal from project root:
 *   php database/import_wordpress.php
 *
 * Safe to re-run — skips users/listings that already exist.
 */

define('ROOT_PATH', dirname(__DIR__));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('APP_PATH',    ROOT_PATH . '/app');
define('UPLOAD_PATH', ROOT_PATH . '/public/uploads');

require_once CONFIG_PATH . '/app.php';
require_once APP_PATH . '/Core/Database.php';

// ─── OLD WORDPRESS DB CREDENTIALS ────────────────────────────────────────────
// Fill these in before running. Get the password from cPanel → MySQL Databases.
$WP_DB_HOST      = 'localhost';
$WP_DB_NAME      = 'kuantan1_wp615';
$WP_DB_USER      = 'kuantan1_wp615';
$WP_DB_PASS      = 'FILL_IN_PASSWORD_HERE';
$WP_PREFIX       = 'wp3c_';
$WP_UPLOADS_BASE = 'http://ihomestay.my/wp-content/uploads';
// ─────────────────────────────────────────────────────────────────────────────

if ($WP_DB_PASS === 'FILL_IN_PASSWORD_HERE') {
    die("ERROR: Please set \$WP_DB_PASS in the script before running.\n");
}

try {
    $wp = new PDO(
        "mysql:host={$WP_DB_HOST};dbname={$WP_DB_NAME};charset=utf8mb4",
        $WP_DB_USER,
        $WP_DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (Exception $e) {
    die("Cannot connect to WordPress DB: " . $e->getMessage() . "\n");
}

$db = Database::get();

echo "\n=== ihomestay.my WordPress Migration ===\n";
echo date('Y-m-d H:i:s') . "\n\n";

// ─── LOAD REFERENCE DATA FROM NEW DB ─────────────────────────────────────────

$states = [];
foreach ($db->query("SELECT id, name FROM states")->fetchAll() as $r) {
    $states[strtolower($r['name'])] = (int) $r['id'];
}

$cities = [];
foreach ($db->query("SELECT id, name, state_id FROM cities")->fetchAll() as $r) {
    $cities[strtolower($r['name'])] = ['id' => (int) $r['id'], 'state_id' => (int) $r['state_id']];
}

$facilities = [];
foreach ($db->query("SELECT id, name FROM facilities")->fetchAll() as $r) {
    $facilities[strtolower($r['name'])] = (int) $r['id'];
}

// ─── HELPERS ─────────────────────────────────────────────────────────────────

function wpMeta(PDO $wp, string $pfx, int $postId): array
{
    $s = $wp->prepare("SELECT meta_key, meta_value FROM {$pfx}postmeta WHERE post_id = ?");
    $s->execute([$postId]);
    $out = [];
    foreach ($s->fetchAll() as $r) {
        $out[$r['meta_key']] = $r['meta_value'];
    }
    return $out;
}

function wpUserMeta(PDO $wp, string $pfx, int $userId): array
{
    $s = $wp->prepare("SELECT meta_key, meta_value FROM {$pfx}usermeta WHERE user_id = ?");
    $s->execute([$userId]);
    $out = [];
    foreach ($s->fetchAll() as $r) {
        $out[$r['meta_key']] = $r['meta_value'];
    }
    return $out;
}

function wpTerms(PDO $wp, string $pfx, int $postId): array
{
    $s = $wp->prepare("
        SELECT t.name, tt.taxonomy
        FROM {$pfx}terms t
        JOIN {$pfx}term_taxonomy tt ON t.term_id = tt.term_id
        JOIN {$pfx}term_relationships tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
        WHERE tr.object_id = ?
    ");
    $s->execute([$postId]);
    $out = [];
    foreach ($s->fetchAll() as $r) {
        $out[$r['taxonomy']][] = $r['name'];
    }
    return $out;
}

function wpAttachments(PDO $wp, string $pfx, int $postId): array
{
    $s = $wp->prepare("
        SELECT p.ID, p.guid,
               pm.meta_value AS file_path
        FROM {$pfx}posts p
        LEFT JOIN {$pfx}postmeta pm
               ON p.ID = pm.post_id AND pm.meta_key = '_wp_attached_file'
        WHERE p.post_parent = ?
          AND p.post_type = 'attachment'
          AND p.post_mime_type LIKE 'image/%'
        ORDER BY p.menu_order ASC, p.ID ASC
    ");
    $s->execute([$postId]);
    return $s->fetchAll();
}

function resolveState(string $location, array $states): ?int
{
    $map = [
        'johor'           => ['johor'],
        'kedah'           => ['kedah'],
        'kelantan'        => ['kelantan'],
        'melaka'          => ['melaka', 'malacca'],
        'negeri sembilan' => ['negeri sembilan', 'n.sembilan', 'n sembilan'],
        'pahang'          => ['pahang'],
        'perak'           => ['perak'],
        'perlis'          => ['perlis'],
        'pulau pinang'    => ['pulau pinang', 'penang', 'p.pinang'],
        'sabah'           => ['sabah'],
        'sarawak'         => ['sarawak'],
        'selangor'        => ['selangor'],
        'terengganu'      => ['terengganu', 'trengganu'],
        'w.p. kuala lumpur' => ['kuala lumpur', 'kl,', ' kl '],
        'w.p. putrajaya'  => ['putrajaya'],
        'w.p. labuan'     => ['labuan'],
    ];

    $loc = strtolower($location);
    foreach ($map as $stateKey => $keywords) {
        foreach ($keywords as $kw) {
            if (str_contains($loc, $kw)) {
                if (isset($states[$stateKey])) {
                    return $states[$stateKey];
                }
                // Fuzzy: pick any state whose name contains the key
                foreach ($states as $sName => $sId) {
                    if (str_contains($sName, $stateKey) || str_contains($stateKey, $sName)) {
                        return $sId;
                    }
                }
            }
        }
    }
    return null;
}

function resolveCity(string $location, array $cities, int $stateId): ?int
{
    $loc = strtolower($location);
    // Prefer longer city name matches to avoid false positives
    $candidates = array_filter($cities, fn($c) => $c['state_id'] === $stateId);
    uasort($candidates, fn($a, $b) => strlen((string)array_search($a, $candidates)) <=> strlen((string)array_search($b, $candidates)));

    foreach ($candidates as $cName => $cData) {
        if (str_contains($loc, $cName)) {
            return $cData['id'];
        }
    }
    return null;
}

function extractPostcode(string $location): string
{
    return preg_match('/\b(\d{5})\b/', $location, $m) ? $m[1] : '';
}

function makeSlug(string $title, int $id): string
{
    $s = strtolower(trim($title));
    $s = preg_replace('/[^a-z0-9\s-]/', '', $s);
    $s = preg_replace('/[\s-]+/', '-', $s);
    return trim($s, '-') . '-' . $id;
}

function priceFromRange(array $rangeTerms): float
{
    $map = [
        '150' => 150, '300' => 300, '450' => 450,
        '600' => 600, '750' => 750, 'keatas' => 750,
    ];
    $best = 0;
    foreach ($rangeTerms as $r) {
        $r = strtolower($r);
        foreach ($map as $needle => $val) {
            if (str_contains($r, (string) $needle) && $val > $best) {
                $best = $val;
            }
        }
    }
    return (float) $best;
}

function fetchImage(string $url, string $destPath): bool
{
    $dir = dirname($destPath);
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        return false;
    }
    $ctx  = stream_context_create(['http' => ['timeout' => 20, 'user_agent' => 'Mozilla/5.0 ihomestay-migration/1.0']]);
    $data = @file_get_contents($url, false, $ctx);
    if ($data === false || strlen($data) < 100) {
        return false;
    }
    return file_put_contents($destPath, $data) !== false;
}

// ─── STEP 1: FETCH ALL PUBLISHED HP_LISTINGS ─────────────────────────────────

$wpListings = $wp->query("
    SELECT ID, post_title, post_content, post_excerpt, post_author, post_date
    FROM   {$WP_PREFIX}posts
    WHERE  post_type = 'hp_listing' AND post_status = 'publish'
    ORDER  BY ID ASC
")->fetchAll();

$total    = count($wpListings);
$imported = 0;
$skipped  = 0;
$warnings = [];
$userMap  = []; // wp_user_id => new_user_id

echo "Found {$total} published hp_listing posts in WordPress.\n\n";

// ─── DEFAULT STATE FALLBACK (Pahang — most listings are there) ────────────────
$defaultStateId = $states['pahang'] ?? array_values($states)[0];
$defaultCityId  = null;
foreach ($cities as $cData) {
    if ($cData['state_id'] === $defaultStateId) {
        $defaultCityId = $cData['id'];
        break;
    }
}

// ─── STEP 2: ITERATE LISTINGS ─────────────────────────────────────────────────

foreach ($wpListings as $listing) {
    $wpId  = (int) $listing['ID'];
    $title = trim($listing['post_title']);

    if ($title === '') {
        echo "  [skip] WP #{$wpId} — empty title\n";
        $skipped++;
        continue;
    }

    echo "  [{$wpId}] {$title}\n";

    $meta  = wpMeta($wp, $WP_PREFIX, $wpId);
    $terms = wpTerms($wp, $WP_PREFIX, $wpId);

    // ── Location ──────────────────────────────────────────────────────────────
    $locationStr = trim($meta['hp_location'] ?? '');

    $stateId = resolveState($locationStr, $states);
    if (!$stateId) {
        // Try hp_listing_negeri taxonomy term
        $negeri = $terms['hp_listing_negeri'][0] ?? '';
        if ($negeri) {
            $stateId = resolveState($negeri, $states);
        }
    }
    if (!$stateId) {
        $warnings[] = "WP #{$wpId} '{$title}': could not resolve state from '{$locationStr}'";
        echo "    [warn] State not resolved — using default (Pahang)\n";
        $stateId = $defaultStateId;
    }

    $cityId = resolveCity($locationStr, $cities, $stateId);
    if (!$cityId) {
        // Pick first city in resolved state
        foreach ($cities as $cData) {
            if ($cData['state_id'] === $stateId) {
                $cityId = $cData['id'];
                break;
            }
        }
    }
    $cityId   = $cityId ?: $defaultCityId;
    $postcode = extractPostcode($locationStr);

    // ── Price ─────────────────────────────────────────────────────────────────
    if (!empty($meta['hp_price']) && is_numeric($meta['hp_price'])) {
        $price = (float) $meta['hp_price'];
    } else {
        $price = priceFromRange($terms['hp_listing_price'] ?? []);
        if ($price === 0.0) {
            $price = 100.00; // safe placeholder if no price data at all
            $warnings[] = "WP #{$wpId} '{$title}': no price found — set to RM 100";
        }
    }

    // ── Owner user ────────────────────────────────────────────────────────────
    $wpUserId = (int) $listing['post_author'];

    if (!isset($userMap[$wpUserId])) {
        $su = $wp->prepare("SELECT * FROM {$WP_PREFIX}users WHERE ID = ?");
        $su->execute([$wpUserId]);
        $wpUser = $su->fetch();

        if (!$wpUser) {
            echo "    [skip] WP user #{$wpUserId} not found\n";
            $skipped++;
            continue;
        }

        // hp_verified is per-listing in postmeta; ownership applies to the user
        $isVerified        = ($meta['hp_verified'] ?? 0) == 1;
        $verificationStatus = $isVerified ? 'verified' : 'unverified';

        // WhatsApp: prefer listing meta, fall back to user meta
        $wpUserMeta = wpUserMeta($wp, $WP_PREFIX, $wpUserId);
        $whatsapp   = ltrim($meta['hp_whatsapp'] ?? $wpUserMeta['hp_whatsapp'] ?? '', '+0');
        $whatsapp   = $whatsapp ? '0' . $whatsapp : '';

        // Check if user already imported
        $eCheck = $db->prepare("SELECT id FROM users WHERE email = ? OR old_wp_user_id = ?");
        $eCheck->execute([$wpUser['user_email'], $wpUserId]);
        $existing = $eCheck->fetch();

        if ($existing) {
            $newUserId = (int) $existing['id'];
            echo "    [user] Matched existing user #{$newUserId} ({$wpUser['user_email']})\n";
        } else {
            $displayName = $wpUser['display_name'] ?: $wpUser['user_login'];
            $tempPw      = password_hash('iHomestay@2024', PASSWORD_BCRYPT);

            $ins = $db->prepare("
                INSERT INTO users
                    (old_wp_user_id, name, email, password, whatsapp, role,
                     verification_status, plan_type, password_reset_required,
                     status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, 'owner', ?, 'free', 1, 'active', ?, ?)
            ");
            $ins->execute([
                $wpUserId, $displayName, $wpUser['user_email'], $tempPw,
                $whatsapp, $verificationStatus,
                $wpUser['user_registered'], $wpUser['user_registered'],
            ]);
            $newUserId = (int) $db->lastInsertId();

            $db->prepare("
                INSERT INTO owner_profiles (user_id, created_at, updated_at)
                VALUES (?, NOW(), NOW())
            ")->execute([$newUserId]);

            echo "    [user] Created user #{$newUserId} ({$wpUser['user_email']})\n";
        }

        $userMap[$wpUserId] = $newUserId;
    }

    $newUserId = $userMap[$wpUserId];

    // ── Skip duplicate listing ────────────────────────────────────────────────
    $dupCheck = $db->prepare("SELECT id FROM listings WHERE owner_id = ? AND title = ?");
    $dupCheck->execute([$newUserId, $title]);
    if ($dupCheck->fetch()) {
        echo "    [skip] Already imported\n";
        $skipped++;
        continue;
    }

    // ── Listing fields ────────────────────────────────────────────────────────
    $description = strip_tags(trim($listing['post_content'] ?: $listing['post_excerpt'] ?: ''));
    if ($description === '') {
        $description = $title;
    }

    $bedrooms   = max(1, (int) ($meta['hp_bilik']   ?? 1));
    $bathrooms  = max(1, (int) ($meta['hp_tandas']  ?? 1));
    $maxGuests  = max(1, (int) ($meta['hp_tetamu']  ?? 2));
    $lat        = isset($meta['hp_latitude'])  && $meta['hp_latitude']  !== '' ? (float) $meta['hp_latitude']  : null;
    $lng        = isset($meta['hp_longitude']) && $meta['hp_longitude'] !== '' ? (float) $meta['hp_longitude'] : null;
    $isFeatured = ($meta['hp_featured'] ?? 0) == 1 ? 1 : 0;
    $listingWa  = ltrim($meta['hp_whatsapp'] ?? '', '+');

    // ── Insert listing ────────────────────────────────────────────────────────
    $db->prepare("
        INSERT INTO listings
            (owner_id, title, slug, description, address, state_id, city_id, postcode,
             latitude, longitude, price_per_night, max_guests, bedrooms, bathrooms,
             whatsapp, status, is_featured, created_at, updated_at)
        VALUES
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'published', ?, ?, ?)
    ")->execute([
        $newUserId, $title, 'tmp-' . $wpId, $description,
        $locationStr ?: 'Malaysia', $stateId, $cityId, $postcode,
        $lat, $lng, $price, $maxGuests, $bedrooms, $bathrooms,
        $listingWa, $isFeatured,
        $listing['post_date'], $listing['post_date'],
    ]);

    $newListingId = (int) $db->lastInsertId();
    $slug = makeSlug($title, $newListingId);
    $db->prepare("UPDATE listings SET slug = ? WHERE id = ?")->execute([$slug, $newListingId]);

    // ── Facilities ────────────────────────────────────────────────────────────
    $toLink = [];

    // Swimming pool
    if (in_array('ADA', $terms['hp_listing_kolam'] ?? [], true)) {
        if ($fid = ($facilities['swimming pool'] ?? null)) {
            $toLink[] = $fid;
        }
    }

    // BBQ
    if (in_array('Boleh', $terms['hp_listing_bbq'] ?? [], true)) {
        if ($fid = ($facilities['bbq pit / grill'] ?? null)) {
            $toLink[] = $fid;
        }
    }

    // Parking
    if (!empty($meta['hp_parking']) && (int) $meta['hp_parking'] > 0) {
        if ($fid = ($facilities['free parking (on-site)'] ?? null)) {
            $toLink[] = $fid;
        }
    }

    $insFac = $db->prepare("INSERT IGNORE INTO listing_facilities (listing_id, facility_id) VALUES (?, ?)");
    foreach ($toLink as $fid) {
        $insFac->execute([$newListingId, $fid]);
    }

    // ── Images ────────────────────────────────────────────────────────────────
    $attachments = wpAttachments($wp, $WP_PREFIX, $wpId);
    $thumbnailId = (int) ($meta['_thumbnail_id'] ?? 0);
    $destDir     = UPLOAD_PATH . '/listings/' . $newListingId . '/';
    $imgOrder    = 0;

    $insImg = $db->prepare("
        INSERT INTO listing_images (listing_id, filename, is_primary, sort_order, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    foreach ($attachments as $att) {
        $filePath = trim($att['file_path'] ?? '');
        $guid     = trim($att['guid'] ?? '');

        $srcUrl = $filePath
            ? rtrim($WP_UPLOADS_BASE, '/') . '/' . ltrim($filePath, '/')
            : $guid;

        if (empty($srcUrl)) {
            continue;
        }

        $ext      = strtolower(pathinfo(parse_url($srcUrl, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
        $filename = uniqid('img_') . '.' . $ext;
        $destFull = $destDir . $filename;

        echo "    [img] " . basename($srcUrl);

        if (fetchImage($srcUrl, $destFull)) {
            $isPrimary = ((int) $att['ID'] === $thumbnailId || $imgOrder === 0) ? 1 : 0;
            // Only one primary — clear flag after first set
            if ($isPrimary && $imgOrder > 0 && (int) $att['ID'] !== $thumbnailId) {
                $isPrimary = 0;
            }
            $insImg->execute([$newListingId, $filename, $isPrimary, $imgOrder]);
            echo " ✓\n";
            $imgOrder++;
        } else {
            echo " ✗ (download failed)\n";
            $warnings[] = "WP #{$wpId} img {$srcUrl}: download failed";
        }
    }

    // Ensure exactly one primary image
    if ($imgOrder > 0 && $thumbnailId > 0) {
        // The primary flag logic above handles it; ensure no duplicates
        $db->prepare("
            UPDATE listing_images SET is_primary = 0 WHERE listing_id = ?
        ")->execute([$newListingId]);
        // Set primary by filename order (first image)
        $firstImg = $db->prepare("
            SELECT id FROM listing_images WHERE listing_id = ? ORDER BY sort_order ASC LIMIT 1
        ");
        $firstImg->execute([$newListingId]);
        if ($fi = $firstImg->fetch()) {
            $db->prepare("UPDATE listing_images SET is_primary = 1 WHERE id = ?")
               ->execute([$fi['id']]);
        }
    }

    echo "    [ok] New listing #{$newListingId} — {$imgOrder} image(s), {$bedrooms}br {$bathrooms}ba RM{$price}/night\n\n";
    $imported++;
}

// ─── SUMMARY ──────────────────────────────────────────────────────────────────

echo "\n=== Migration Complete — " . date('H:i:s') . " ===\n";
echo "  Imported  : {$imported}\n";
echo "  Skipped   : {$skipped}\n";
echo "  WP Users  : " . count($userMap) . "\n";

if ($warnings) {
    echo "\n--- Warnings (" . count($warnings) . ") ---\n";
    foreach ($warnings as $w) {
        echo "  ! {$w}\n";
    }
}

echo "\nNOTE: All imported owners have password_reset_required=1.\n";
echo "      Temp password for all imported accounts: iHomestay@2024\n";
echo "      They will be prompted to change it on first login.\n\n";
echo "Done.\n";
