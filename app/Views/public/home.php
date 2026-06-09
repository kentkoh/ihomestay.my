<style>
/* ── Animations ── */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(32px); }
    to   { opacity: 1; transform: translateY(0); }
}
.fade-up        { opacity: 0; transform: translateY(32px); transition: opacity .6s ease, transform .6s ease; }
.fade-up.visible { opacity: 1; transform: translateY(0); }

/* ── Hero ── */
.hero {
    background: url('/assets/hero.png') center center / cover no-repeat;
    min-height: 92vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: linear-gradient(to right,
        rgba(6,12,20,0.82) 0%,
        rgba(6,12,20,0.68) 45%,
        rgba(6,12,20,0.38) 100%);
    pointer-events: none;
}
@media (max-width: 991px) {
    .hero::before {
        background: rgba(6,12,20,0.72);
    }
}
.hero-badge {
    display: inline-block;
    background: rgba(232,76,43,.15);
    border: 1px solid rgba(232,76,43,.4);
    color: #e84c2b;
    border-radius: 50px;
    padding: 5px 16px;
    font-size: .8rem;
    font-weight: 600;
    letter-spacing: .05em;
    animation: fadeUp .6s ease .1s both;
}
.hero-title {
    font-size: clamp(2.2rem, 5vw, 4rem);
    font-weight: 800;
    color: #fff;
    line-height: 1.12;
    animation: fadeUp .6s ease .25s both;
}
.hero-title span { color: #e84c2b; }
.hero-sub {
    color: rgba(255,255,255,.82);
    font-size: clamp(1rem, 1.8vw, 1.2rem);
    animation: fadeUp .6s ease .4s both;
}
.hero-search { animation: fadeUp .6s ease .55s both; }
.hero-search .form-control, .hero-search .form-select {
    border: none;
    border-radius: 8px;
    padding: .75rem 1rem;
    font-size: .95rem;
}
.hero-stats { animation: fadeUp .6s ease .7s both; }
.hero-stat-num { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; }
.hero-stat-lbl { font-size: .75rem; color: rgba(255,255,255,.55); }

/* ── Value strip ── */
.value-strip { background: #fff; border-bottom: 1px solid #f1f5f9; }
.value-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    background: rgba(232,76,43,.1);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    color: #e84c2b;
    flex-shrink: 0;
}

/* ── Section headings ── */
.section-label {
    font-size: .75rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; color: #e84c2b;
}

/* ── Listing cards ── */
.listing-card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    transition: transform .25s, box-shadow .25s;
    background: #fff;
}
.featured-ribbon { position:absolute; top:18px; right:-30px; background:#e84c2b; color:#fff; font-size:.62rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; padding:5px 44px; transform:rotate(45deg); z-index:3; box-shadow:0 2px 8px rgba(0,0,0,.25); pointer-events:none; }
.verified-badge { position:absolute; bottom:8px; left:8px; background:#16a34a; color:#fff; border-radius:99px; padding:3px 9px; font-size:.62rem; font-weight:700; display:flex; align-items:center; gap:3px; z-index:2; box-shadow:0 1px 6px rgba(0,0,0,.25); }
.promo-badge { position:absolute; top:8px; left:8px; background:#f59e0b; color:#fff; border-radius:6px; padding:3px 8px; font-size:.62rem; font-weight:800; z-index:2; box-shadow:0 1px 6px rgba(0,0,0,.25); letter-spacing:.04em; }
.listing-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }
.listing-thumb { height: 200px; object-fit: cover; width: 100%; background: #e2e8f0; }
.listing-thumb-placeholder {
    height: 200px; background: linear-gradient(135deg,#e2e8f0,#f1f5f9);
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 2.5rem;
}
.listing-price { font-size: 1.15rem; font-weight: 700; color: #e84c2b; }

/* ── State grid ── */
.state-btn {
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    padding: .6rem 1rem;
    background: #fff;
    color: #334155;
    font-size: .875rem;
    font-weight: 500;
    text-decoration: none;
    display: flex; align-items: center; justify-content: space-between; gap: 8px;
    transition: all .2s;
}
.state-btn:hover { border-color: #e84c2b; color: #e84c2b; background: #fff5f3; transform: translateY(-2px); }
.state-btn .cnt { font-size: .7rem; background: #f1f5f9; color: #64748b; border-radius: 50px; padding: 1px 8px; }

/* ── Article cards ── */
.article-card {
    border: none; border-radius: 16px; overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    background: #fff;
    transition: transform .25s, box-shadow .25s;
}
.article-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }
.article-thumb { height: 180px; object-fit: cover; width: 100%; background: #e2e8f0; }
.article-thumb-placeholder {
    height: 180px; background: linear-gradient(135deg,#e2e8f0,#f1f5f9);
    display: flex; align-items: center; justify-content: center;
    color: #94a3b8; font-size: 2rem;
}
</style>

<!-- ══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge mb-3">&#127968; Direct Owner &middot; No Platform Fees</div>

                <h1 class="hero-title mb-3">
                    Book Direct<br>
                    <span>from the Owner.</span>
                </h1>

                <p class="hero-sub mb-5">
                    No platform fees. No middleman.<br>
                    Contact owners directly &mdash; save more, stay better.
                </p>

                <!-- Search -->
                <div class="hero-search">
                    <form action="/search" method="GET">
                        <div class="bg-white p-2 rounded-3 d-flex flex-column flex-md-row gap-2 shadow-lg">
                            <select name="state_id" id="hero-state" class="form-select flex-fill" style="min-width:0;">
                                <option value="">Select State</option>
                                <?php foreach ($statesWithCnt as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="city_id" id="hero-city" class="form-select flex-fill" style="min-width:0;">
                                <option value="">All Cities</option>
                            </select>
                            <button type="submit" class="btn px-4 fw-semibold text-white"
                                    style="background:#e84c2b;border-color:#e84c2b;white-space:nowrap;">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Stats -->
                <div class="hero-stats d-flex gap-4 mt-4">
                    <div>
                        <div class="hero-stat-num"><?= array_sum(array_column($statesWithCnt, 'total')) ?>+</div>
                        <div class="hero-stat-lbl">Active Listings</div>
                    </div>
                    <div style="border-left:1px solid #1e3a2f;margin:0 4px;"></div>
                    <div>
                        <div class="hero-stat-num">16</div>
                        <div class="hero-stat-lbl">States</div>
                    </div>
                    <div style="border-left:1px solid #1e3a2f;margin:0 4px;"></div>
                    <div>
                        <div class="hero-stat-num">0%</div>
                        <div class="hero-stat-lbl">Platform Fee</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     VALUE STRIP
═══════════════════════════════════════════ -->
<section class="value-strip py-4">
    <div class="container">
        <div class="row g-3 justify-content-center">
            <div class="col-12 col-md-4 fade-up">
                <div class="d-flex align-items-center gap-3 p-3">
                    <div class="value-icon"><i class="bi bi-tag-fill"></i></div>
                    <div>
                        <div class="fw-bold">Zero Platform Fees</div>
                        <div class="text-muted small">Pay the real price. No hidden charges.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 fade-up">
                <div class="d-flex align-items-center gap-3 p-3">
                    <div class="value-icon"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <div class="fw-bold">Contact Owner Directly</div>
                        <div class="text-muted small">Ask questions, negotiate — straight via WhatsApp.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 fade-up">
                <div class="d-flex align-items-center gap-3 p-3">
                    <div class="value-icon"><i class="bi bi-shield-check-fill"></i></div>
                    <div>
                        <div class="fw-bold">Verified Listings</div>
                        <div class="text-muted small">Every listing is reviewed and approved by our team.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     FEATURED LISTINGS
═══════════════════════════════════════════ -->
<?php if (!empty($listings)): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 fade-up">
            <div>
                <div class="section-label mb-1">Featured Homestays</div>
                <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Find Your Perfect Stay</h2>
            </div>
            <a href="/search" class="text-decoration-none small fw-semibold" style="color:#e84c2b;">
                View All <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php foreach ($listings as $l): ?>
            <div class="col-12 col-sm-6 col-lg-4 fade-up">
                <a href="/listing/<?= htmlspecialchars($l['slug']) ?>" class="text-decoration-none">
                    <div class="listing-card h-100">
                        <?php if (!empty($l['is_featured_active'])): ?>
                            <div class="featured-ribbon">Featured</div>
                        <?php endif; ?>
                        <div class="position-relative">
                            <?php if ($l['primary_image']): ?>
                                <img src="/uploads/listings/<?= $l['id'] ?>/<?= htmlspecialchars($l['primary_image']) ?>"
                                     class="listing-thumb" alt="<?= htmlspecialchars($l['title']) ?>">
                            <?php else: ?>
                                <div class="listing-thumb-placeholder"><i class="bi bi-house"></i></div>
                            <?php endif; ?>
                            <?php
                            $promo = !empty($l['active_promo']) ? json_decode($l['active_promo'], true) : null;
                            if ($promo && !empty($l['owner_is_verified'])):
                                $promoText = $promo['type'] === 'percent'
                                    ? (int)$promo['value'] . '% OFF'
                                    : 'RM' . (int)$promo['value'] . ' OFF';
                            ?>
                                <span class="promo-badge"><i class="bi bi-tag-fill me-1"></i><?= $promoText ?></span>
                            <?php endif; ?>
                            <?php if (!empty($l['owner_is_verified'])): ?>
                                <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified Host</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-3">
                            <div class="text-muted small mb-1">
                                <i class="bi bi-geo-alt-fill me-1" style="color:#e84c2b;"></i>
                                <?= htmlspecialchars($l['city_name']) ?>, <?= htmlspecialchars($l['state_name']) ?>
                            </div>
                            <div class="fw-semibold text-dark mb-2" style="font-size:.95rem;line-height:1.3;">
                                <?= htmlspecialchars($l['title']) ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="listing-price">RM <?= number_format($l['price_per_night'], 0) ?></span>
                                    <span class="text-muted small"> / night</span>
                                </div>
                                <?php
                                $_wa = preg_replace('/\D/', '', $l['owner_whatsapp'] ?? '');
                                $_waText = urlencode('Hi, I Saw your Homestay listing "' . $l['title'] . '" at ihomestay.my, Can I get more info for this unit?');
                                $_waUrl  = 'https://wa.me/' . $_wa . '?text=' . $_waText;
                                ?>
                                <?php if ($_wa): ?>
                                <?php if (!empty($l['owner_is_verified'])): ?>
                                <a href="<?= $_waUrl ?>" target="_blank" rel="noopener"
                                   class="btn btn-sm btn-success" style="font-size:.75rem;padding:3px 10px;"
                                   onclick="event.stopPropagation()">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </a>
                                <?php else: ?>
                                <button type="button"
                                        class="btn btn-sm btn-success wa-unverified-trigger"
                                        style="font-size:.75rem;padding:3px 10px;"
                                        data-wa-url="<?= htmlspecialchars($_waUrl) ?>"
                                        onclick="event.stopPropagation()">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </button>
                                <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ══════════════════════════════════════════
     BROWSE BY STATE
═══════════════════════════════════════════ -->
<section class="py-5" style="background:#f8fafc;">
    <div class="container">
        <div class="text-center mb-4 fade-up">
            <div class="section-label mb-1">Explore by State</div>
            <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Where Would You Like to Stay?</h2>
        </div>
        <div class="row g-2">
            <?php foreach ($statesWithCnt as $s): ?>
            <div class="col-6 col-sm-4 col-md-3 col-lg-2 fade-up">
                <a href="/<?= htmlspecialchars($s['slug']) ?>" class="state-btn w-100">
                    <span><?= htmlspecialchars($s['name']) ?></span>
                    <span class="cnt"><?= $s['total'] ?></span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ══════════════════════════════════════════
     OWNER CTA BANNER
═══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#e84c2b,#c73d22);padding:60px 0;">
    <div class="container text-center fade-up">
        <div class="text-white fw-semibold mb-2"
             style="font-size:.85rem;letter-spacing:.1em;text-transform:uppercase;opacity:.8;">
            For Homestay Owners
        </div>
        <h2 class="text-white fw-bold mb-3" style="font-size:clamp(1.5rem,3vw,2.4rem);">
            List Your Homestay for Free
        </h2>
        <p class="text-white mb-4" style="opacity:.85;max-width:520px;margin:0 auto 1.5rem;">
            Guests contact you directly. No commission. No listing fees.
            Register now and start receiving bookings today.
        </p>
        <a href="/register" class="btn btn-light fw-semibold px-5 py-2"
           style="border-radius:50px;color:#e84c2b;">
            Register Free <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
</section>

<!-- ══════════════════════════════════════════
     ARTICLES
═══════════════════════════════════════════ -->
<?php if (!empty($articles)): ?>
<section class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-4 fade-up">
            <div>
                <div class="section-label mb-1">Tips &amp; Guides</div>
                <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Latest Articles</h2>
            </div>
            <a href="/articles" class="text-decoration-none small fw-semibold" style="color:#e84c2b;">
                All Articles <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php foreach ($articles as $a): ?>
            <div class="col-12 col-md-4 fade-up">
                <a href="/articles/<?= htmlspecialchars($a['slug']) ?>" class="text-decoration-none">
                    <div class="article-card h-100">
                        <?php if ($a['cover_image']): ?>
                            <img src="/uploads/articles/<?= htmlspecialchars($a['cover_image']) ?>"
                                 class="article-thumb" alt="<?= htmlspecialchars($a['title']) ?>">
                        <?php else: ?>
                            <div class="article-thumb-placeholder"><i class="bi bi-newspaper"></i></div>
                        <?php endif; ?>
                        <div class="p-3">
                            <div class="text-muted small mb-1">
                                <?= $a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '' ?>
                            </div>
                            <div class="fw-semibold text-dark mb-1" style="font-size:.95rem;line-height:1.4;">
                                <?= htmlspecialchars($a['title']) ?>
                            </div>
                            <?php if ($a['excerpt']): ?>
                                <div class="text-muted small"
                                     style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                    <?= htmlspecialchars($a['excerpt']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
// City filter for hero search
const heroCities = <?= json_encode($citiesByState) ?>;
const heroState  = document.getElementById('hero-state');
const heroCity   = document.getElementById('hero-city');

heroState.addEventListener('change', function () {
    heroCity.innerHTML = '<option value="">All Cities</option>';
    (heroCities[this.value] || []).forEach(c => {
        const o = document.createElement('option');
        o.value = c.id; o.textContent = c.name;
        heroCity.appendChild(o);
    });
});

// Scroll-triggered fade-up
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); }
    });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "ihomestay.my",
  "url": "<?= htmlspecialchars(rtrim(env('APP_URL', 'https://ihomestay.my'), '/')) ?>",
  "description": "Malaysia's homestay directory — browse and book direct from owners. No platform fees, no middleman.",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "<?= htmlspecialchars(rtrim(env('APP_URL', 'https://ihomestay.my'), '/')) ?>/search?q={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "ihomestay.my",
  "url": "<?= htmlspecialchars(rtrim(env('APP_URL', 'https://ihomestay.my'), '/')) ?>",
  "logo": "<?= htmlspecialchars(rtrim(env('APP_URL', 'https://ihomestay.my'), '/')) ?>/assets/logo.png",
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "customer support",
    "url": "<?= htmlspecialchars(rtrim(env('APP_URL', 'https://ihomestay.my'), '/')) ?>/contact"
  }
}
</script>

<!-- Shared unverified-owner modal -->
<div class="modal fade" id="waUnverifiedModal" tabindex="-1" aria-labelledby="waUnverifiedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="waUnverifiedModalLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Owner Not Yet Verified
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2">
                <p class="text-muted mb-3">
                    This owner has not yet completed identity verification on ihomestay.my.
                    You can still contact them, but please take precautions:
                </p>
                <ul class="small text-muted mb-0 ps-3">
                    <li>Ask for photos or video of the property before committing</li>
                    <li>Do not transfer any payment before confirming with the owner</li>
                    <li>Meet in person or via video call if possible before booking</li>
                </ul>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Go Back</button>
                <a id="waUnverifiedProceed" href="#" target="_blank" rel="noopener"
                   class="btn" style="background:#25D366;color:#fff;">
                    <i class="bi bi-whatsapp me-1"></i>Proceed to WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.wa-unverified-trigger').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('waUnverifiedProceed').href = btn.dataset.waUrl;
        new bootstrap.Modal(document.getElementById('waUnverifiedModal')).show();
    });
});
</script>
