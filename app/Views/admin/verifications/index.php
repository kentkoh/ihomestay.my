<?php
$statusMap = [
    'pending_payment' => ['label'=>'Awaiting Payment', 'badge'=>'warning'],
    'pending_review'  => ['label'=>'Pending Review',   'badge'=>'primary'],
    'approved'        => ['label'=>'Approved',         'badge'=>'success'],
    'rejected'        => ['label'=>'Rejected',         'badge'=>'danger'],
];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h5 class="fw-bold mb-0">Verification Requests</h5>
        <div class="text-muted small">Review IC / SSM documents and approve Verified Host status</div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php foreach (['pending_review'=>'Pending Review','approved'=>'Approved','rejected'=>'Rejected'] as $k => $label): ?>
        <span class="badge px-3 py-2" style="background:<?= $k==='pending_review' ? '#eff6ff' : ($k==='approved' ? '#f0fdf4' : '#fef2f2') ?>;color:<?= $k==='pending_review' ? '#2563eb' : ($k==='approved' ? '#16a34a' : '#dc2626') ?>;font-size:.78rem;">
            <?= $label ?>: <?= $counts[$k] ?>
        </span>
        <?php endforeach; ?>
    </div>
</div>

<?php if (empty($requests)): ?>
<div class="card border-0 shadow-sm text-center py-5">
    <i class="bi bi-patch-check fs-1 text-muted mb-3 d-block"></i>
    <div class="text-muted">No verification requests yet.</div>
</div>
<?php else: ?>
<div class="d-flex flex-column gap-3">
<?php foreach ($requests as $r):
    $sm = $statusMap[$r['status']] ?? ['label'=>ucfirst($r['status']),'badge'=>'secondary'];
?>
<div class="card border-0 shadow-sm" style="border-radius:14px;">
    <div class="card-body p-4">
        <div class="row g-3 align-items-start">
            <!-- Info -->
            <div class="col-md-5">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="fw-semibold" style="color:#0f172a;"><?= htmlspecialchars($r['owner_name']) ?></span>
                    <span class="badge bg-<?= $sm['badge'] ?>"><?= $sm['label'] ?></span>
                    <span class="badge bg-light text-dark border" style="font-size:.7rem;">
                        <?= $r['request_type'] === 'company' ? 'SSM' : 'Individual IC' ?>
                    </span>
                </div>
                <div class="text-muted small"><?= htmlspecialchars($r['owner_email']) ?></div>
                <div class="text-muted small mt-1">Applied: <?= date('d M Y, g:ia', strtotime($r['created_at'])) ?></div>
                <?php if ($r['payment_status'] === 'paid' && $r['paid_at']): ?>
                <div class="text-muted small">Paid: <?= date('d M Y, g:ia', strtotime($r['paid_at'])) ?></div>
                <?php endif; ?>
                <?php if ($r['promo_eligible']): ?>
                <div class="mt-2">
                    <span style="background:#fef2f0;color:#e84c2b;border:1px solid #fca5a5;border-radius:6px;font-size:.72rem;padding:.2rem .6rem;">
                        <i class="bi bi-gift-fill me-1"></i>Promo eligible
                        <?= $r['featured_activated'] ? '· Featured activated' : ($r['selected_listing_id'] ? '· Listing selected' : '· No listing selected') ?>
                    </span>
                </div>
                <?php endif; ?>
                <?php if ($r['listing_title']): ?>
                <div class="text-muted small mt-1"><i class="bi bi-house me-1"></i>Free featured: <?= htmlspecialchars(mb_substr($r['listing_title'], 0, 40)) ?></div>
                <?php endif; ?>
                <?php if ($r['admin_notes']): ?>
                <div style="background:#f8fafc;border-radius:8px;padding:.5rem .75rem;margin-top:.5rem;font-size:.78rem;color:#475569;">
                    <strong>Note:</strong> <?= htmlspecialchars($r['admin_notes']) ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Documents -->
            <div class="col-md-3">
                <div style="font-size:.75rem;color:#64748b;font-weight:600;letter-spacing:.05em;margin-bottom:.5rem;">DOCUMENTS</div>
                <div class="d-flex flex-column gap-2">
                    <a href="/admin/verifications/<?= $r['id'] ?>/document" target="_blank"
                       class="btn btn-sm btn-outline-secondary" style="font-size:.8rem;">
                        <i class="bi bi-file-earmark-fill me-1"></i>View Document
                    </a>
                    <?php if ($r['selfie_path']): ?>
                    <a href="/admin/verifications/<?= $r['id'] ?>/selfie" target="_blank"
                       class="btn btn-sm btn-outline-secondary" style="font-size:.8rem;">
                        <i class="bi bi-camera-fill me-1"></i>View Selfie
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-md-4">
                <?php if ($r['status'] === 'pending_review'): ?>
                <div style="font-size:.75rem;color:#64748b;font-weight:600;letter-spacing:.05em;margin-bottom:.5rem;">ACTION</div>
                <div class="d-flex flex-column gap-2">
                    <!-- Approve -->
                    <form method="POST" action="/admin/verifications/<?= $r['id'] ?>/approve">
                        <?= CSRF::field() ?>
                        <input type="text" name="admin_notes" class="form-control form-control-sm mb-1"
                               placeholder="Optional note to owner" style="font-size:.8rem;">
                        <button type="submit" class="btn btn-sm w-100"
                                style="background:#22c55e;color:#fff;font-size:.82rem;"
                                onclick="return confirm('Approve this verification? The owner will become a Verified Host.')">
                            <i class="bi bi-patch-check-fill me-1"></i>Approve
                        </button>
                    </form>
                    <!-- Reject -->
                    <form method="POST" action="/admin/verifications/<?= $r['id'] ?>/reject">
                        <?= CSRF::field() ?>
                        <input type="text" name="admin_notes" class="form-control form-control-sm mb-1"
                               placeholder="Rejection reason (required)" required style="font-size:.8rem;">
                        <button type="submit" class="btn btn-sm btn-outline-danger w-100" style="font-size:.82rem;"
                                onclick="return confirm('Reject this application?')">
                            <i class="bi bi-x-circle me-1"></i>Reject
                        </button>
                    </form>
                </div>
                <?php elseif ($r['status'] === 'approved'): ?>
                <div style="color:#16a34a;font-size:.85rem;"><i class="bi bi-check-circle-fill me-1"></i>Approved and verified</div>
                <?php elseif ($r['status'] === 'rejected'): ?>
                <div style="color:#dc2626;font-size:.85rem;"><i class="bi bi-x-circle-fill me-1"></i>Rejected</div>
                <?php else: ?>
                <div style="color:#94a3b8;font-size:.85rem;">Awaiting payment</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
