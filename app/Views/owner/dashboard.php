<?php
$pageTitle = 'Owner Dashboard';
$listings  = Listing::byOwner(Auth::id());
$total     = count($listings);
$published = count(array_filter($listings, fn($l) => $l['status'] === 'published'));
$pending   = count(array_filter($listings, fn($l) => $l['status'] === 'pending'));
$isPro     = Auth::user()['plan_type'] !== 'free';
ob_start();
?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">My Dashboard</h4>
        <span class="badge bg-secondary"><?= $isPro ? 'Pro Owner' : 'Free Owner' ?></span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold" style="color:#e84c2b;"><?= $total ?></div>
                <div class="text-muted small">Total Listings</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-success"><?= $published ?></div>
                <div class="text-muted small">Published</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-warning"><?= $pending ?></div>
                <div class="text-muted small">Pending Review</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-secondary"><?= $isPro ? '∞' : '3' ?></div>
                <div class="text-muted small">Listing Limit</div>
            </div>
        </div>
    </div>

    <?php if (!$isPro): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <small class="fw-semibold">Free Plan Usage</small>
                    <small class="text-muted"><?= $total ?> / 3 listings</small>
                </div>
                <div class="progress mb-2" style="height:8px;">
                    <div class="progress-bar" style="width:<?= min(100, ($total / 3) * 100) ?>%;background:#e84c2b;"></div>
                </div>
                <?php if ($total >= 3): ?>
                    <p class="text-muted small mb-0">Limit reached. Contact us to upgrade your account.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Quick Actions</h6>
            <div class="d-flex flex-wrap gap-2">
                <?php if ($isPro || $total < 3): ?>
                    <a href="/owner/listings/create" class="btn btn-sm" style="background:#e84c2b;color:#fff;">
                        <i class="bi bi-plus-lg me-1"></i> Add New Listing
                    </a>
                <?php endif; ?>
                <a href="/owner/listings" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-house me-1"></i> My Listings
                </a>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/main.php'; ?>
