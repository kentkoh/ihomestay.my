<div class="container py-5" style="max-width:560px;">
    <div style="background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(0,0,0,.06);padding:2.5rem;text-align:center;">

        <div style="width:72px;height:72px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
            <i class="bi bi-patch-check-fill" style="color:#22c55e;font-size:2rem;"></i>
        </div>

        <h4 class="fw-bold mb-2" style="color:#0f172a;">Payment Received!</h4>
        <p class="text-muted mb-4">Your verification application has been submitted. Our team will review your documents within <strong>24 hours</strong>.</p>

        <?php if ($vr && $vr['promo_eligible']): ?>
        <div style="background:linear-gradient(135deg,#0f1923,#1e293b);border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;text-align:left;">
            <div style="color:#fca5a5;font-weight:600;font-size:.88rem;margin-bottom:.4rem;">
                <i class="bi bi-gift-fill me-1"></i>Promo Locked In!
            </div>
            <div style="color:#94a3b8;font-size:.82rem;">
                You paid during the promo window.
                <?php if ($vr['selected_listing_id']): ?>
                    Your selected listing will get <strong style="color:#fff;">1 free month featured</strong> once your application is approved.
                <?php else: ?>
                    Contact support after approval to claim your 1 free month featured listing.
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <div style="background:#f8fafc;border-radius:12px;padding:1.25rem;margin-bottom:1.5rem;text-align:left;">
            <div style="color:#64748b;font-size:.8rem;margin-bottom:.5rem;font-weight:600;letter-spacing:.05em;">WHAT HAPPENS NEXT</div>
            <div class="d-flex flex-column gap-2">
                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                    <i class="bi bi-1-circle-fill" style="color:#e84c2b;flex-shrink:0;margin-top:.1rem;"></i>
                    <span style="font-size:.85rem;color:#475569;">Our team reviews your submitted document</span>
                </div>
                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                    <i class="bi bi-2-circle-fill" style="color:#e84c2b;flex-shrink:0;margin-top:.1rem;"></i>
                    <span style="font-size:.85rem;color:#475569;">We approve within 24 hours and send you an email</span>
                </div>
                <div style="display:flex;gap:.75rem;align-items:flex-start;">
                    <i class="bi bi-3-circle-fill" style="color:#e84c2b;flex-shrink:0;margin-top:.1rem;"></i>
                    <span style="font-size:.85rem;color:#475569;">Your Verified Host badge goes live on all your listings</span>
                </div>
            </div>
        </div>

        <a href="/owner/dashboard" class="btn fw-bold px-5 py-2" style="background:#e84c2b;color:#fff;border-radius:10px;">
            Back to Dashboard
        </a>
    </div>
</div>
