<style>
:root{--red:#e84c2b;--dark:#0f1923;--slate:#1e293b;}
.gv-hero{background:linear-gradient(135deg,var(--dark) 0%,#1a2535 100%);padding:4rem 0 5rem;position:relative;overflow:hidden;}
.gv-hero::before{content:'';position:absolute;right:-120px;top:-120px;width:400px;height:400px;border-radius:50%;background:rgba(232,76,43,.08);pointer-events:none;}
.gv-hero::after{content:'';position:absolute;left:-80px;bottom:-100px;width:300px;height:300px;border-radius:50%;background:rgba(232,76,43,.05);pointer-events:none;}
.badge-promo{background:rgba(232,76,43,.2);color:#fca5a5;border:1px solid rgba(232,76,43,.3);font-size:.75rem;letter-spacing:.08em;padding:.35rem .8rem;border-radius:99px;display:inline-block;margin-bottom:1rem;}
.timer-box{background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:1rem 1.5rem;display:inline-flex;gap:1.5rem;margin-top:1.5rem;}
.timer-unit{text-align:center;}
.timer-num{font-size:2rem;font-weight:700;color:#fff;line-height:1;font-variant-numeric:tabular-nums;}
.timer-label{font-size:.65rem;color:#64748b;letter-spacing:.08em;text-transform:uppercase;margin-top:.2rem;}
.compare-table{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.06);}
.compare-table th{padding:1.25rem 1.5rem;font-size:.8rem;letter-spacing:.06em;text-transform:uppercase;}
.compare-table td{padding:1rem 1.5rem;border-top:1px solid #f1f5f9;font-size:.92rem;vertical-align:middle;}
.compare-table tr:hover td{background:#fafcff;}
.col-free{background:#f8fafc;}
.col-verified{background:linear-gradient(135deg,#fef2f0,#fff8f7);}
.th-free{color:#64748b;}
.th-verified{color:var(--red);}
.check-yes{color:#22c55e;font-size:1.1rem;}
.check-no{color:#cbd5e1;font-size:1.1rem;}
.price-card{background:linear-gradient(135deg,var(--dark) 0%,var(--slate) 100%);border-radius:20px;padding:2.5rem;color:#fff;position:relative;overflow:hidden;}
.price-card::before{content:'';position:absolute;right:-40px;top:-40px;width:200px;height:200px;border-radius:50%;background:rgba(232,76,43,.12);}
.price-card .price-amount{font-size:3.5rem;font-weight:800;line-height:1;color:#fff;}
.price-card .price-per{font-size:1rem;color:#94a3b8;margin-left:.5rem;}
.price-badge{background:rgba(232,76,43,.25);color:#fca5a5;border:1px solid rgba(232,76,43,.3);border-radius:8px;font-size:.78rem;padding:.3rem .75rem;display:inline-block;margin-bottom:1.5rem;}
.benefit-row{display:flex;align-items:flex-start;gap:.75rem;margin-bottom:1rem;}
.benefit-icon{width:32px;height:32px;border-radius:8px;background:rgba(232,76,43,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.step-bubble{width:36px;height:36px;border-radius:50%;background:var(--red);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;flex-shrink:0;}
.step-line{width:2px;background:#f1f5f9;margin:4px auto;flex:1;}
</style>

<!-- HERO -->
<section class="gv-hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="badge-promo"><i class="bi bi-clock me-1"></i>Limited-Time Offer Active</span>
                <h1 class="fw-bold mb-3" style="color:#fff;font-size:clamp(2rem,5vw,3.2rem);line-height:1.2;">
                    Become a<br><span style="color:var(--red);">Verified Host</span>
                </h1>
                <p style="color:#94a3b8;font-size:1.05rem;max-width:460px;line-height:1.8;margin-bottom:2rem;">
                    Stand out from thousands of listings. Get the verification badge, rank higher in search, let guests WhatsApp you directly, and unlock featured listing boosts.
                </p>
                <div class="d-flex flex-wrap gap-2 mb-4">
                    <span style="color:#fff;font-size:.88rem;"><i class="bi bi-patch-check-fill me-1" style="color:#22c55e;"></i>Verified badge on every listing</span>
                    <span style="color:#fff;font-size:.88rem;"><i class="bi bi-whatsapp me-1" style="color:#25d366;"></i>Direct WhatsApp contact</span>
                    <span style="color:#fff;font-size:.88rem;"><i class="bi bi-graph-up-arrow me-1" style="color:#e84c2b;"></i>Higher search ranking</span>
                    <span style="color:#fff;font-size:.88rem;"><i class="bi bi-star-fill me-1" style="color:#f59e0b;"></i>Featured eligible</span>
                </div>

                <?php if ($promoActive): ?>
                <div>
                    <div style="color:#94a3b8;font-size:.8rem;margin-bottom:.5rem;letter-spacing:.05em;">⚡ BUY NOW & GET 1 FREE MONTH FEATURED — offer expires in:</div>
                    <div class="timer-box" id="countdownBox">
                        <div class="timer-unit"><div class="timer-num" id="cdHours">--</div><div class="timer-label">Hours</div></div>
                        <div class="timer-unit"><div class="timer-num" id="cdMins">--</div><div class="timer-label">Mins</div></div>
                        <div class="timer-unit"><div class="timer-num" id="cdSecs">--</div><div class="timer-label">Secs</div></div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="col-lg-6">
                <!-- Price card -->
                <div class="price-card">
                    <div class="price-badge"><i class="bi bi-star-fill me-1"></i>Annual Membership</div>
                    <div class="d-flex align-items-baseline mb-1">
                        <span class="price-amount">RM<?= number_format($yearlyPrice, 0) ?></span>
                        <span class="price-per">/ year</span>
                    </div>
                    <div style="color:#64748b;font-size:.85rem;margin-bottom:2rem;">One-time yearly payment — cancel anytime</div>

                    <?php if ($promoActive): ?>
                    <div style="background:rgba(232,76,43,.15);border:1px solid rgba(232,76,43,.25);border-radius:12px;padding:1rem 1.25rem;margin-bottom:2rem;">
                        <div style="color:#fca5a5;font-weight:600;font-size:.88rem;margin-bottom:.25rem;">
                            <i class="bi bi-gift-fill me-1"></i>Promo Active!
                        </div>
                        <div style="color:#94a3b8;font-size:.82rem;">Buy now and choose any published listing to get <strong style="color:#fff;">1 free month featured</strong> once verified.</div>
                    </div>
                    <?php endif; ?>

                    <div class="d-flex flex-column gap-3 mb-2" style="position:relative;z-index:1;">
                        <div class="benefit-row">
                            <div class="benefit-icon"><i class="bi bi-patch-check-fill" style="color:var(--red);"></i></div>
                            <div><div style="color:#fff;font-weight:600;font-size:.9rem;">Verified Badge</div><div style="color:#64748b;font-size:.8rem;">Blue tick on all your listings</div></div>
                        </div>
                        <div class="benefit-row">
                            <div class="benefit-icon"><i class="bi bi-whatsapp" style="color:#25d366;"></i></div>
                            <div><div style="color:#fff;font-weight:600;font-size:.9rem;">Direct WhatsApp Contact</div><div style="color:#64748b;font-size:.8rem;">Guests reach you without middlemen</div></div>
                        </div>
                        <div class="benefit-row">
                            <div class="benefit-icon"><i class="bi bi-star-fill" style="color:#f59e0b;"></i></div>
                            <div><div style="color:#fff;font-weight:600;font-size:.9rem;">Featured Listing Eligible</div><div style="color:#64748b;font-size:.8rem;">Boost any listing to the top of search</div></div>
                        </div>
                        <div class="benefit-row">
                            <div class="benefit-icon"><i class="bi bi-graph-up-arrow" style="color:#e84c2b;"></i></div>
                            <div><div style="color:#fff;font-weight:600;font-size:.9rem;">Higher Search Ranking</div><div style="color:#64748b;font-size:.8rem;">Verified listings rank above unverified</div></div>
                        </div>
                    </div>

                    <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
                       class="btn fw-bold d-block py-3 mt-4"
                       style="background:var(--red);color:#fff;border-radius:12px;font-size:1rem;">
                        <?= Auth::check() ? 'Apply for Verification' : 'Register & Get Verified' ?>
                        <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <?php if (!Auth::check()): ?>
                    <div style="color:#475569;font-size:.75rem;text-align:center;margin-top:.75rem;">Already have an account? <a href="/login" style="color:#94a3b8;">Login</a></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- COMPARISON TABLE -->
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#0f172a;">Free vs <span style="color:var(--red);">Verified Host</span></h2>
            <p class="text-muted">Everything you get when you upgrade.</p>
        </div>
        <div class="compare-table table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th style="background:#fff;border:none;width:45%;">Feature</th>
                        <th class="col-free th-free text-center border-0">Free Owner</th>
                        <th class="col-verified th-verified text-center border-0"><i class="bi bi-patch-check-fill me-1"></i>Verified Host</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $rows = [
                        ['Max listings',              '3 listings',   'Unlimited'],
                        ['Listing approval',          'Manual review','Priority review'],
                        ['Verified badge on listing', false,          true],
                        ['Direct WhatsApp contact',   false,          true],
                        ['Search ranking',            'Standard',     'Higher priority'],
                        ['Featured listing eligible', false,          true],
                        ['Guest trust & credibility', 'Basic',        'High — verified identity'],
                        ['Priority support',          false,          true],
                        ['Annual price',              'Free',         'RM49 / year'],
                    ]; foreach ($rows as $r): ?>
                    <tr>
                        <td class="fw-medium" style="color:#0f172a;"><?= $r[0] ?></td>
                        <td class="col-free text-center">
                            <?php if ($r[1] === true): ?>
                                <i class="bi bi-check-circle-fill check-yes"></i>
                            <?php elseif ($r[1] === false): ?>
                                <i class="bi bi-x-circle check-no"></i>
                            <?php else: ?>
                                <span class="text-muted small"><?= $r[1] ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="col-verified text-center">
                            <?php if ($r[2] === true): ?>
                                <i class="bi bi-check-circle-fill check-yes"></i>
                            <?php elseif ($r[2] === false): ?>
                                <i class="bi bi-x-circle check-no"></i>
                            <?php else: ?>
                                <span style="color:var(--red);font-weight:600;font-size:.9rem;"><?= $r[2] ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-5">
            <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
               class="btn btn-lg fw-bold px-5 py-3"
               style="background:var(--red);color:#fff;border-radius:12px;">
                Get Verified — RM49/year <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <div class="text-muted small mt-2">Documents reviewed within 24 hours</div>
        </div>
    </div>
</section>

<!-- HOW IT WORKS -->
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#0f172a;">How It Works</h2>
            <p class="text-muted">Three simple steps to get verified.</p>
        </div>
        <div class="row justify-content-center g-4">
            <?php $steps = [
                ['1','bi-cloud-upload','Upload Documents','Submit your IC (individual) or SSM certificate (company). Files are reviewed securely by our team.'],
                ['2','bi-credit-card','Pay RM49/year','Secure payment via BillPlz. Your submission is reviewed once payment is confirmed.'],
                ['3','bi-patch-check-fill','Get Verified','Our team reviews within 24 hours. Once approved, your verified badge goes live instantly.'],
            ]; foreach ($steps as $i => $s): ?>
            <div class="col-12 col-md-4">
                <div class="text-center px-3">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="step-bubble"><?= $s[0] ?></div>
                        <?php if ($i < 2): ?>
                        <div style="height:2px;flex:1;background:#f1f5f9;max-width:60px;margin:0 .75rem;" class="d-none d-md-block"></div>
                        <?php endif; ?>
                    </div>
                    <div style="width:56px;height:56px;border-radius:14px;background:#fef2f0;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="bi <?= $s[1] ?>" style="color:var(--red);font-size:1.5rem;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#0f172a;"><?= $s[2] ?></h6>
                    <p class="text-muted small"><?= $s[3] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($promoActive): ?>
<script>
(function(){
    const endsAt = <?= (int) $promoEndsAt ?> * 1000;
    function tick(){
        const diff = Math.max(0, Math.floor((endsAt - Date.now()) / 1000));
        const h = Math.floor(diff / 3600);
        const m = Math.floor((diff % 3600) / 60);
        const s = diff % 60;
        const pad = n => String(n).padStart(2,'0');
        document.getElementById('cdHours').textContent = pad(h);
        document.getElementById('cdMins').textContent  = pad(m);
        document.getElementById('cdSecs').textContent  = pad(s);
        if(diff === 0) clearInterval(timer);
    }
    tick();
    const timer = setInterval(tick, 1000);
})();
</script>
<?php endif; ?>
