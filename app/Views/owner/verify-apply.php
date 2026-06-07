<style>
.vform-card{background:#fff;border-radius:20px;box-shadow:0 4px 24px rgba(0,0,0,.06);padding:2rem;}
.type-btn{border:2px solid #e2e8f0;border-radius:14px;padding:1.25rem;cursor:pointer;transition:all .15s;background:#fff;width:100%;}
.type-btn:hover{border-color:#e84c2b;background:#fef2f0;}
.type-btn.selected{border-color:#e84c2b;background:#fef2f0;}
.type-btn input{display:none;}
.upload-zone{border:2px dashed #e2e8f0;border-radius:14px;padding:2rem;text-align:center;cursor:pointer;transition:all .2s;background:#f8fafc;}
.upload-zone:hover{border-color:#e84c2b;background:#fef2f0;}
.upload-zone.has-file{border-color:#22c55e;background:#f0fdf4;}
.promo-badge{background:linear-gradient(135deg,#0f1923,#1e293b);border-radius:14px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;}
</style>

<div class="container py-5" style="max-width:700px;">

    <div class="mb-4">
        <a href="/get-verified" style="color:#e84c2b;text-decoration:none;font-size:.88rem;">
            <i class="bi bi-arrow-left me-1"></i>Back to Verified Host info
        </a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show mb-4">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?php if ($promoActive): ?>
    <div class="promo-badge mb-4">
        <div class="d-flex align-items-start gap-3">
            <div style="width:40px;height:40px;border-radius:10px;background:rgba(232,76,43,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bi bi-gift-fill" style="color:#fca5a5;"></i>
            </div>
            <div>
                <div style="color:#fff;font-weight:600;margin-bottom:.2rem;">Promo Active — 1 Free Month Featured!</div>
                <div style="color:#94a3b8;font-size:.82rem;">Complete your payment now to claim 1 free month of featured listing. Select your listing below.</div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <div style="color:#64748b;font-size:.7rem;margin-bottom:.15rem;">Expires in</div>
                <div style="color:#fff;font-weight:700;font-size:1rem;" id="promoTimer">--:--</div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="vform-card">
        <h4 class="fw-bold mb-1" style="color:#0f172a;">Verification Application</h4>
        <p class="text-muted small mb-4">Submit your identity document to become a Verified Host. We review within 24 hours.</p>

        <form method="POST" action="/owner/verify" enctype="multipart/form-data" id="verifyForm">
            <?= CSRF::field() ?>

            <!-- Step 1: Type -->
            <div class="mb-4">
                <label class="form-label fw-semibold">I am applying as</label>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="type-btn selected" id="btn-individual">
                            <input type="radio" name="request_type" value="individual" checked>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-person-fill" style="color:#e84c2b;font-size:1.25rem;"></i>
                                <span class="fw-semibold" style="color:#0f172a;">Individual</span>
                            </div>
                            <div class="text-muted" style="font-size:.78rem;">Submit your IC (MyKad / Passport)</div>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="type-btn" id="btn-company">
                            <input type="radio" name="request_type" value="company">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <i class="bi bi-building" style="color:#e84c2b;font-size:1.25rem;"></i>
                                <span class="fw-semibold" style="color:#0f172a;">Company</span>
                            </div>
                            <div class="text-muted" style="font-size:.78rem;">Submit your SSM registration certificate</div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Step 2: Document upload -->
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    Upload Document <span style="color:#e84c2b;">*</span>
                </label>
                <div style="color:#64748b;font-size:.8rem;margin-bottom:.75rem;" id="docHint">
                    IC (front side) — JPG, PNG, or PDF, max 5 MB
                </div>
                <div class="upload-zone" id="docZone" onclick="document.getElementById('docInput').click()">
                    <i class="bi bi-cloud-upload fs-2 text-muted mb-2 d-block"></i>
                    <div class="fw-semibold text-muted" id="docLabel">Click to upload document</div>
                    <div style="color:#94a3b8;font-size:.78rem;margin-top:.25rem;">JPG, PNG or PDF • Max 5 MB</div>
                </div>
                <input type="file" name="document" id="docInput" accept=".jpg,.jpeg,.png,.webp,.pdf" class="d-none" required>
            </div>

            <!-- Step 3: Selfie (optional) -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Selfie with Document <span class="text-muted fw-normal">(optional)</span></label>
                <div style="color:#64748b;font-size:.8rem;margin-bottom:.75rem;">A photo of yourself holding your IC helps speed up verification.</div>
                <div class="upload-zone" id="selfieZone" onclick="document.getElementById('selfieInput').click()">
                    <i class="bi bi-camera fs-2 text-muted mb-2 d-block"></i>
                    <div class="fw-semibold text-muted" id="selfieLabel">Click to upload selfie (optional)</div>
                    <div style="color:#94a3b8;font-size:.78rem;margin-top:.25rem;">JPG or PNG • Max 5 MB</div>
                </div>
                <input type="file" name="selfie" id="selfieInput" accept=".jpg,.jpeg,.png,.webp" class="d-none">
            </div>

            <?php if ($promoActive && !empty($publishedListings)): ?>
            <!-- Step 4: Select listing for free featured (promo only) -->
            <div class="mb-4" style="border-top:1px solid #f1f5f9;padding-top:1.5rem;">
                <label class="form-label fw-semibold">
                    <i class="bi bi-gift-fill me-1" style="color:#e84c2b;"></i>
                    Choose listing for FREE 1-month featured
                </label>
                <div style="color:#64748b;font-size:.8rem;margin-bottom:.75rem;">
                    Pick one of your published listings. We'll activate 1 free month of featured once you're approved.
                </div>
                <select name="selected_listing_id" class="form-select">
                    <option value="">— Skip (claim later) —</option>
                    <?php foreach ($publishedListings as $pl): ?>
                    <option value="<?= $pl['id'] ?>">
                        <?= htmlspecialchars(mb_substr($pl['title'], 0, 60)) ?> — <?= htmlspecialchars($pl['city_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>

            <!-- Price summary -->
            <div style="background:#f8fafc;border-radius:14px;padding:1.25rem;margin-bottom:1.5rem;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="fw-semibold" style="color:#0f172a;">Verified Host Membership</div>
                        <div class="text-muted small">Annual · auto-renews next year</div>
                    </div>
                    <div class="fw-bold" style="color:#e84c2b;font-size:1.25rem;">RM<?= number_format($yearlyPrice, 0) ?></div>
                </div>
                <?php if ($promoActive): ?>
                <div style="border-top:1px dashed #e2e8f0;margin-top:.75rem;padding-top:.75rem;">
                    <div class="d-flex justify-content-between" style="font-size:.82rem;">
                        <span style="color:#22c55e;"><i class="bi bi-gift-fill me-1"></i>Free 1-month featured listing</span>
                        <span style="color:#22c55e;font-weight:600;">FREE</span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <p style="color:#94a3b8;font-size:.78rem;margin-bottom:1.25rem;">
                <i class="bi bi-lock-fill me-1"></i>Payment processed securely via BillPlz. Your documents are encrypted and stored privately.
            </p>

            <button type="submit" class="btn fw-bold d-block w-100 py-3"
                    style="background:#e84c2b;color:#fff;border-radius:12px;font-size:1rem;">
                Proceed to Payment — RM<?= number_format($yearlyPrice, 0) ?>
                <i class="bi bi-arrow-right ms-2"></i>
            </button>
        </form>
    </div>
</div>

<script>
// Type selector
document.querySelectorAll('input[name="request_type"]').forEach(radio => {
    radio.addEventListener('change', function(){
        document.querySelectorAll('.type-btn').forEach(b => b.classList.remove('selected'));
        this.closest('.type-btn').classList.add('selected');
        const hint = document.getElementById('docHint');
        if(this.value === 'company'){
            hint.textContent = 'SSM Business Registration Certificate — JPG, PNG, or PDF, max 5 MB';
        } else {
            hint.textContent = 'IC (front side) — JPG, PNG, or PDF, max 5 MB';
        }
    });
});

// File upload zones
function wireUpload(inputId, zoneId, labelId){
    document.getElementById(inputId).addEventListener('change', function(){
        if(this.files.length){
            document.getElementById(zoneId).classList.add('has-file');
            document.getElementById(labelId).textContent = this.files[0].name;
        }
    });
}
wireUpload('docInput','docZone','docLabel');
wireUpload('selfieInput','selfieZone','selfieLabel');

<?php if ($promoActive): ?>
// Promo timer
(function(){
    const endsAt = <?= (int) $promoEndsAt ?> * 1000;
    function tick(){
        const diff = Math.max(0, Math.floor((endsAt - Date.now()) / 1000));
        const m = Math.floor(diff / 60);
        const s = diff % 60;
        document.getElementById('promoTimer').textContent =
            String(m).padStart(2,'0') + ':' + String(s).padStart(2,'0');
        if(diff === 0){ document.getElementById('promoTimer').textContent = 'EXPIRED'; clearInterval(t); }
    }
    tick();
    const t = setInterval(tick, 1000);
})();
<?php endif; ?>
</script>
