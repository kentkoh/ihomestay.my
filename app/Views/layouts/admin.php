<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin') ?> — iHomestay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background: #f1f5f9; margin: 0; }
        .admin-sidebar {
            width: 230px;
            min-height: 100vh;
            background: #1e293b;
            position: fixed;
            top: 0; left: 0;
            z-index: 100;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            padding: 1rem;
        }
        .admin-main { margin-left: 230px; min-height: 100vh; }
        .sidebar-brand { color: #e84c2b; font-weight: 700; font-size: 1rem; text-decoration: none; }
        .sidebar-brand:hover { color: #e84c2b; }
        .sidebar-label { color: #64748b; font-size: 0.68rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; padding: 0 8px; margin-bottom: 6px; }
        .sidebar-link {
            color: #94a3b8; text-decoration: none;
            display: flex; align-items: center; gap: 8px;
            padding: 7px 10px; border-radius: 6px;
            font-size: 0.875rem; transition: all 0.15s;
        }
        .sidebar-link:hover, .sidebar-link.active { color: #fff; background: rgba(255,255,255,0.08); }
        .sidebar-link i { font-size: 1rem; width: 18px; }
        .admin-topbar { background: #fff; border-bottom: 1px solid #e2e8f0; padding: 14px 24px; }
        .sidebar-divider { border-color: #334155; margin: 12px 0; }
    </style>
</head>
<body>

<div class="admin-sidebar">
    <a href="/admin/dashboard" class="sidebar-brand d-flex align-items-center gap-2 mb-4 px-2 py-1">
        <i class="bi bi-house-heart-fill"></i> iHomestay
    </a>

    <div class="sidebar-label">Main</div>
    <nav class="d-flex flex-column gap-1 mb-4">
        <a href="/admin/dashboard" class="sidebar-link <?= $_SERVER['REQUEST_URI'] === '/admin/dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
    </nav>

    <div class="sidebar-label">Management</div>
    <nav class="d-flex flex-column gap-1 mb-4">
        <a href="/admin/listings" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/listings') ? 'active' : '' ?>">
            <i class="bi bi-house-fill"></i> Listings
        </a>
        <a href="/admin/owners" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/owners') ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i> Owners
        </a>
        <a href="/admin/articles" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/articles') ? 'active' : '' ?>">
            <i class="bi bi-newspaper"></i> Articles
        </a>
        <a href="/admin/facilities" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/facilities') ? 'active' : '' ?>">
            <i class="bi bi-grid-3x3-gap-fill"></i> Facilities
        </a>
    </nav>

    <div class="sidebar-label">Monetisation</div>
    <nav class="d-flex flex-column gap-1 mb-4">
        <a href="/admin/featured-packages" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/featured-packages') ? 'active' : '' ?>">
            <i class="bi bi-star-fill"></i> Featured Packages
        </a>
        <a href="/admin/verifications" class="sidebar-link <?= str_starts_with($_SERVER['REQUEST_URI'], '/admin/verifications') ? 'active' : '' ?>">
            <i class="bi bi-patch-check-fill"></i> Verifications
            <?php
            $pendingVr = (int) Database::get()->query("SELECT COUNT(*) FROM verification_requests WHERE status='pending_review'")->fetchColumn();
            if ($pendingVr > 0): ?>
                <span class="badge rounded-pill ms-auto" style="background:#e84c2b;font-size:.65rem;"><?= $pendingVr ?></span>
            <?php endif; ?>
        </a>
    </nav>

    <div class="mt-auto">
        <hr class="sidebar-divider">
        <div class="sidebar-label mb-1"><?= htmlspecialchars(Auth::user()['name'] ?? '') ?></div>
        <a href="/logout" class="sidebar-link">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<div class="admin-main">
    <div class="admin-topbar d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fw-semibold"><?= htmlspecialchars($title ?? 'Admin') ?></h6>
        <span class="badge" style="background:#e84c2b;">Admin</span>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="px-4 pt-3">
            <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
                <div class="alert alert-<?= htmlspecialchars($type) ?> alert-dismissible fade show mb-0" role="alert">
                    <?= htmlspecialchars($msg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="p-4">
        <?= $content ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php if (!empty($extraScripts)) echo $extraScripts; ?>
</body>
</html>
