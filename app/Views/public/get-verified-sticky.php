<!-- Mobile sticky CTA — injected into $stickyBar in main layout -->
<div class="d-lg-none" style="position:fixed;bottom:0;left:0;right:0;z-index:1000;padding:.875rem 1rem;background:#0f1923;border-top:1px solid #1e293b;box-shadow:0 -4px 20px rgba(0,0,0,.3);">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div>
            <div style="color:#fff;font-weight:700;font-size:1rem;">RM49 <span style="color:#94a3b8;font-weight:400;font-size:.8rem;">/ year</span></div>
            <?php if ($promoActive): ?>
            <div style="color:#fca5a5;font-size:.72rem;"><i class="bi bi-gift-fill me-1"></i>+1 free month featured</div>
            <?php endif; ?>
        </div>
        <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
           class="btn fw-bold flex-shrink-0"
           style="background:#e84c2b;color:#fff;border-radius:10px;padding:.6rem 1.5rem;">
            Get Verified <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</div>
<div class="d-lg-none" style="height:72px;"></div>
