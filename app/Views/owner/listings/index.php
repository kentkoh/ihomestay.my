<?php
$statusColors = [
    'draft'     => 'secondary',
    'pending'   => 'warning',
    'published' => 'success',
    'rejected'  => 'danger',
    'suspended' => 'dark',
];
$user = Auth::user();
$isPro = $user['plan_type'] !== 'free';
?>

<div class="container py-4">
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show" role="alert">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-0">My Listings</h5>
            <?php if (!$isPro): ?>
                <small class="text-muted"><?= $count ?> / 3 listings used on free plan</small>
            <?php endif; ?>
        </div>
        <?php if ($isPro || $count < 3): ?>
            <a href="/owner/listings/create" class="btn btn-sm" style="background:#e84c2b;color:#fff;">
                <i class="bi bi-plus-lg me-1"></i> Add Listing
            </a>
        <?php else: ?>
            <button class="btn btn-sm btn-outline-secondary" disabled title="Upgrade to add more">
                <i class="bi bi-lock me-1"></i> Limit Reached
            </button>
        <?php endif; ?>
    </div>

    <?php if (!$isPro): ?>
        <div class="progress mb-4" style="height:6px;">
            <div class="progress-bar" style="width:<?= min(100, ($count / 3) * 100) ?>%;background:#e84c2b;"></div>
        </div>
    <?php endif; ?>

    <?php if (empty($listings)): ?>
        <div class="card border-0 shadow-sm text-center p-5">
            <i class="bi bi-house-add fs-1 text-muted mb-3"></i>
            <h6 class="text-muted">No listings yet</h6>
            <p class="text-muted small mb-3">Add your first homestay listing to get started.</p>
            <a href="/owner/listings/create" class="btn btn-sm" style="background:#e84c2b;color:#fff;">Add Your First Listing</a>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
            <?php foreach ($listings as $l): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex gap-3 align-items-start">
                            <?php if ($l['primary_image']): ?>
                                <img src="/uploads/listings/<?= $l['id'] ?>/<?= htmlspecialchars($l['primary_image']) ?>"
                                     class="rounded" style="width:80px;height:60px;object-fit:cover;" alt="">
                            <?php else: ?>
                                <div class="rounded bg-light d-flex align-items-center justify-content-center"
                                     style="width:80px;height:60px;flex-shrink:0;">
                                    <i class="bi bi-image text-muted fs-4"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1 min-width-0">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($l['title']) ?></div>
                                        <div class="text-muted small"><?= htmlspecialchars($l['city_name']) ?>, <?= htmlspecialchars($l['state_name']) ?> &mdash; RM<?= number_format($l['price_per_night'], 2) ?>/night</div>
                                    </div>
                                    <span class="badge bg-<?= $statusColors[$l['status']] ?? 'secondary' ?> text-capitalize">
                                        <?= $l['status'] ?>
                                    </span>
                                </div>
                                <?php if ($l['status'] === 'rejected' && $l['rejection_reason']): ?>
                                    <div class="alert alert-danger py-1 px-2 mt-2 mb-0 small">
                                        <strong>Rejected:</strong> <?= htmlspecialchars($l['rejection_reason']) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="d-flex gap-2 mt-2 flex-wrap">
                                    <?php if ($l['status'] === 'published'): ?>
                                        <?php
                                            $isFeatured = $l['is_featured'] && (!$l['featured_until'] || strtotime($l['featured_until']) > time());
                                            $ownerIsVerified = ($user['verification_status'] ?? '') === 'verified';
                                        ?>
                                        <?php if ($isFeatured): ?>
                                            <span class="btn btn-sm" style="background:#fef2f0;color:#e84c2b;border:1px solid #fca5a5;pointer-events:none;">
                                                <i class="bi bi-star-fill me-1"></i>Featured
                                                <?php if ($l['featured_until']): ?>
                                                    until <?= date('d M', strtotime($l['featured_until'])) ?>
                                                <?php endif; ?>
                                            </span>
                                        <?php elseif ($ownerIsVerified): ?>
                                            <a href="/feature/<?= $l['id'] ?>" class="btn btn-sm"
                                               style="background:#e84c2b;color:#fff;">
                                                <i class="bi bi-lightning-charge-fill me-1"></i>Feature This
                                            </a>
                                        <?php else: ?>
                                            <a href="/owner/profile" title="Verified Hosts only"
                                               class="btn btn-sm" style="background:#f1f5f9;color:#94a3b8;">
                                                <i class="bi bi-lock-fill me-1"></i>Feature This
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <a href="/owner/listings/<?= $l['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </a>
                                    <form method="POST" action="/owner/listings/<?= $l['id'] ?>/delete"
                                          onsubmit="return confirm('Delete this listing? This cannot be undone.')">
                                        <?= CSRF::field() ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
