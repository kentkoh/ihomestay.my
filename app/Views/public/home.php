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
    background: linear-gradient(135deg, #0f1923 0%, #1a2e22 60%, #0f1923 100%);
    min-height: 92vh;
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}
.hero::before {
    content: '';
    position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 60% 50%, rgba(232,76,43,.12) 0%, transparent 70%);
    pointer-events: none;
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
    color: #94a3b8;
    font-size: clamp(1rem, 1.8vw, 1.2rem);
    animation: fadeUp .6s ease .4s both;
}
.hero-search {
    animation: fadeUp .6s ease .55s both;
}
.hero-search .form-control, .hero-search .form-select {
    border: none;
    border-radius: 8px;
    padding: .75rem 1rem;
    font-size: .95rem;
}
.hero-stats {
    animation: fadeUp .6s ease .7s both;
}
.hero-stat-num { font-size: 1.6rem; font-weight: 800; color: #fff; line-height: 1; }
.hero-stat-lbl { font-size: .75rem; color: #64748b; }

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
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    transition: transform .25s, box-shadow .25s;
    background: #fff;
}
.listing-card:hover { transform: translateY(-4px); box-shadow: 0 8px 28px rgba(0,0,0,.12); }
.listing-thumb {
    height: 200px; object-fit: cover; width: 100%;
    background: #e2e8f0;
}
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

/* ── Footer ── */
.site-footer { background: #0f1923; color: #64748b; }
.site-footer a { color: #94a3b8; text-decoration: none; }
.site-footer a:hover { color: #fff; }
</style>

<!-- ══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ -->
<section class="hero">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-badge mb-3">&#127968; Direct Owner · No Platform Fees</div>

                <h1 class="hero-title mb-3">
                    Tempah Terus<br>
                    <span>dari Tuan Rumah.</span>
                </h1>

                <p class="hero-sub mb-5">
                    Pelancong tidak perlu bayar caj platform lagi.<br>
                    Hubungi tuan rumah terus — lebih jimat, lebih mesra.
                </p>

                <!-- Search -->
                <div class="hero-search">
                    <form action="/search" method="GET">
                        <div class="bg-white p-2 rounded-3 d-flex flex-column flex-md-row gap-2 shadow-lg">
                            <select name="state_id" id="hero-state" class="form-select flex-fill" style="min-width:0;">
                                <option value="">Pilih Negeri</option>
                                <?php foreach ($statesWithCnt as $s): ?>
                                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select name="city_id" id="hero-city" class="form-select flex-fill" style="min-width:0;">
                                <option value="">Semua Bandar</option>
                            </select>
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" style="background:#e84c2b;border-color:#e84c2b;white-space:nowrap;">
                                <i class="bi bi-search me-1"></i> Cari Homestay
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Stats -->
                <div class="hero-stats d-flex gap-4 mt-4">
                    <div>
                        <div class="hero-stat-num"><?= array_sum(array_column($statesWithCnt, 'total')) ?>+</div>
                        <div class="hero-stat-lbl">Listing Aktif</div>
                    </div>
                    <div style="border-left:1px solid #1e3a2f;margin:0 4px;"></div>
                    <div>
                        <div class="hero-stat-num">16</div>
                        <div class="hero-stat-lbl">Negeri</div>
                    </div>
                    <div style="border-left:1px solid #1e3a2f;margin:0 4px;"></div>
                    <div>
                        <div class="hero-stat-num">0</div>
                        <div class="hero-stat-lbl">Caj Platform</div>
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
                        <div class="fw-bold">Tiada Caj Platform</div>
                        <div class="text-muted small">Bayar harga sebenar. Tiada caj tersembunyi.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 fade-up">
                <div class="d-flex align-items-center gap-3 p-3">
                    <div class="value-icon"><i class="bi bi-whatsapp"></i></div>
                    <div>
                        <div class="fw-bold">Hubungi Tuan Rumah Terus</div>
                        <div class="text-muted small">Tanya soalan, nego harga — terus via WhatsApp.</div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 fade-up">
                <div class="d-flex align-items-center gap-3 p-3">
                    <div class="value-icon"><i class="bi bi-shield-check-fill"></i></div>
                    <div>
                        <div class="fw-bold">Pemilik Disahkan</div>
                        <div class="text-muted small">Listing disemak dan diluluskan admin.</div>
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
                <div class="section-label mb-1">Homestay Terpilih</div>
                <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Cari Penginapan Anda</h2>
            </div>
            <a href="/search" class="text-decoration-none small fw-semibold" style="color:#e84c2b;">
                Lihat Semua <i class="bi bi-arrow-right"></i>
            </a>
        </div>
        <div class="row g-3">
            <?php foreach ($listings as $l): ?>
            <div class="col-12 col-sm-6 col-lg-4 fade-up">
                <a href="/listing/<?= htmlspecialchars($l['slug']) ?>" class="text-decoration-none">
                    <div class="listing-card h-100">
                        <?php if ($l['primary_image']): ?>
                            <img src="/uploads/listings/<?= $l['id'] ?>/<?= htmlspecialchars($l['primary_image']) ?>"
                                 class="listing-thumb" alt="<?= htmlspecialchars($l['title']) ?>">
                        <?php else: ?>
                            <div class="listing-thumb-placeholder"><i class="bi bi-house"></i></div>
                        <?php endif; ?>
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
                                    <span class="text-muted small"> / malam</span>
                                </div>
                                <a href="https://wa.me/<?= htmlspecialchars(preg_replace('/\D/', '', $l['whatsapp'])) ?>"
                                   target="_blank" rel="noopener"
                                   class="btn btn-sm btn-success" style="font-size:.75rem;padding:3px 10px;"
                                   onclick="event.stopPropagation()">
                                    <i class="bi bi-whatsapp me-1"></i>WhatsApp
                                </a>
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
            <div class="section-label mb-1">Terokai Mengikut Negeri</div>
            <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Di Mana Anda Ingin Menginap?</h2>
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
     DIRECT OWNER BANNER
═══════════════════════════════════════════ -->
<section style="background:linear-gradient(135deg,#e84c2b,#c73d22);padding:60px 0;">
    <div class="container text-center fade-up">
        <div class="text-white fw-semibold mb-2" style="font-size:.85rem;letter-spacing:.1em;text-transform:uppercase;opacity:.8;">
            Untuk Tuan Rumah
        </div>
        <h2 class="text-white fw-bold mb-3" style="font-size:clamp(1.5rem,3vw,2.4rem);">
            Senaraikan Homestay Anda Percuma
        </h2>
        <p class="text-white mb-4" style="opacity:.85;max-width:520px;margin:0 auto 1.5rem;">
            Pelancong hubungi anda terus. Tiada komisen. Tiada caj listing.
            Daftar sekarang dan mula terima tempahan hari ini.
        </p>
        <a href="/register" class="btn btn-light fw-semibold px-5 py-2" style="border-radius:50px;color:#e84c2b;">
            Daftar Percuma <i class="bi bi-arrow-right ms-1"></i>
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
                <div class="section-label mb-1">Tips &amp; Panduan</div>
                <h2 class="fw-bold mb-0" style="font-size:1.6rem;">Artikel Terkini</h2>
            </div>
            <a href="/articles" class="text-decoration-none small fw-semibold" style="color:#e84c2b;">
                Semua Artikel <i class="bi bi-arrow-right"></i>
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

<!-- Scroll animation observer -->
<script>
// City filter for hero search
const heroCities  = <?= json_encode($citiesByState) ?>;
const heroState   = document.getElementById('hero-state');
const heroCity    = document.getElementById('hero-city');

heroState.addEventListener('change', function () {
    heroCity.innerHTML = '<option value="">Semua Bandar</option>';
    (heroCities[this.value] || []).forEach(c => {
        const o = document.createElement('option');
        o.value = c.id; o.textContent = c.name;
        heroCity.appendChild(o);
    });
});

// Scroll-triggered fade-up
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
</script>
