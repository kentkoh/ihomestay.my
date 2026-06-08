<?php
$statusColors = [
    'draft'     => ['bg'=>'#f1f5f9','color'=>'#64748b','label'=>'Draft'],
    'pending'   => ['bg'=>'#fffbeb','color'=>'#d97706','label'=>'Pending Review'],
    'published' => ['bg'=>'#f0fdf4','color'=>'#16a34a','label'=>'Published'],
    'rejected'  => ['bg'=>'#fef2f2','color'=>'#dc2626','label'=>'Rejected'],
    'suspended' => ['bg'=>'#f8fafc','color'=>'#475569','label'=>'Suspended'],
];
?>
<style>
.dash-stat{border-radius:16px;padding:1.25rem 1.5rem;border:none;}
.listing-row{border-radius:12px;transition:box-shadow .15s;}
.listing-row:hover{box-shadow:0 4px 20px rgba(0,0,0,.08);}
.verify-banner{background:linear-gradient(135deg,#0f1923 0%,#1e293b 100%);border-radius:16px;padding:1.75rem 2rem;position:relative;overflow:hidden;}
.verify-banner::before{content:'';position:absolute;right:-60px;top:-60px;width:220px;height:220px;border-radius:50%;background:rgba(232,76,43,.15);}
</style>

<div class="container py-4">

    <!-- Welcome Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0" style="color:#0f172a;">
                Welcome back, <?= htmlspecialchars(explode(' ', $user['name'])[0]) ?> 👋
            </h4>
            <div class="text-muted small mt-1">Here's an overview of your homestay listings.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <?php if ($isVerified): ?>
                <span class="badge px-3 py-2" style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:.8rem;">
                    <i class="bi bi-patch-check-fill me-1"></i>Verified Host
                </span>
            <?php else: ?>
                <span class="badge px-3 py-2" style="background:#f8fafc;color:#64748b;border:1px solid #e2e8f0;font-size:.8rem;">
                    <i class="bi bi-person me-1"></i>Free Owner
                </span>
            <?php endif; ?>
            <?php if ($isPro || $total < 3): ?>
                <a href="/owner/listings/create" class="btn btn-sm fw-semibold"
                   style="background:#e84c2b;color:#fff;border-radius:8px;">
                    <i class="bi bi-plus-lg me-1"></i>Add Listing
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Flash messages -->
    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show mb-3">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="dash-stat shadow-sm" style="background:#fff;border-left:4px solid #e84c2b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size:2rem;color:#0f172a;line-height:1;"><?= $total ?></div>
                        <div class="text-muted small mt-1">Total Listings</div>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:#fef2f0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-house-fill" style="color:#e84c2b;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat shadow-sm" style="background:#fff;border-left:4px solid #22c55e;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size:2rem;color:#0f172a;line-height:1;"><?= $published ?></div>
                        <div class="text-muted small mt-1">Live Now</div>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-circle-fill" style="color:#22c55e;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat shadow-sm" style="background:#fff;border-left:4px solid #f59e0b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size:2rem;color:#0f172a;line-height:1;"><?= $pending ?></div>
                        <div class="text-muted small mt-1">Pending Review</div>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-fill" style="color:#f59e0b;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="dash-stat shadow-sm" style="background:#fff;border-left:4px solid #8b5cf6;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-bold" style="font-size:2rem;color:#0f172a;line-height:1;"><?= $featured ?></div>
                        <div class="text-muted small mt-1">Featured Active</div>
                    </div>
                    <div style="width:42px;height:42px;border-radius:12px;background:#f5f3ff;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-star-fill" style="color:#8b5cf6;font-size:1.2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verified Host CTA (non-verified only) -->
    <?php if (!$isVerified): ?>
    <div class="verify-banner mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge px-2 py-1" style="background:rgba(232,76,43,.3);color:#fca5a5;font-size:.72rem;letter-spacing:.06em;">UPGRADE</span>
                </div>
                <h5 class="fw-bold mb-1" style="color:#fff;">Become a <span style="color:#e84c2b;">Verified Host</span></h5>
                <p style="color:#94a3b8;font-size:.9rem;margin-bottom:1rem;max-width:480px;line-height:1.7;">
                    Get a verified badge, appear higher in search, let guests contact you directly via WhatsApp, and unlock featured listing boosts.
                </p>
                <div class="d-flex flex-wrap gap-3" style="font-size:.82rem;color:#64748b;">
                    <span><i class="bi bi-patch-check-fill me-1" style="color:#22c55e;"></i>Verified badge on all listings</span>
                    <span><i class="bi bi-whatsapp me-1" style="color:#25d366;"></i>Direct WhatsApp contact</span>
                    <span><i class="bi bi-graph-up-arrow me-1" style="color:#e84c2b;"></i>Higher search ranking</span>
                    <span><i class="bi bi-star-fill me-1" style="color:#f59e0b;"></i>Featured listing eligible</span>
                </div>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="/get-verified" class="btn fw-bold px-4 py-2"
                   style="background:#e84c2b;color:#fff;border-radius:10px;font-size:.95rem;">
                    <i class="bi bi-patch-check me-2"></i>Get Verified — RM49/yr
                </a>
                <div style="color:#475569;font-size:.75rem;margin-top:.5rem;">Free · Reviewed within 24 hours</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Free plan usage bar -->
    <?php if (!$isPro): ?>
    <div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
        <div class="card-body py-3 px-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small fw-semibold" style="color:#0f172a;">Free Plan — Listing Slots</span>
                <span class="small text-muted"><?= $total ?> / 3 used</span>
            </div>
            <div class="progress" style="height:6px;border-radius:99px;">
                <div class="progress-bar" style="width:<?= min(100, ($total/3)*100) ?>%;background:#e84c2b;border-radius:99px;"></div>
            </div>
            <?php if ($total >= 3): ?>
                <div class="text-muted small mt-2">Listing limit reached. Contact us to unlock more slots.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- My Listings -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0" style="color:#0f172a;">My Listings</h6>
        <a href="/owner/listings" class="btn btn-sm btn-outline-secondary" style="border-radius:8px;font-size:.8rem;">Manage All</a>
    </div>

    <?php if (empty($listings)): ?>
        <div class="card border-0 shadow-sm text-center py-5" style="border-radius:16px;">
            <i class="bi bi-house-add fs-1 text-muted mb-3 d-block"></i>
            <div class="fw-semibold text-muted mb-1">No listings yet</div>
            <p class="text-muted small mb-3">Add your first homestay and start getting bookings.</p>
            <div>
                <a href="/owner/listings/create" class="btn btn-sm" style="background:#e84c2b;color:#fff;border-radius:8px;">
                    <i class="bi bi-plus-lg me-1"></i>Add Your First Listing
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
        <?php foreach ($listings as $l):
            $s = $statusColors[$l['status']] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','label'=>ucfirst($l['status'])];
            $isFeatured = $l['is_featured'] && (!$l['featured_until'] || strtotime($l['featured_until']) > time());
        ?>
            <div class="card border-0 shadow-sm listing-row" style="border-radius:14px;">
                <div class="card-body p-3">
                    <div class="d-flex gap-3 align-items-start">
                        <!-- Thumbnail -->
                        <?php if ($l['primary_image']): ?>
                            <img src="/uploads/listings/<?= $l['id'] ?>/<?= htmlspecialchars($l['primary_image']) ?>"
                                 style="width:76px;height:58px;border-radius:10px;object-fit:cover;flex-shrink:0;" alt=""
                                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
                            <div style="display:none;width:76px;height:58px;border-radius:10px;background:#f1f5f9;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-image text-muted fs-4"></i>
                            </div>
                        <?php else: ?>
                            <div style="width:76px;height:58px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-image text-muted fs-4"></i>
                            </div>
                        <?php endif; ?>

                        <!-- Info -->
                        <div class="flex-grow-1 min-width-0">
                            <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap">
                                <div>
                                    <div class="fw-semibold text-truncate" style="color:#0f172a;max-width:320px;">
                                        <?= htmlspecialchars($l['title']) ?>
                                    </div>
                                    <div class="text-muted" style="font-size:.78rem;">
                                        <?= htmlspecialchars($l['city_name']) ?>, <?= htmlspecialchars($l['state_name']) ?>
                                        &nbsp;·&nbsp; RM<?= number_format($l['price_per_night'], 0) ?>/night
                                    </div>
                                </div>
                                <div class="d-flex gap-1 align-items-center flex-wrap">
                                    <?php if ($isFeatured): ?>
                                        <span class="badge" style="background:#fef2f0;color:#e84c2b;font-size:.7rem;">
                                            <i class="bi bi-star-fill me-1"></i>Featured
                                            <?php if ($l['featured_until']): ?>until <?= date('d M', strtotime($l['featured_until'])) ?><?php endif; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="badge" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;font-size:.72rem;">
                                        <?= $s['label'] ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($l['status'] === 'rejected' && $l['rejection_reason']): ?>
                                <div class="mt-2 px-2 py-1 rounded-2 small" style="background:#fef2f2;color:#dc2626;font-size:.78rem;">
                                    <i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($l['rejection_reason']) ?>
                                </div>
                            <?php endif; ?>

                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-2 flex-wrap align-items-center">
                                <?php if ($l['status'] === 'published' && !$isFeatured): ?>
                                    <?php if ($isVerified): ?>
                                        <a href="/feature/<?= $l['id'] ?>" class="btn btn-sm fw-semibold"
                                           style="background:#e84c2b;color:#fff;border-radius:7px;font-size:.78rem;padding:.25rem .75rem;">
                                            <i class="bi bi-lightning-charge-fill me-1"></i>Feature This
                                        </a>
                                    <?php else: ?>
                                        <a href="/get-verified" title="Verified Hosts only — get verified to unlock"
                                           class="btn btn-sm" style="background:#f1f5f9;color:#94a3b8;border-radius:7px;font-size:.78rem;padding:.25rem .75rem;">
                                            <i class="bi bi-lock-fill me-1"></i>Feature This
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($l['status'] === 'published'): ?>
                                    <a href="/listing/<?= htmlspecialchars($l['slug']) ?>" target="_blank"
                                       class="btn btn-sm btn-outline-secondary" style="border-radius:7px;font-size:.78rem;padding:.25rem .75rem;">
                                        <i class="bi bi-box-arrow-up-right me-1"></i>View
                                    </a>
                                <?php endif; ?>
                                <a href="/owner/listings/<?= $l['id'] ?>/edit"
                                   class="btn btn-sm btn-outline-secondary" style="border-radius:7px;font-size:.78rem;padding:.25rem .75rem;">
                                    <i class="bi bi-pencil me-1"></i>Edit
                                </a>
                                <form method="POST" action="/owner/listings/<?= $l['id'] ?>/delete"
                                      onsubmit="return confirm('Delete this listing? This cannot be undone.');" class="m-0">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-outline-danger" style="border-radius:7px;font-size:.78rem;padding:.25rem .6rem;">
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
