<?php
$statusColors = ['published'=>'success','pending'=>'warning','rejected'=>'danger','suspended'=>'dark','draft'=>'secondary'];
$verifyColors = ['verified'=>'success','unverified'=>'secondary','pending_verification'=>'warning','rejected'=>'danger','suspended'=>'dark'];
?>

<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #3b82f6!important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">LISTINGS</span>
                    <div style="width:36px;height:36px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-house-fill" style="color:#3b82f6;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#0f172a;line-height:1;"><?= $stats['total_listings'] ?></div>
                <div class="mt-1 d-flex gap-2 flex-wrap">
                    <span class="badge bg-success" style="font-size:.7rem;"><?= $stats['published_listings'] ?> live</span>
                    <?php if ($stats['pending_listings']): ?>
                        <a href="/admin/listings?status=pending" class="badge bg-warning text-dark text-decoration-none" style="font-size:.7rem;"><?= $stats['pending_listings'] ?> pending ↗</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #22c55e!important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">OWNERS</span>
                    <div style="width:36px;height:36px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-people-fill" style="color:#22c55e;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#0f172a;line-height:1;"><?= $stats['total_owners'] ?></div>
                <div class="mt-1">
                    <span class="badge bg-success" style="font-size:.7rem;"><?= $stats['verified_owners'] ?> verified</span>
                    <span class="badge bg-secondary ms-1" style="font-size:.7rem;"><?= $stats['total_owners'] - $stats['verified_owners'] ?> unverified</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #e84c2b!important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">FEATURED ACTIVE</span>
                    <div style="width:36px;height:36px;border-radius:10px;background:#fef2f0;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-star-fill" style="color:#e84c2b;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#0f172a;line-height:1;"><?= $stats['featured_listings'] ?></div>
                <div class="mt-1">
                    <a href="/admin/featured-packages" class="badge text-decoration-none" style="background:#fef2f0;color:#e84c2b;font-size:.7rem;">Manage packages ↗</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;border-left:4px solid #f59e0b!important;">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">REVENUE</span>
                    <div style="width:36px;height:36px;border-radius:10px;background:#fffbeb;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-cash-coin" style="color:#f59e0b;"></i>
                    </div>
                </div>
                <div class="fw-bold" style="font-size:1.8rem;color:#0f172a;line-height:1;">RM<?= number_format($stats['total_revenue'], 0) ?></div>
                <div class="mt-1">
                    <span class="badge bg-secondary" style="font-size:.7rem;"><?= $stats['paid_payments'] ?> paid orders</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius:14px;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3" style="color:#0f172a;">Quick Actions</h6>
                <div class="row g-3">
                    <?php
                    $actions = [
                        ['href'=>'/admin/listings?status=pending','icon'=>'bi-clock-history','color'=>'#f59e0b','bg'=>'#fffbeb','label'=>'Pending Approvals','sub'=>$stats['pending_listings'].' awaiting review'],
                        ['href'=>'/admin/listings','icon'=>'bi-house-fill','color'=>'#3b82f6','bg'=>'#eff6ff','label'=>'All Listings','sub'=>$stats['total_listings'].' total listings'],
                        ['href'=>'/admin/owners','icon'=>'bi-people-fill','color'=>'#22c55e','bg'=>'#f0fdf4','label'=>'Manage Owners','sub'=>$stats['verified_owners'].' verified'],
                        ['href'=>'/admin/featured-packages','icon'=>'bi-star-fill','color'=>'#e84c2b','bg'=>'#fef2f0','label'=>'Featured Packages','sub'=>'Edit prices & days'],
                        ['href'=>'/admin/articles','icon'=>'bi-newspaper','color'=>'#8b5cf6','bg'=>'#f5f3ff','label'=>'Articles','sub'=>$stats['total_articles'].' articles'],
                        ['href'=>'/admin/facilities','icon'=>'bi-grid-3x3-gap-fill','color'=>'#64748b','bg'=>'#f8fafc','label'=>'Facilities','sub'=>'Manage facility tags'],
                    ];
                    foreach ($actions as $a):
                    ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <a href="<?= $a['href'] ?>" class="text-decoration-none d-block text-center p-3 rounded-3 h-100"
                           style="background:<?= $a['bg'] ?>;transition:transform .15s,box-shadow .15s;"
                           onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 6px 20px rgba(0,0,0,.1)'"
                           onmouseout="this.style.transform='';this.style.boxShadow=''">
                            <div style="width:44px;height:44px;border-radius:12px;background:#fff;display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;">
                                <i class="bi <?= $a['icon'] ?>" style="color:<?= $a['color'] ?>;font-size:1.2rem;"></i>
                            </div>
                            <div class="fw-semibold" style="color:#0f172a;font-size:.85rem;"><?= $a['label'] ?></div>
                            <div style="color:#64748b;font-size:.75rem;margin-top:.2rem;"><?= $a['sub'] ?></div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Listings + Recent Owners -->
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0" style="color:#0f172a;">Pending Approval</h6>
                    <a href="/admin/listings?status=pending" class="btn btn-sm" style="background:#fef2f0;color:#e84c2b;font-size:.78rem;">View All</a>
                </div>
                <?php if (empty($pending)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle-fill fs-2 text-success mb-2 d-block"></i>
                        <div class="text-muted small">All listings reviewed — nothing pending.</div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($pending as $p): ?>
                            <div class="d-flex align-items-center gap-3 p-2 rounded-3" style="background:#f8fafc;">
                                <div style="width:36px;height:36px;border-radius:10px;background:#fef2f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="bi bi-house" style="color:#e84c2b;"></i>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-semibold text-truncate" style="font-size:.85rem;color:#0f172a;"><?= htmlspecialchars($p['title']) ?></div>
                                    <div class="text-muted" style="font-size:.75rem;"><?= htmlspecialchars($p['owner_name']) ?> · <?= htmlspecialchars($p['state_name']) ?></div>
                                </div>
                                <div class="d-flex gap-1 flex-shrink-0">
                                    <form method="POST" action="/admin/listings/<?= $p['id'] ?>/approve" class="m-0">
                                        <?= CSRF::field() ?>
                                        <button class="btn btn-sm btn-success" style="font-size:.72rem;padding:.25rem .6rem;" title="Approve">
                                            <i class="bi bi-check-lg"></i>
                                        </button>
                                    </form>
                                    <a href="/admin/listings/<?= $p['id'] ?>/edit" class="btn btn-sm btn-outline-secondary" style="font-size:.72rem;padding:.25rem .6rem;" title="Review">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100" style="border-radius:14px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0" style="color:#0f172a;">Recent Owners</h6>
                    <a href="/admin/owners" class="btn btn-sm" style="background:#f0fdf4;color:#22c55e;font-size:.78rem;">View All</a>
                </div>
                <?php if (empty($recent_owners)): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-people fs-2 text-muted mb-2 d-block"></i>
                        <div class="text-muted small">No owners yet.</div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-2">
                        <?php foreach ($recent_owners as $o): ?>
                            <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;">
                                <div style="width:34px;height:34px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:.8rem;font-weight:700;color:#64748b;">
                                    <?= mb_strtoupper(mb_substr($o['name'], 0, 1)) ?>
                                </div>
                                <div class="flex-grow-1 min-width-0">
                                    <div class="fw-semibold text-truncate" style="font-size:.82rem;color:#0f172a;"><?= htmlspecialchars($o['name']) ?></div>
                                    <div class="text-muted text-truncate" style="font-size:.72rem;"><?= $o['listing_count'] ?> listing<?= $o['listing_count'] != 1 ? 's' : '' ?></div>
                                </div>
                                <span class="badge bg-<?= $verifyColors[$o['verification_status']] ?? 'secondary' ?> text-capitalize" style="font-size:.65rem;">
                                    <?= str_replace('_', ' ', $o['verification_status']) ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
