<?php
$statusMap = [
    'pending_payment' => ['label'=>'Awaiting Payment',  'color'=>'#f59e0b','bg'=>'#fffbeb','icon'=>'bi-clock-fill'],
    'pending_review'  => ['label'=>'Under Review',      'color'=>'#3b82f6','bg'=>'#eff6ff','icon'=>'bi-hourglass-split'],
    'approved'        => ['label'=>'Approved',          'color'=>'#22c55e','bg'=>'#f0fdf4','icon'=>'bi-patch-check-fill'],
    'rejected'        => ['label'=>'Rejected',          'color'=>'#ef4444','bg'=>'#fef2f2','icon'=>'bi-x-circle-fill'],
];
$s = $statusMap[$existingApp['status']] ?? $statusMap['pending_review'];
?>

<div class="container py-5" style="max-width:560px;">
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(0,0,0,.06);padding:2.5rem;text-align:center;">

        <div style="width:72px;height:72px;border-radius:50%;background:<?= $s['bg'] ?>;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <i class="bi <?= $s['icon'] ?>" style="color:<?= $s['color'] ?>;font-size:2rem;"></i>
        </div>

        <span class="badge mb-3" style="background:<?= $s['bg'] ?>;color:<?= $s['color'] ?>;border:1px solid <?= $s['color'] ?>33;font-size:.82rem;padding:.4rem .9rem;">
            <?= $s['label'] ?>
        </span>

        <h4 class="fw-bold mb-2" style="color:#0f172a;">Verification Application</h4>

        <?php if ($existingApp['status'] === 'pending_payment'): ?>
            <p class="text-muted small mb-4">Your application was saved but payment wasn't completed. Please pay to proceed with review.</p>
            <p class="text-muted" style="font-size:.8rem;">Applied on <?= date('d M Y', strtotime($existingApp['created_at'])) ?></p>
        <?php elseif ($existingApp['status'] === 'pending_review'): ?>
            <p class="text-muted small mb-4">Your documents are being reviewed by our team. You'll be notified by email within 24 hours.</p>
            <p class="text-muted" style="font-size:.8rem;">Submitted on <?= date('d M Y', strtotime($existingApp['paid_at'] ?? $existingApp['created_at'])) ?></p>
        <?php elseif ($existingApp['status'] === 'approved'): ?>
            <p class="text-muted small mb-4">Congratulations! You are a Verified Host. Your badge is live on all your listings.</p>
            <?php if ($existingApp['promo_eligible'] && $existingApp['selected_listing_id']): ?>
                <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:1rem;margin-bottom:1rem;">
                    <i class="bi bi-star-fill me-1" style="color:#22c55e;"></i>
                    <span style="color:#16a34a;font-size:.88rem;font-weight:600;">1 free month featured <?= $existingApp['featured_activated'] ? 'activated!' : 'will be activated on approval.' ?></span>
                </div>
            <?php endif; ?>
        <?php elseif ($existingApp['status'] === 'rejected'): ?>
            <p class="text-muted small mb-2">Unfortunately your application was not approved.</p>
            <?php if ($existingApp['admin_notes']): ?>
            <div style="background:#fef2f2;border-radius:10px;padding:.875rem;margin-bottom:1rem;text-align:left;">
                <div style="color:#dc2626;font-size:.82rem;font-weight:600;margin-bottom:.25rem;">Reason:</div>
                <div style="color:#7f1d1d;font-size:.82rem;"><?= htmlspecialchars($existingApp['admin_notes']) ?></div>
            </div>
            <?php endif; ?>
            <p class="text-muted" style="font-size:.78rem;">You may re-apply. Contact support if you believe this is an error.</p>
        <?php endif; ?>

        <div class="d-flex gap-2 justify-content-center mt-3">
            <a href="/owner/dashboard" class="btn btn-outline-secondary">Back to Dashboard</a>
            <?php if ($existingApp['status'] === 'rejected'): ?>
            <?php /* Allow re-apply: would need to handle in controller */ ?>
            <a href="/get-verified" class="btn" style="background:#e84c2b;color:#fff;">Re-apply</a>
            <?php endif; ?>
        </div>
    </div>
</div>
