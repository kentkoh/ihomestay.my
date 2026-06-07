<style>
.feat-hero{background:linear-gradient(135deg,#0f1923 0%,#1a1f2e 50%,#0f1923 100%);padding:5rem 0 4rem;position:relative;overflow:hidden;}
.feat-hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 80% 60% at 50% 0%,rgba(232,76,43,.18),transparent);}
.stat-pill{display:inline-flex;align-items:center;gap:.5rem;background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:999px;padding:.45rem 1.1rem;font-size:.88rem;color:#cbd5e1;}
.vs-card{border-radius:16px;overflow:hidden;box-shadow:0 8px 40px rgba(0,0,0,.15);}
.vs-card .vc-img{height:160px;object-fit:cover;width:100%;background:linear-gradient(135deg,#dde3ea,#c5ced8);}
.vs-featured{box-shadow:0 8px 40px rgba(232,76,43,.3),0 0 0 2px #e84c2b;}
.feat-ribbon{position:absolute;top:16px;left:-28px;background:#e84c2b;color:#fff;font-size:.72rem;font-weight:700;padding:.3rem 2.4rem;transform:rotate(-45deg);letter-spacing:.08em;}
.benefit-card{background:#fff;border-radius:16px;padding:1.75rem 1.5rem;box-shadow:0 2px 20px rgba(0,0,0,.06);transition:transform .2s,box-shadow .2s;border:1px solid #f1f5f9;}
.benefit-card:hover{transform:translateY(-4px);box-shadow:0 8px 32px rgba(0,0,0,.1);}
.benefit-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;margin-bottom:1rem;}
.pkg-card{border-radius:20px;padding:2rem 1.75rem;background:#fff;box-shadow:0 4px 24px rgba(0,0,0,.08);border:2px solid #f1f5f9;transition:transform .2s,box-shadow .2s,border-color .2s;position:relative;overflow:hidden;}
.pkg-card:hover{transform:translateY(-6px);box-shadow:0 12px 40px rgba(0,0,0,.12);}
.pkg-card.best{border-color:#e84c2b;background:linear-gradient(160deg,#fff 70%,#fff9f8 100%);}
.pkg-best-badge{position:absolute;top:0;right:0;background:#e84c2b;color:#fff;font-size:.72rem;font-weight:700;padding:.35rem 1rem;border-radius:0 18px 0 12px;letter-spacing:.05em;}
.pkg-price{font-size:2.4rem;font-weight:800;color:#0f172a;line-height:1;}
.pkg-price sup{font-size:1rem;font-weight:600;vertical-align:top;margin-top:.4rem;}
.pkg-price .duration{font-size:.85rem;font-weight:400;color:#64748b;}
.pkg-normal{text-decoration:line-through;color:#94a3b8;font-size:.9rem;}
.pkg-promo-badge{background:#fef2f2;color:#e84c2b;font-size:.75rem;font-weight:700;padding:.2rem .6rem;border-radius:999px;}
.faq-item{border:1px solid #e2e8f0;border-radius:12px;margin-bottom:.75rem;overflow:hidden;}
.faq-q{padding:1.1rem 1.25rem;font-weight:600;cursor:pointer;background:#fff;color:#0f172a;display:flex;justify-content:space-between;align-items:center;font-size:.95rem;}
.faq-q:hover{background:#f8fafc;}
.faq-a{padding:0 1.25rem 1.1rem;color:#475569;font-size:.93rem;line-height:1.8;}
</style>

<!-- HERO -->
<div class="feat-hero">
    <div class="container position-relative" style="z-index:1;">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="mb-3">
                    <span class="badge px-3 py-2" style="background:rgba(232,76,43,.25);color:#fca5a5;letter-spacing:.08em;font-size:.78rem;">
                        ⭐ FEATURED LISTING
                    </span>
                </div>
                <h1 style="color:#fff;font-size:clamp(2rem,5vw,3rem);font-weight:800;line-height:1.2;">
                    Get More Bookings.<br>
                    <span style="color:#e84c2b;">Stand Out</span> From the Rest.
                </h1>
                <p style="color:#94a3b8;font-size:1.05rem;margin-top:1.2rem;max-width:480px;line-height:1.8;">
                    Featured listings appear at the <strong style="color:#cbd5e1;">top of every search result</strong> and on the homepage — giving your homestay maximum exposure to guests actively searching.
                </p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <span class="stat-pill"><i class="bi bi-graph-up-arrow" style="color:#e84c2b;"></i> 3× More Views</span>
                    <span class="stat-pill"><i class="bi bi-whatsapp" style="color:#25d366;"></i> 5× More Clicks</span>
                    <span class="stat-pill"><i class="bi bi-patch-check-fill" style="color:#f59e0b;"></i> Featured Badge</span>
                </div>
                <div class="mt-4 p-3 rounded-3 d-inline-flex align-items-center gap-3"
                     style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);">
                    <img src="<?php
                        $img = $listing['primary_image'] ?? null;
                        echo $img ? '/uploads/listings/' . $listing['id'] . '/' . htmlspecialchars($img) : '/assets/placeholder.jpg';
                    ?>" style="width:48px;height:48px;border-radius:10px;object-fit:cover;" alt="">
                    <div>
                        <div style="color:#fff;font-weight:600;font-size:.9rem;"><?= htmlspecialchars($listing['title']) ?></div>
                        <div style="color:#64748b;font-size:.8rem;">Selected listing</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <!-- Visual comparison -->
                <div class="row g-3 align-items-center">
                    <div class="col-6">
                        <div style="color:#64748b;font-size:.78rem;font-weight:600;text-align:center;letter-spacing:.06em;margin-bottom:.6rem;">NORMAL LISTING</div>
                        <div class="vs-card" style="border-radius:16px;overflow:hidden;background:#fff;">
                            <div class="vc-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#e2e8f0,#cbd5e1);">
                                <i class="bi bi-house fs-1 text-muted opacity-50"></i>
                            </div>
                            <div style="padding:.85rem;">
                                <div style="background:#e2e8f0;height:10px;border-radius:4px;width:80%;margin-bottom:.5rem;"></div>
                                <div style="background:#f1f5f9;height:8px;border-radius:4px;width:55%;margin-bottom:.75rem;"></div>
                                <div style="background:#f1f5f9;height:22px;border-radius:6px;width:45%;"></div>
                            </div>
                        </div>
                        <div class="text-center mt-2" style="color:#64748b;font-size:.78rem;">Buried in results</div>
                    </div>
                    <div class="col-6">
                        <div style="color:#e84c2b;font-size:.78rem;font-weight:700;text-align:center;letter-spacing:.06em;margin-bottom:.6rem;">✦ FEATURED LISTING</div>
                        <div class="vs-card vs-featured position-relative" style="border-radius:16px;overflow:hidden;background:#fff;">
                            <div style="overflow:hidden;">
                                <div class="feat-ribbon">FEATURED</div>
                            </div>
                            <div class="vc-img d-flex align-items-center justify-content-center" style="background:linear-gradient(135deg,#fde8e4,#fca5a5);">
                                <i class="bi bi-house-heart fs-1" style="color:#e84c2b;opacity:.6;"></i>
                            </div>
                            <div style="padding:.85rem;">
                                <div style="background:#fde8e4;height:10px;border-radius:4px;width:80%;margin-bottom:.5rem;"></div>
                                <div style="background:#fef2f0;height:8px;border-radius:4px;width:55%;margin-bottom:.75rem;"></div>
                                <div style="background:#e84c2b;height:22px;border-radius:6px;width:45%;"></div>
                            </div>
                        </div>
                        <div class="text-center mt-2" style="color:#e84c2b;font-size:.78rem;font-weight:600;">Top of search results</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- BENEFITS -->
<div style="background:#f8fafc;padding:4.5rem 0;">
    <div class="container">
        <div class="text-center mb-5">
            <span style="color:#e84c2b;font-size:.82rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;">Why Feature?</span>
            <h2 class="fw-bold mt-2" style="color:#0f172a;font-size:clamp(1.5rem,3vw,2rem);">Everything Working in Your Favour</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="benefit-card h-100">
                    <div class="benefit-icon" style="background:#fef2f0;">
                        <i class="bi bi-pin-map-fill" style="color:#e84c2b;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#0f172a;">Top of Search Results</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7;">Featured listings always appear above regular listings — guests see yours first.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="benefit-card h-100">
                    <div class="benefit-icon" style="background:#fef9ec;">
                        <i class="bi bi-award-fill" style="color:#f59e0b;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#0f172a;">Eye-Catching Featured Badge</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7;">A prominent "Featured" ribbon on your listing card builds trust and grabs attention instantly.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="benefit-card h-100">
                    <div class="benefit-icon" style="background:#f0fdf4;">
                        <i class="bi bi-house-heart-fill" style="color:#22c55e;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#0f172a;">Homepage Placement</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7;">Featured listings rotate on the ihomestay.my homepage — reaching guests who haven't searched yet.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="benefit-card h-100">
                    <div class="benefit-icon" style="background:#eff6ff;">
                        <i class="bi bi-whatsapp" style="color:#25d366;"></i>
                    </div>
                    <h6 class="fw-bold" style="color:#0f172a;">More Enquiries</h6>
                    <p class="text-muted small mb-0" style="line-height:1.7;">More views means more WhatsApp enquiries — direct contact with guests, no platform fees.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- PACKAGES -->
<div style="background:#fff;padding:4.5rem 0;" id="packages">
    <div class="container">
        <div class="text-center mb-5">
            <span style="color:#e84c2b;font-size:.82rem;font-weight:700;letter-spacing:.12em;text-transform:uppercase;">Pricing</span>
            <h2 class="fw-bold mt-2" style="color:#0f172a;font-size:clamp(1.5rem,3vw,2rem);">Choose Your Boost Duration</h2>
            <p class="text-muted" style="max-width:460px;margin:0.75rem auto 0;">One-time payment. Starts immediately after payment. Cancel anytime by contacting us.</p>
        </div>

        <div class="row g-4 justify-content-center">
            <?php foreach ($packages as $i => $pkg):
                $isBest     = $pkg['days'] == 14;
                $hasPromo   = $packageModel->hasPromo($pkg);
                $finalPrice = $packageModel->effectivePrice($pkg);
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="pkg-card h-100 <?= $isBest ? 'best' : '' ?>">
                    <?php if ($isBest): ?>
                        <div class="pkg-best-badge">BEST VALUE</div>
                    <?php endif; ?>

                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div style="width:36px;height:36px;border-radius:10px;background:<?= $isBest ? '#fef2f0' : '#f8fafc' ?>;display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-lightning-charge-fill" style="color:<?= $isBest ? '#e84c2b' : '#94a3b8' ?>;font-size:1rem;"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="color:#0f172a;"><?= htmlspecialchars($pkg['label']) ?></div>
                            <div style="color:#64748b;font-size:.8rem;"><?= $pkg['days'] ?> days of featured placement</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <?php if ($hasPromo): ?>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="pkg-normal">RM<?= number_format($pkg['normal_price'], 2) ?></span>
                                <span class="pkg-promo-badge">PROMO</span>
                            </div>
                        <?php endif; ?>
                        <div class="pkg-price">
                            <sup>RM</sup><?= number_format($finalPrice, 0) ?>
                            <span class="duration"> / <?= $pkg['days'] ?> days</span>
                        </div>
                        <div style="color:#94a3b8;font-size:.8rem;margin-top:.35rem;">
                            ~RM<?= number_format($finalPrice / $pkg['days'], 2) ?>/day
                        </div>
                    </div>

                    <ul class="list-unstyled mb-4" style="font-size:.88rem;color:#475569;">
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill" style="color:#22c55e;flex-shrink:0;"></i>
                            Top of search results for <?= $pkg['days'] ?> days
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill" style="color:#22c55e;flex-shrink:0;"></i>
                            Featured badge on listing card
                        </li>
                        <li class="d-flex align-items-center gap-2 mb-2">
                            <i class="bi bi-check-circle-fill" style="color:#22c55e;flex-shrink:0;"></i>
                            Homepage placement included
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <i class="bi bi-check-circle-fill" style="color:#22c55e;flex-shrink:0;"></i>
                            Starts immediately after payment
                        </li>
                    </ul>

                    <form method="POST" action="/feature/<?= $listing['id'] ?>/checkout">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="package_id" value="<?= $pkg['id'] ?>">
                        <button type="submit" class="btn w-100 fw-bold py-2"
                                style="background:<?= $isBest ? '#e84c2b' : '#0f1923' ?>;color:#fff;border-radius:12px;font-size:.95rem;">
                            <i class="bi bi-lightning-charge-fill me-1"></i>
                            Feature for <?= $pkg['days'] ?> Days — RM<?= number_format($finalPrice, 0) ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-muted small mt-4">
            <i class="bi bi-lock-fill me-1"></i>
            Secure payment via BillPlz · FPX, Credit/Debit Card accepted · No subscription, pay once
        </p>
    </div>
</div>

<!-- FAQ -->
<div style="background:#f8fafc;padding:4rem 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <h3 class="fw-bold text-center mb-4" style="color:#0f172a;">Common Questions</h3>

                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        When does my featured listing start?
                        <i class="bi bi-chevron-down" style="transition:transform .2s;"></i>
                    </div>
                    <div class="faq-a" style="display:none;">
                        Immediately after your payment is confirmed. Your listing will appear at the top of search results and on the homepage within minutes.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What payment methods are accepted?
                        <i class="bi bi-chevron-down" style="transition:transform .2s;"></i>
                    </div>
                    <div class="faq-a" style="display:none;">
                        We use BillPlz as our payment gateway, which supports FPX (online banking for all major Malaysian banks), credit cards, and debit cards.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        What happens when the featured period ends?
                        <i class="bi bi-chevron-down" style="transition:transform .2s;"></i>
                    </div>
                    <div class="faq-a" style="display:none;">
                        Your listing automatically returns to the standard listing pool. Your listing remains live and active — it just no longer has priority placement. You can purchase a new featured package at any time to boost it again.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Can I feature more than one listing?
                        <i class="bi bi-chevron-down" style="transition:transform .2s;"></i>
                    </div>
                    <div class="faq-a" style="display:none;">
                        Yes. Each listing can be featured independently. Go to My Listings and click "Feature This Listing" on any published listing you'd like to boost.
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-q" onclick="toggleFaq(this)">
                        Is there a refund if I change my mind?
                        <i class="bi bi-chevron-down" style="transition:transform .2s;"></i>
                    </div>
                    <div class="faq-a" style="display:none;">
                        Featured listing fees are non-refundable once the featured period has started. If you experience a technical issue, please contact us at admin@ihomestay.my and we will review your case.
                    </div>
                </div>

                <div class="text-center mt-4">
                    <p class="text-muted small">Still have questions? <a href="/contact" style="color:#e84c2b;">Contact us</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFaq(el) {
    const answer = el.nextElementSibling;
    const icon   = el.querySelector('.bi-chevron-down, .bi-chevron-up');
    const isOpen = answer.style.display !== 'none';
    answer.style.display = isOpen ? 'none' : 'block';
    if (icon) {
        icon.className = isOpen ? 'bi bi-chevron-down' : 'bi bi-chevron-up';
        icon.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
    }
}
</script>
