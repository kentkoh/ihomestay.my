<style>
:root{--red:#e84c2b;--dark:#0f1923;--slate:#1e293b;}

/* ── Hero ── */
.gv-hero{background:linear-gradient(135deg,#0a1018 0%,#1a2535 100%);padding:4rem 0 5rem;position:relative;overflow:hidden;}
.gv-hero::before{content:'';position:absolute;right:-80px;top:-80px;width:380px;height:380px;border-radius:50%;background:radial-gradient(circle,rgba(232,76,43,.18) 0%,transparent 70%);}
.gv-hero::after{content:'';position:absolute;left:-60px;bottom:-80px;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(232,76,43,.1) 0%,transparent 70%);}
.badge-pill{background:rgba(232,76,43,.18);color:#fca5a5;border:1px solid rgba(232,76,43,.3);font-size:.72rem;letter-spacing:.08em;padding:.3rem .85rem;border-radius:99px;display:inline-block;margin-bottom:1rem;}
.timer-row{display:inline-flex;gap:0;margin-top:1rem;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);border-radius:14px;overflow:hidden;}
.t-cell{padding:.6rem 1rem;text-align:center;border-right:1px solid rgba(255,255,255,.08);}
.t-cell:last-child{border-right:none;}
.t-num{font-size:1.6rem;font-weight:800;color:#fff;line-height:1;font-variant-numeric:tabular-nums;}
.t-lab{font-size:.55rem;color:#64748b;text-transform:uppercase;letter-spacing:.08em;}
.price-card{background:linear-gradient(160deg,#1a2535 0%,#0f1923 100%);border-radius:22px;padding:2rem;border:1px solid rgba(255,255,255,.08);position:relative;overflow:hidden;}
.price-card::before{content:'';position:absolute;right:-50px;top:-50px;width:180px;height:180px;border-radius:50%;background:rgba(232,76,43,.1);}

/* ── Promo banner ── */
.promo-mega{background:linear-gradient(135deg,#7c0000 0%,#b91c1c 40%,#e84c2b 100%);padding:3.5rem 0;position:relative;overflow:hidden;}
.promo-mega::before{content:'';position:absolute;left:-60px;top:-60px;width:250px;height:250px;border-radius:50%;background:rgba(255,255,255,.06);}
.promo-mega::after{content:'';position:absolute;right:-40px;bottom:-60px;width:220px;height:220px;border-radius:50%;background:rgba(0,0,0,.12);}
.promo-glow{text-shadow:0 0 40px rgba(255,200,100,.4);}
@keyframes pulse-badge{0%,100%{transform:scale(1);}50%{transform:scale(1.05);}}
.pulse{animation:pulse-badge 2s ease-in-out infinite;}

/* ── Phone mockup ── */
.phone-device{width:178px;background:#111827;border-radius:30px;padding:7px;box-shadow:0 30px 60px rgba(0,0,0,.4),0 0 0 1.5px #374151,inset 0 0 0 1px rgba(255,255,255,.04);flex-shrink:0;}
.phone-notch-bar{height:13px;display:flex;align-items:flex-end;justify-content:center;padding-bottom:1px;}
.phone-notch-bar::after{content:'';width:50px;height:11px;background:#111827;border-radius:0 0 10px 10px;display:block;}
.phone-screen{background:#fff;border-radius:22px;overflow:hidden;}
.ps-bar{background:#f8fafc;padding:2px 8px;display:flex;justify-content:space-between;align-items:center;font-size:5.5px;font-weight:700;color:#0f172a;}
.feature-card{background:#fff;border-radius:18px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.07);transition:transform .2s,box-shadow .2s;}
.feature-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px rgba(0,0,0,.12);}
.feat-label{font-size:.78rem;font-weight:700;color:#0f172a;margin-top:.75rem;margin-bottom:.2rem;text-align:center;}
.feat-desc{font-size:.72rem;color:#64748b;text-align:center;line-height:1.5;}
.feat-icon-pill{display:inline-flex;align-items:center;gap:.3rem;background:#fef2f0;color:#e84c2b;border-radius:99px;font-size:.65rem;font-weight:600;padding:.2rem .6rem;margin-bottom:.4rem;}

/* ── Compare table ── */
.compare-table{background:#fff;border-radius:20px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.06);}
.compare-table th{padding:1.1rem 1.25rem;font-size:.75rem;letter-spacing:.07em;text-transform:uppercase;}
.compare-table td{padding:.85rem 1.25rem;border-top:1px solid #f1f5f9;font-size:.88rem;vertical-align:middle;}
.col-free{background:#f8fafc;}.col-veri{background:linear-gradient(135deg,#fef2f0,#fff8f7);}
.th-free{color:#64748b;}.th-veri{color:var(--red);}
.cy{color:#22c55e;font-size:1.05rem;}.cn{color:#cbd5e1;font-size:1.05rem;}
.step-bubble{width:34px;height:34px;border-radius:50%;background:var(--red);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;flex-shrink:0;}
</style>

<!-- ═══════════════════════════════════════
     HERO
═══════════════════════════════════════ -->
<section class="gv-hero">
<div class="container">
<div class="row align-items-center g-5">
  <div class="col-lg-6">
    <span class="badge-pill"><i class="bi bi-clock me-1"></i>Limited-Time Offer Active</span>
    <h1 class="fw-bold mb-3" style="color:#fff;font-size:clamp(2rem,5vw,3rem);line-height:1.15;">
      Become a<br><span style="color:var(--red);">Verified Host</span>
    </h1>
    <p style="color:#94a3b8;font-size:1rem;max-width:440px;line-height:1.85;margin-bottom:1.75rem;">
      Unlock every premium tool — verified badge, direct WhatsApp, featured listing boosts, availability calendar, iCal sync, promotions &amp; more.
    </p>
    <div class="d-flex flex-column gap-2 mb-4">
      <?php $heroPerks = [
        ['bi-patch-check-fill','#22c55e','Verified badge on every listing'],
        ['bi-whatsapp','#25d366','Guests WhatsApp you directly'],
        ['bi-star-fill','#f59e0b','Featured listing eligible'],
        ['bi-calendar-check-fill','#3b82f6','Availability calendar & iCal sync'],
        ['bi-tag-fill','#e84c2b','Set promotions & long-stay discounts'],
      ]; foreach ($heroPerks as $p): ?>
      <div style="display:flex;align-items:center;gap:.6rem;">
        <i class="bi <?= $p[0] ?>" style="color:<?= $p[1] ?>;font-size:.95rem;flex-shrink:0;"></i>
        <span style="color:#e2e8f0;font-size:.88rem;"><?= $p[2] ?></span>
      </div>
      <?php endforeach; ?>
    </div>
    <?php if ($promoActive): ?>
    <div style="border-top:1px solid rgba(255,255,255,.08);padding-top:1.25rem;">
      <div style="color:#fca5a5;font-size:.78rem;font-weight:600;letter-spacing:.06em;margin-bottom:.5rem;">⚡ BUY NOW · GET 1 FREE MONTH FEATURED — EXPIRES IN:</div>
      <div class="timer-row" id="heroTimer">
        <div class="t-cell"><div class="t-num" id="cdH">--</div><div class="t-lab">Hrs</div></div>
        <div class="t-cell"><div class="t-num" id="cdM">--</div><div class="t-lab">Min</div></div>
        <div class="t-cell"><div class="t-num" id="cdS">--</div><div class="t-lab">Sec</div></div>
      </div>
    </div>
    <?php endif; ?>
  </div>

  <div class="col-lg-6">
    <div class="price-card">
      <div style="background:rgba(232,76,43,.2);color:#fca5a5;border:1px solid rgba(232,76,43,.3);border-radius:8px;font-size:.75rem;font-weight:600;padding:.3rem .75rem;display:inline-block;margin-bottom:1.25rem;">
        <i class="bi bi-star-fill me-1"></i>Annual Membership
      </div>
      <div class="d-flex align-items-baseline mb-1" style="position:relative;z-index:1;">
        <span style="font-size:3.2rem;font-weight:800;color:#fff;line-height:1;">RM<?= number_format($yearlyPrice, 0) ?></span>
        <span style="color:#94a3b8;margin-left:.5rem;">/year</span>
      </div>
      <div style="color:#64748b;font-size:.82rem;margin-bottom:1.5rem;">One-time yearly payment</div>

      <?php if ($promoActive): ?>
      <div style="background:rgba(232,76,43,.15);border:1px solid rgba(232,76,43,.3);border-radius:12px;padding:.875rem 1rem;margin-bottom:1.5rem;" class="pulse">
        <div style="color:#fca5a5;font-weight:700;font-size:.88rem;"><i class="bi bi-gift-fill me-1"></i>PROMO — 1 FREE MONTH FEATURED</div>
        <div style="color:#94a3b8;font-size:.78rem;margin-top:.2rem;">Buy within the timer above → pick any listing to feature free (worth RM19–RM35) after approval.</div>
      </div>
      <?php endif; ?>

      <div class="d-flex flex-column gap-2 mb-1" style="position:relative;z-index:1;">
        <?php $cardPerks=[
          ['bi-patch-check-fill','var(--red)','Verified badge on all listings'],
          ['bi-whatsapp','#25d366','Direct WhatsApp from guests'],
          ['bi-star-fill','#f59e0b','Featured listing boosts'],
          ['bi-calendar3','#3b82f6','Availability calendar + iCal'],
          ['bi-tag-fill','var(--red)','Promotions & long-stay pricing'],
          ['bi-share-fill','#94a3b8','Social media links on listing'],
        ]; foreach ($cardPerks as $p): ?>
        <div style="display:flex;align-items:center;gap:.6rem;">
          <i class="bi <?= $p[0] ?>" style="color:<?= $p[1] ?>;font-size:.9rem;flex-shrink:0;"></i>
          <span style="color:#cbd5e1;font-size:.82rem;"><?= $p[2] ?></span>
        </div>
        <?php endforeach; ?>
      </div>

      <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
         class="btn fw-bold d-block py-3 mt-4"
         style="background:var(--red);color:#fff;border-radius:12px;font-size:.95rem;position:relative;z-index:1;">
        <?= Auth::check() ? 'Apply for Verification' : 'Register & Get Verified' ?>
        <i class="bi bi-arrow-right ms-1"></i>
      </a>
      <?php if (!Auth::check()): ?>
      <div style="color:#475569;font-size:.72rem;text-align:center;margin-top:.6rem;position:relative;z-index:1;">
        Already have an account? <a href="/login" style="color:#94a3b8;">Login here</a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</div>
</div>
</section>

<!-- ═══════════════════════════════════════
     PROMO BANNER — MASSIVE
═══════════════════════════════════════ -->
<?php if ($promoActive): ?>
<section class="promo-mega">
<div class="container" style="position:relative;z-index:1;">
  <div class="row align-items-center g-4 justify-content-center text-center text-md-start">
    <div class="col-md-auto">
      <div style="width:80px;height:80px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;margin:0 auto;">
        <i class="bi bi-gift-fill" style="font-size:2.2rem;color:#fff;"></i>
      </div>
    </div>
    <div class="col-md">
      <div style="color:rgba(255,255,255,.7);font-size:.78rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;margin-bottom:.3rem;">Limited-Time Promo</div>
      <h2 class="fw-bold mb-1 promo-glow" style="color:#fff;font-size:clamp(1.6rem,4vw,2.6rem);line-height:1.1;">
        🎁 Get <span style="background:rgba(255,255,255,.2);padding:0 .3rem;border-radius:6px;">1 FREE MONTH</span> Featured Listing
      </h2>
      <p style="color:rgba(255,255,255,.75);margin-bottom:0;font-size:.95rem;">
        Worth up to <strong style="color:#fff;">RM35</strong> — automatically applied when you verify within the countdown.
        Your listing jumps to the <strong style="color:#fff;">#1 position</strong> in search results for 30 days.
      </p>
    </div>
    <div class="col-md-auto">
      <div style="background:rgba(0,0,0,.25);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:1rem 1.5rem;text-align:center;margin-bottom:.75rem;">
        <div style="color:rgba(255,255,255,.6);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;margin-bottom:.3rem;">Offer Expires In</div>
        <div style="display:flex;gap:.5rem;justify-content:center;align-items:center;">
          <div style="text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:#fff;line-height:1;font-variant-numeric:tabular-nums;" id="bH">--</div>
            <div style="font-size:.55rem;color:rgba(255,255,255,.5);text-transform:uppercase;">HRS</div>
          </div>
          <div style="color:rgba(255,255,255,.4);font-size:1.5rem;font-weight:300;margin-bottom:6px;">:</div>
          <div style="text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:#fff;line-height:1;font-variant-numeric:tabular-nums;" id="bM">--</div>
            <div style="font-size:.55rem;color:rgba(255,255,255,.5);text-transform:uppercase;">MIN</div>
          </div>
          <div style="color:rgba(255,255,255,.4);font-size:1.5rem;font-weight:300;margin-bottom:6px;">:</div>
          <div style="text-align:center;">
            <div style="font-size:2rem;font-weight:800;color:#fff;line-height:1;font-variant-numeric:tabular-nums;" id="bS">--</div>
            <div style="font-size:.55rem;color:rgba(255,255,255,.5);text-transform:uppercase;">SEC</div>
          </div>
        </div>
      </div>
      <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
         class="btn fw-bold d-block py-2"
         style="background:#fff;color:#e84c2b;border-radius:12px;font-size:.9rem;">
        Claim Free Month <i class="bi bi-arrow-right ms-1"></i>
      </a>
      <div style="color:rgba(255,255,255,.5);font-size:.68rem;text-align:center;margin-top:.4rem;">No code needed · Auto-applied</div>
    </div>
  </div>
</div>
</section>
<?php endif; ?>

<!-- ═══════════════════════════════════════
     FEATURE SHOWCASE — PHONE MOCKUPS
═══════════════════════════════════════ -->
<section class="py-5" style="background:#f0f4f8;">
<div class="container">
  <div class="text-center mb-5">
    <span style="background:#fef2f0;color:var(--red);border-radius:99px;font-size:.72rem;font-weight:700;padding:.3rem .9rem;letter-spacing:.07em;text-transform:uppercase;">Verified Host Features</span>
    <h2 class="fw-bold mt-2 mb-1" style="color:#0f172a;">Everything you unlock</h2>
    <p class="text-muted" style="max-width:480px;margin:0 auto;">Here's exactly how each feature looks on your listing — right on your guests' phones.</p>
  </div>

  <div class="row g-4 justify-content-center">

    <!-- ── Feature 1: Featured at top of search ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="phone-wrap d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <!-- mini search bar -->
              <div style="padding:4px;background:#f8fafc;">
                <div style="background:#e2e8f0;border-radius:5px;padding:3px 6px;font-size:5.5px;color:#94a3b8;display:flex;align-items:center;gap:2px;">
                  <span>🔍</span><span>Melaka homestay...</span>
                </div>
              </div>
              <!-- featured card - bright, prominent -->
              <div style="margin:4px;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(232,76,43,.25);border:1.5px solid #e84c2b;">
                <div style="position:relative;background:linear-gradient(135deg,#e84c2b,#c43e24);height:52px;display:flex;align-items:flex-end;padding:3px 4px;">
                  <span style="background:#fff;color:#e84c2b;font-size:4.5px;font-weight:800;padding:1px 3px;border-radius:2px;display:flex;align-items:center;gap:1px;">⭐ FEATURED</span>
                </div>
                <div style="background:#fff;padding:3px 5px;">
                  <div style="font-weight:800;font-size:6.5px;color:#0f172a;">The Cove Klebang</div>
                  <div style="color:#64748b;font-size:5.5px;">Melaka · RM 150/night</div>
                  <div style="color:#f59e0b;font-size:6px;margin-top:1px;">★★★★★</div>
                </div>
              </div>
              <!-- regular cards below, faded -->
              <?php foreach ([['#cbd5e1','Villa Indah','KL · RM 200'],['#94a3b8','Rumah Damai','Selangor']] as $rc): ?>
              <div style="margin:3px 4px;border-radius:6px;overflow:hidden;opacity:.5;">
                <div style="background:<?= $rc[0] ?>;height:30px;"></div>
                <div style="background:#f8fafc;padding:2px 4px;">
                  <div style="font-size:5.5px;color:#64748b;"><?= $rc[1] ?> · <?= $rc[2] ?></div>
                </div>
              </div>
              <?php endforeach; ?>
              <div style="height:12px;"></div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-star-fill"></i> Featured Boost</div>
        <div class="feat-label">Appear #1 in Search</div>
        <div class="feat-desc">Your listing sits above all others — more views, more enquiries.</div>
      </div>
    </div>

    <!-- ── Feature 2: Social Links ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <!-- mini navbar -->
              <div style="background:var(--red);padding:4px 6px;display:flex;align-items:center;gap:3px;">
                <span style="color:#fff;font-size:5px;">← Villa Indah</span>
              </div>
              <!-- photo placeholder -->
              <div style="background:linear-gradient(135deg,#1e3a5f,#2d5a8e);height:48px;display:flex;align-items:center;justify-content:center;">
                <span style="color:rgba(255,255,255,.4);font-size:10px;">🏠</span>
              </div>
              <!-- social links section -->
              <div style="padding:5px 6px;">
                <div style="font-size:5.5px;font-weight:700;color:#64748b;letter-spacing:.07em;margin-bottom:4px;">CONNECT WITH HOST</div>
                <?php foreach ([
                  ['#1877f2','f','Facebook'],
                  ['#e1306c','📷','Instagram'],
                  ['#0f172a','🌐','Website'],
                ] as $sl): ?>
                <div style="display:flex;align-items:center;justify-content:space-between;background:#f8fafc;border-radius:5px;padding:3px 5px;margin-bottom:2px;">
                  <div style="display:flex;align-items:center;gap:3px;">
                    <span style="font-size:8px;"><?= $sl[1] ?></span>
                    <span style="font-size:5.5px;color:#0f172a;font-weight:600;"><?= $sl[2] ?></span>
                  </div>
                  <span style="color:#94a3b8;font-size:6px;">→</span>
                </div>
                <?php endforeach; ?>
                <div style="margin-top:5px;background:#25d366;border-radius:5px;padding:3px 5px;text-align:center;">
                  <span style="color:#fff;font-size:5.5px;font-weight:700;">WhatsApp Host</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-share-fill"></i> Social Links</div>
        <div class="feat-label">Link Your Social Media</div>
        <div class="feat-desc">Add Facebook, Instagram and website to your listing page.</div>
      </div>
    </div>

    <!-- ── Feature 3: Tiered Pricing ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <div style="background:var(--red);padding:4px 6px;">
                <span style="color:#fff;font-size:5px;">← The Loft PJ</span>
              </div>
              <div style="background:linear-gradient(135deg,#4c1d95,#7c3aed);height:44px;display:flex;align-items:center;justify-content:center;">
                <span style="color:rgba(255,255,255,.4);font-size:10px;">🏠</span>
              </div>
              <div style="padding:5px 6px;">
                <div style="font-size:5.5px;font-weight:700;color:#64748b;letter-spacing:.07em;margin-bottom:4px;">NIGHTLY RATES</div>
                <?php foreach ([
                  ['1 night','RM 150','#f1f5f9','#0f172a','',''],
                  ['2 nights','RM 130','#eff6ff','#1d4ed8','↓ Save 13%','#93c5fd'],
                  ['3+ nights','RM 110','#f0fdf4','#15803d','★ Best Value','#86efac'],
                ] as $tp): ?>
                <div style="background:<?= $tp[2] ?>;border-radius:5px;padding:3px 5px;margin-bottom:2px;display:flex;justify-content:space-between;align-items:center;">
                  <div>
                    <div style="font-size:5.5px;font-weight:700;color:<?= $tp[3] ?>"><?= $tp[0] ?></div>
                    <?php if ($tp[4]): ?><div style="font-size:4.5px;color:<?= $tp[5] ?>"><?= $tp[4] ?></div><?php endif; ?>
                  </div>
                  <div style="font-size:6px;font-weight:800;color:<?= $tp[3] ?>"><?= $tp[1] ?>/nt</div>
                </div>
                <?php endforeach; ?>
                <div style="margin-top:5px;background:#e84c2b;border-radius:5px;padding:3px 5px;text-align:center;">
                  <span style="color:#fff;font-size:5.5px;font-weight:700;">Book 3 Nights — RM 330</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-tags-fill"></i> Long-Stay Pricing</div>
        <div class="feat-label">Reward Longer Stays</div>
        <div class="feat-desc">Set 2-night and 3-night discounts automatically shown to guests.</div>
      </div>
    </div>

    <!-- ── Feature 4: Promotions ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <div style="padding:4px;background:#f8fafc;">
                <div style="background:#e2e8f0;border-radius:5px;padding:3px 6px;font-size:5.5px;color:#94a3b8;">🔍 Search...</div>
              </div>
              <!-- Card with promo ribbon -->
              <div style="margin:4px;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1);position:relative;">
                <div style="background:linear-gradient(135deg,#0ea5e9,#0284c7);height:58px;position:relative;overflow:hidden;">
                  <!-- Promo ribbon -->
                  <div style="position:absolute;top:6px;right:-10px;background:#e84c2b;color:#fff;font-size:5px;font-weight:800;padding:2px 14px;transform:rotate(20deg);">20% OFF</div>
                </div>
                <div style="background:#fff;padding:4px 5px;">
                  <div style="display:flex;align-items:baseline;gap:3px;">
                    <span style="font-size:5px;color:#94a3b8;text-decoration:line-through;">RM 180</span>
                    <span style="font-size:7px;font-weight:800;color:#e84c2b;">RM 144</span>
                    <span style="font-size:5px;color:#64748b;">/night</span>
                  </div>
                  <div style="font-weight:700;font-size:6.5px;color:#0f172a;">Rumah Damai Seremban</div>
                  <div style="display:flex;align-items:center;gap:2px;margin-top:1px;">
                    <span style="background:#fef2f0;color:#e84c2b;font-size:4.5px;font-weight:700;padding:1px 3px;border-radius:2px;">PROMO</span>
                    <span style="color:#94a3b8;font-size:4.5px;">Ends 30 Jun</span>
                  </div>
                </div>
              </div>
              <!-- second card no promo -->
              <div style="margin:3px 4px;border-radius:6px;overflow:hidden;opacity:.5;">
                <div style="background:#cbd5e1;height:32px;"></div>
                <div style="background:#f8fafc;padding:2px 4px;font-size:5.5px;color:#64748b;">Villa Indah · RM 200/night</div>
              </div>
              <div style="height:8px;"></div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-percent"></i> Promotions</div>
        <div class="feat-label">Run Time-Limited Deals</div>
        <div class="feat-desc">Add discount badges to your listing card — guests love a deal.</div>
      </div>
    </div>

    <!-- ── Feature 5: Availability Calendar ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <div style="background:var(--red);padding:4px 8px;display:flex;justify-content:space-between;align-items:center;">
                <span style="color:#fff;font-size:5px;">← Availability</span>
                <span style="color:rgba(255,255,255,.7);font-size:5px;">June 2025</span>
              </div>
              <div style="padding:5px 6px;">
                <!-- day headers -->
                <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;margin-bottom:2px;">
                  <?php foreach(['S','M','T','W','T','F','S'] as $d): ?>
                  <div style="text-align:center;font-size:5px;font-weight:700;color:#64748b;"><?= $d ?></div>
                  <?php endforeach; ?>
                </div>
                <!-- calendar grid -->
                <?php
                $blocked = [10,11,15,16,17,22,23];
                $days = [['','','','','','','6'],['7','8','9','10','11','12','13'],['14','15','16','17','18','19','20'],['21','22','23','24','25','26','27'],['28','29','30','','','','']];
                foreach($days as $week): ?>
                <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:1px;margin-bottom:1px;">
                  <?php foreach($week as $d): ?>
                  <?php $bl = $d && in_array((int)$d, $blocked); ?>
                  <div style="text-align:center;font-size:5.5px;border-radius:3px;padding:2px 0;background:<?= $d ? ($bl ? '#fef2f2' : '#f0fdf4') : 'transparent' ?>;color:<?= $d ? ($bl ? '#dc2626' : '#16a34a') : 'transparent' ?>;font-weight:<?= $bl ? '700' : '400' ?>;">
                    <?= $d ? ($bl ? '✕' : $d) : '·' ?>
                  </div>
                  <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
                <!-- legend -->
                <div style="display:flex;gap:6px;justify-content:center;margin-top:4px;">
                  <div style="display:flex;align-items:center;gap:2px;font-size:4.5px;color:#16a34a;"><span style="width:5px;height:5px;background:#f0fdf4;border-radius:1px;display:block;"></span>Available</div>
                  <div style="display:flex;align-items:center;gap:2px;font-size:4.5px;color:#dc2626;"><span style="width:5px;height:5px;background:#fef2f2;border-radius:1px;display:block;"></span>Blocked</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-calendar3"></i> Calendar</div>
        <div class="feat-label">Block Dates Easily</div>
        <div class="feat-desc">Tap any date to mark it unavailable — guests see real availability.</div>
      </div>
    </div>

    <!-- ── Feature 6: iCal Sync ── -->
    <div class="col-6 col-md-4 col-xl-2">
      <div class="feature-card p-3 h-100">
        <div class="d-flex justify-content-center">
          <div class="phone-device">
            <div class="phone-notch-bar"></div>
            <div class="phone-screen">
              <div class="ps-bar"><span>9:41</span><span>🔋</span></div>
              <div style="background:var(--red);padding:4px 6px;">
                <span style="color:#fff;font-size:5px;">← Calendar Sync</span>
              </div>
              <div style="padding:5px 6px;">
                <div style="font-size:5.5px;font-weight:700;color:#64748b;letter-spacing:.07em;margin-bottom:4px;">CONNECTED PLATFORMS</div>
                <!-- Airbnb-like -->
                <div style="background:#fff5f5;border-radius:5px;padding:3px 5px;margin-bottom:2px;display:flex;justify-content:space-between;align-items:center;border:1px solid #fed7d7;">
                  <div style="display:flex;align-items:center;gap:3px;">
                    <span style="font-size:8px;">🅰</span>
                    <span style="font-size:5.5px;font-weight:700;color:#0f172a;">Airbnb</span>
                  </div>
                  <span style="background:#f0fdf4;color:#16a34a;font-size:4.5px;font-weight:700;padding:1px 3px;border-radius:2px;">✓ Synced</span>
                </div>
                <!-- Booking.com-like -->
                <div style="background:#f0f7ff;border-radius:5px;padding:3px 5px;margin-bottom:4px;display:flex;justify-content:space-between;align-items:center;border:1px solid #bfdbfe;">
                  <div style="display:flex;align-items:center;gap:3px;">
                    <span style="font-size:8px;">🅱</span>
                    <span style="font-size:5.5px;font-weight:700;color:#0f172a;">Booking.com</span>
                  </div>
                  <span style="background:#f0fdf4;color:#16a34a;font-size:4.5px;font-weight:700;padding:1px 3px;border-radius:2px;">✓ Synced</span>
                </div>
                <div style="font-size:5.5px;font-weight:700;color:#64748b;letter-spacing:.07em;margin-bottom:3px;">ADD CALENDAR</div>
                <div style="background:#f8fafc;border-radius:4px;padding:3px 5px;border:1px solid #e2e8f0;font-size:5px;color:#94a3b8;margin-bottom:3px;">Paste .ics URL here...</div>
                <div style="background:#e84c2b;border-radius:4px;padding:2px;text-align:center;">
                  <span style="color:#fff;font-size:5px;font-weight:700;">🔄 Sync Now</span>
                </div>
                <div style="margin-top:4px;font-size:4.5px;color:#94a3b8;text-align:center;">Last synced: 2 hours ago</div>
              </div>
            </div>
          </div>
        </div>
        <div class="feat-icon-pill mt-3"><i class="bi bi-arrow-repeat"></i> iCal Sync</div>
        <div class="feat-label">Sync with Airbnb & More</div>
        <div class="feat-desc">Import your Airbnb or Booking.com calendar — no double bookings.</div>
      </div>
    </div>

  </div><!-- /row -->
</div>
</section>

<!-- ═══════════════════════════════════════
     COMPARISON TABLE
═══════════════════════════════════════ -->
<section class="py-5" style="background:#fff;">
<div class="container">
  <div class="text-center mb-4">
    <h2 class="fw-bold" style="color:#0f172a;">Free vs <span style="color:var(--red);">Verified Host</span></h2>
    <p class="text-muted">Every feature, side by side.</p>
  </div>
  <div class="compare-table table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th style="background:#fff;border:none;width:45%;">Feature</th>
          <th class="col-free th-free text-center border-0">Free Owner</th>
          <th class="col-veri th-veri text-center border-0"><i class="bi bi-patch-check-fill me-1"></i>Verified Host</th>
        </tr>
      </thead>
      <tbody>
        <?php $rows = [
          ['Max listings',                  '3 listings',   'Unlimited'],
          ['Listing approval',              'Manual review','Priority review'],
          ['Verified badge on listing',     false,          true],
          ['Direct WhatsApp contact',       false,          true],
          ['Search ranking',                'Standard',     'Higher priority'],
          ['Featured listing eligible',     false,          true],
          ['Social media links (FB/IG/Web)',false,          true],
          ['Long-stay tiered pricing',      false,          true],
          ['Promotions & discount badges',  false,          true],
          ['Availability calendar',         false,          true],
          ['iCal sync (Airbnb / Booking.com)',false,        true],
          ['Guest trust & credibility',     'Basic',        'High — verified identity'],
          ['Priority support',              false,          true],
          ['Annual price',                  'Free',         'RM49 / year'],
        ]; foreach ($rows as $r): ?>
        <tr>
          <td class="fw-medium" style="color:#0f172a;"><?= $r[0] ?></td>
          <td class="col-free text-center">
            <?php if ($r[1]===true): ?><i class="bi bi-check-circle-fill cy"></i>
            <?php elseif ($r[1]===false): ?><i class="bi bi-x-circle cn"></i>
            <?php else: ?><span class="text-muted small"><?= $r[1] ?></span><?php endif; ?>
          </td>
          <td class="col-veri text-center">
            <?php if ($r[2]===true): ?><i class="bi bi-check-circle-fill cy"></i>
            <?php elseif ($r[2]===false): ?><i class="bi bi-x-circle cn"></i>
            <?php else: ?><span style="color:var(--red);font-weight:700;font-size:.88rem;"><?= $r[2] ?></span><?php endif; ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="text-center mt-5">
    <a href="<?= Auth::check() ? '/owner/verify' : '/register' ?>"
       class="btn btn-lg fw-bold px-5 py-3"
       style="background:var(--red);color:#fff;border-radius:14px;">
      Get Verified — RM49/year <i class="bi bi-arrow-right ms-1"></i>
    </a>
    <div class="text-muted small mt-2">Documents reviewed within 24 hours · No hidden fees</div>
  </div>
</div>
</section>

<!-- ═══════════════════════════════════════
     HOW IT WORKS
═══════════════════════════════════════ -->
<section class="py-5" style="background:#f8fafc;">
<div class="container">
  <div class="text-center mb-5">
    <h2 class="fw-bold" style="color:#0f172a;">How It Works</h2>
    <p class="text-muted">Three steps to unlock everything.</p>
  </div>
  <div class="row justify-content-center g-4">
    <?php $steps=[
      ['1','bi-cloud-upload','Upload Documents','Submit your IC (individual) or SSM certificate (company). Stored securely — only our team sees it.'],
      ['2','bi-credit-card','Pay RM49/year','Secure payment via BillPlz. Your documents go into review immediately after payment.'],
      ['3','bi-patch-check-fill','Go Live as Verified Host','Approved within 24 hours. Badge goes live, all features unlock, promo featured month activates.'],
    ]; foreach ($steps as $i => $s): ?>
    <div class="col-12 col-md-4">
      <div class="text-center px-2">
        <div class="d-flex align-items-center justify-content-center mb-3">
          <div class="step-bubble"><?= $s[0] ?></div>
          <?php if ($i < 2): ?>
          <div style="height:2px;flex:1;background:#e2e8f0;max-width:50px;margin:0 .5rem;" class="d-none d-md-block"></div>
          <?php endif; ?>
        </div>
        <div style="width:52px;height:52px;border-radius:14px;background:#fef2f0;display:flex;align-items:center;justify-content:center;margin:0 auto .875rem;">
          <i class="bi <?= $s[1] ?>" style="color:var(--red);font-size:1.4rem;"></i>
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
  const ids = [['cdH','bH'],['cdM','bM'],['cdS','bS']];
  function tick(){
    const diff = Math.max(0, Math.floor((endsAt - Date.now()) / 1000));
    const h = Math.floor(diff / 3600);
    const m = Math.floor((diff % 3600) / 60);
    const s = diff % 60;
    const vals = [h, m, s];
    ids.forEach(([a, b], i) => {
      const v = String(vals[i]).padStart(2,'0');
      if(document.getElementById(a)) document.getElementById(a).textContent = v;
      if(document.getElementById(b)) document.getElementById(b).textContent = v;
    });
    if(diff === 0) clearInterval(timer);
  }
  tick();
  const timer = setInterval(tick, 1000);
})();
</script>
<?php endif; ?>
