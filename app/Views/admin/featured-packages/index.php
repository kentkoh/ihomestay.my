<?php $pageTitle = 'Featured Packages'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Featured Packages</h5>
        <small class="text-muted">Prices sync automatically to the feature listing page.</small>
    </div>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
        <div class="alert alert-<?= $type ?> alert-dismissible fade show">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="row g-4">
    <?php foreach ($packages as $pkg): ?>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100" style="border-radius:16px;<?= !$pkg['is_active'] ? 'opacity:.6;' : '' ?>">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="fw-bold" style="color:#0f172a;"><?= htmlspecialchars($pkg['label']) ?></div>
                            <div class="text-muted small"><?= $pkg['days'] ?> days</div>
                        </div>
                        <span class="badge <?= $pkg['is_active'] ? 'bg-success' : 'bg-secondary' ?>">
                            <?= $pkg['is_active'] ? 'Active' : 'Inactive' ?>
                        </span>
                    </div>

                    <div class="mb-3">
                        <div class="fw-bold" style="font-size:1.6rem;color:#0f172a;">
                            RM<?= number_format($pkg['normal_price'], 2) ?>
                        </div>
                        <?php if ($pkg['promo_price'] !== null && $pkg['promo_price'] > 0): ?>
                            <div class="mt-1">
                                <span class="badge" style="background:#fef2f0;color:#e84c2b;">
                                    Promo: RM<?= number_format($pkg['promo_price'], 2) ?>
                                </span>
                            </div>
                        <?php else: ?>
                            <div class="text-muted small">No promo price set</div>
                        <?php endif; ?>
                    </div>

                    <a href="/admin/featured-packages/<?= $pkg['id'] ?>/edit"
                       class="btn btn-sm w-100" style="background:#0f1923;color:#fff;border-radius:8px;">
                        <i class="bi bi-pencil me-1"></i>Edit Package
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div class="mt-4 p-3 rounded-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
    <div class="d-flex gap-2 align-items-start">
        <i class="bi bi-info-circle-fill mt-1" style="color:#94a3b8;flex-shrink:0;"></i>
        <div class="small text-muted">
            Changes to prices and days take effect immediately on the public feature listing page.
            Promo price replaces the normal price — leave blank to remove the promotion.
            Existing paid orders are not affected by price changes.
        </div>
    </div>
</div>
