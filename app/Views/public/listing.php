<?php
$isVerified = (bool) ($listing['owner_is_verified'] ?? false);
$waNumber   = preg_replace('/\D/', '', $listing['owner_whatsapp'] ?? '');
$waText     = urlencode('Hi, I saw your Listing "' . $listing['title'] . '" at ihomestay.my. Can I know more about this unit?');
$waUrl      = 'https://wa.me/' . $waNumber . '?text=' . $waText;
$hasMap     = !empty($listing['latitude']) && !empty($listing['longitude']);
$promo      = (!empty($listing['active_promo']) && $isVerified) ? json_decode($listing['active_promo'], true) : null;
?>
<style>
/* Gallery */
.gallery-main { width:100%; height:420px; object-fit:cover; border-radius:12px 12px 0 0; background:#f1f5f9; }
.gallery-main-ph { width:100%; height:420px; border-radius:12px 12px 0 0; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8; font-size:3rem; }
.thumb-strip { display:flex; gap:8px; overflow-x:auto; padding:8px 0; scrollbar-width:thin; }
.thumb-item { flex:0 0 90px; height:68px; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid transparent; transition:border-color .2s; }
.thumb-item.active, .thumb-item:hover { border-color:#e84c2b; }

/* Contact card */
.contact-card { position:sticky; top:80px; border:none; border-radius:12px; box-shadow:0 4px 24px rgba(0,0,0,.1); }
.price-big { font-size:1.8rem; font-weight:800; color:#e84c2b; }
.wa-btn-main { background:#25D366; color:#fff; border:none; font-size:1rem; font-weight:600; padding:.75rem; border-radius:8px; }
.wa-btn-main:hover { background:#1da851; color:#fff; }
.wa-btn-unverified { background:#64748b; color:#fff; border:none; font-size:1rem; font-weight:600; padding:.75rem; border-radius:8px; }
.wa-btn-unverified:hover { background:#475569; color:#fff; }

/* Stats strip */
.stat-chip { display:inline-flex; align-items:center; gap:6px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:8px 14px; font-size:.9rem; }

/* Facilities */
.facility-tag { display:inline-block; background:#f1f5f9; border-radius:6px; padding:4px 12px; font-size:.82rem; margin:3px; }

/* Map */
#listingMap { height:280px; border-radius:10px; z-index:1; }

/* Padding so content doesn't hide behind sticky bar (verified only) */
.has-sticky-bar { padding-bottom:80px; }

/* Featured ribbon */
.featured-ribbon { position:absolute; top:18px; right:-30px; background:#e84c2b; color:#fff; font-size:.65rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; padding:5px 44px; transform:rotate(45deg); z-index:3; box-shadow:0 2px 8px rgba(0,0,0,.25); }

/* Similar listings */
.similar-card { border:none; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.07); transition:transform .2s,box-shadow .2s; }
.similar-card:hover { transform:translateY(-3px); box-shadow:0 6px 20px rgba(0,0,0,.12); }
.similar-thumb { height:140px; object-fit:cover; width:100%; background:#f1f5f9; }
.similar-thumb-ph { height:140px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8; }

/* Verified badge */
.verified-badge { display:inline-flex; align-items:center; gap:5px; background:#d1fae5; color:#065f46; border-radius:50px; padding:3px 10px; font-size:.78rem; font-weight:600; }
.unverified-badge { display:inline-flex; align-items:center; gap:5px; background:#fef3c7; color:#92400e; border-radius:50px; padding:3px 10px; font-size:.78rem; font-weight:600; }
</style>

<div style="background:#f8fafc; min-height:80vh; padding-bottom:3rem;" class="<?= $isVerified ? 'has-sticky-bar' : '' ?>">
<div class="container pt-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="/" style="color:#e84c2b;">Home</a></li>
            <li class="breadcrumb-item">
                <a href="/<?= htmlspecialchars($listing['state_slug']) ?>" style="color:#e84c2b;">
                    <?= htmlspecialchars($listing['state_name']) ?>
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="/<?= htmlspecialchars($listing['state_slug']) ?>/<?= htmlspecialchars($listing['city_slug']) ?>" style="color:#e84c2b;">
                    <?= htmlspecialchars($listing['city_name']) ?>
                </a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width:220px;">
                <?= htmlspecialchars($listing['title']) ?>
            </li>
        </ol>
    </nav>

    <div class="row g-4">

        <!-- ── Left column ── -->
        <div class="col-12 col-lg-8">

            <!-- Gallery -->
            <div class="card border-0 shadow-sm overflow-hidden mb-4">
                <?php if (!empty($listing['is_featured_active'])): ?>
                    <div class="featured-ribbon">Featured</div>
                <?php endif; ?>
                <?php if (!empty($images)): ?>
                    <img id="mainImg"
                         src="/uploads/listings/<?= (int)$listing['id'] ?>/<?= htmlspecialchars($images[0]['filename']) ?>"
                         class="gallery-main"
                         alt="<?= htmlspecialchars($listing['title']) ?>"
                         onerror="this.style.display='none';document.getElementById('mainImgPh').style.display='flex';">
                    <div id="mainImgPh" class="gallery-main-ph" style="display:none;">
                        <i class="bi bi-image"></i>
                    </div>
                    <?php if (count($images) > 1): ?>
                    <div class="px-3 pb-2 pt-1">
                        <div class="thumb-strip">
                            <?php foreach ($images as $i => $img): ?>
                            <img src="/uploads/listings/<?= (int)$listing['id'] ?>/<?= htmlspecialchars($img['filename']) ?>"
                                 class="thumb-item <?= $i === 0 ? 'active' : '' ?>"
                                 alt="Photo <?= $i + 1 ?>"
                                 loading="lazy"
                                 onerror="this.style.display='none'"
                                 onclick="setMain(this, '/uploads/listings/<?= (int)$listing['id'] ?>/<?= htmlspecialchars($img['filename']) ?>')">
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="gallery-main-ph"><i class="bi bi-image"></i></div>
                <?php endif; ?>
            </div>

            <!-- Title & location -->
            <div class="mb-3">
                <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap">
                    <h1 class="h3 fw-bold mb-1"><?= htmlspecialchars($listing['title']) ?></h1>
                    <?php if ($isVerified): ?>
                        <span class="verified-badge flex-shrink-0">
                            <i class="bi bi-patch-check-fill"></i> Verified Owner
                        </span>
                    <?php else: ?>
                        <span class="unverified-badge flex-shrink-0">
                            <i class="bi bi-exclamation-triangle-fill"></i> Not Yet Verified
                        </span>
                    <?php endif; ?>
                </div>
                <div class="text-muted">
                    <i class="bi bi-geo-alt-fill me-1" style="color:#e84c2b;"></i>
                    <?= htmlspecialchars($listing['city_name']) ?>, <?= htmlspecialchars($listing['state_name']) ?>
                    <?php if ($listing['address']): ?>
                        &mdash; <?= htmlspecialchars($listing['address']) ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Key stats -->
            <div class="d-flex flex-wrap gap-2 mb-4">
                <span class="stat-chip"><i class="bi bi-door-open" style="color:#e84c2b;"></i> <?= (int)$listing['bedrooms'] ?> Bedroom<?= $listing['bedrooms'] != 1 ? 's' : '' ?></span>
                <span class="stat-chip"><i class="bi bi-droplet" style="color:#e84c2b;"></i> <?= (int)$listing['bathrooms'] ?> Bathroom<?= $listing['bathrooms'] != 1 ? 's' : '' ?></span>
                <span class="stat-chip"><i class="bi bi-people" style="color:#e84c2b;"></i> Up to <?= (int)$listing['max_guests'] ?> Guests</span>
                <span class="stat-chip"><i class="bi bi-calendar-check" style="color:#e84c2b;"></i> Min <?= (int)$listing['min_nights'] ?> Night<?= $listing['min_nights'] != 1 ? 's' : '' ?></span>
            </div>

            <!-- Description -->
            <?php if ($listing['description']): ?>
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">About This Homestay</h2>
                <div style="line-height:1.8; color:#374151; white-space:pre-line;">
                    <?= nl2br(htmlspecialchars($listing['description'])) ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Facilities -->
            <?php if (!empty($facilities)): ?>
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Facilities &amp; Amenities</h2>
                <?php foreach ($facilities as $category => $items): ?>
                    <div class="mb-3">
                        <div class="small fw-semibold text-muted text-uppercase mb-2"
                             style="letter-spacing:.05em;"><?= htmlspecialchars($category) ?></div>
                        <div>
                            <?php foreach ($items as $item): ?>
                                <span class="facility-tag"><?= htmlspecialchars($item) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Map -->
            <?php if ($hasMap): ?>
            <div id="map-section" class="card border-0 shadow-sm p-4 mb-4">
                <h2 class="h5 fw-bold mb-3">Location</h2>
                <div id="listingMap" class="mb-3"></div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="https://www.google.com/maps?q=<?= (float)$listing['latitude'] ?>,<?= (float)$listing['longitude'] ?>"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-map"></i> Google Maps
                    </a>
                    <a href="https://waze.com/ul?ll=<?= (float)$listing['latitude'] ?>,<?= (float)$listing['longitude'] ?>&navigate=yes"
                       target="_blank" rel="noopener"
                       class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-sign-turn-right"></i> Waze
                    </a>
                </div>
            </div>
            <?php endif; ?>

        </div>

        <!-- ── Right column (contact card) ── -->
        <div class="col-12 col-lg-4">
            <div class="card contact-card p-4">

                <!-- Price -->
                <div class="mb-1">
                    <span class="price-big">RM <?= number_format((float)$listing['price_per_night'], 0) ?></span>
                    <span class="text-muted"> / night</span>
                </div>
                <div class="small text-muted mb-3">
                    Minimum <?= (int)$listing['min_nights'] ?> night<?= $listing['min_nights'] != 1 ? 's' : '' ?>
                    &nbsp;&middot;&nbsp;
                    Up to <?= (int)$listing['max_guests'] ?> guests
                </div>

                <?php
                $p1 = (float) $listing['price_per_night'];
                $p2 = !empty($listing['price_2nights']) ? (float) $listing['price_2nights'] : null;
                $p3 = !empty($listing['price_3nights']) ? (float) $listing['price_3nights'] : null;
                if ($isVerified && ($p2 || $p3)):
                ?>
                <div class="mb-4 rounded-3 overflow-hidden" style="border:1px solid #e2e8f0;">
                    <div class="px-3 py-2" style="background:#f8fafc;font-size:.75rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;">
                        Long-Stay Rates
                    </div>
                    <table class="table table-sm mb-0" style="font-size:.85rem;">
                        <tbody>
                            <tr>
                                <td class="ps-3 text-muted">1 night</td>
                                <td class="fw-semibold">RM <?= number_format($p1, 0) ?>/night</td>
                                <td></td>
                            </tr>
                            <?php if ($p2): ?>
                            <tr>
                                <td class="ps-3 text-muted">2 nights</td>
                                <td class="fw-semibold">RM <?= number_format($p2, 0) ?>/night</td>
                                <td><span class="badge" style="background:#d1fae5;color:#065f46;font-size:.7rem;">Save <?= round((1 - $p2/$p1)*100) ?>%</span></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($p3): ?>
                            <tr>
                                <td class="ps-3 text-muted">3+ nights</td>
                                <td class="fw-semibold">RM <?= number_format($p3, 0) ?>/night</td>
                                <td><span class="badge" style="background:#d1fae5;color:#065f46;font-size:.7rem;">Save <?= round((1 - $p3/$p1)*100) ?>%</span></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>

                <!-- Active Promo -->
                <?php if ($promo): ?>
                <?php
                    $basePrice = (float) $listing['price_per_night'];
                    $promoPrice = $promo['type'] === 'percent'
                        ? $basePrice * (1 - $promo['value'] / 100)
                        : $basePrice - $promo['value'];
                    $promoPrice = max(0, $promoPrice);
                ?>
                <div class="mb-4 p-3 rounded-3" style="background:#fffbeb;border:1px solid #fde68a;">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-tag-fill" style="color:#f59e0b;"></i>
                        <span class="fw-bold" style="color:#92400e;font-size:.9rem;"><?= htmlspecialchars($promo['label']) ?></span>
                    </div>
                    <div class="d-flex align-items-baseline gap-2">
                        <span style="text-decoration:line-through;color:#94a3b8;font-size:.9rem;">RM <?= number_format($basePrice, 0) ?></span>
                        <span style="color:#e84c2b;font-size:1.3rem;font-weight:800;">RM <?= number_format($promoPrice, 0) ?></span>
                        <span class="text-muted" style="font-size:.85rem;">/night</span>
                        <span class="badge ms-1" style="background:#fef2f2;color:#e84c2b;">
                            <?= $promo['type'] === 'percent' ? (int)$promo['value'] . '% OFF' : 'RM' . (int)$promo['value'] . ' OFF' ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- WhatsApp button -->
                <?php if ($waNumber): ?>
                    <?php if ($isVerified): ?>
                        <a href="<?= $waUrl ?>" target="_blank" rel="noopener"
                           class="btn wa-btn-main w-100 mb-3">
                            <i class="bi bi-whatsapp me-2"></i>Contact Owner via WhatsApp
                        </a>
                    <?php else: ?>
                        <button type="button" class="btn wa-btn-unverified w-100 mb-3"
                                data-bs-toggle="modal" data-bs-target="#unverifiedModal">
                            <i class="bi bi-whatsapp me-2"></i>Contact via WhatsApp
                        </button>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Check Availability -->
                <?php if ($isVerified): ?>
                <button type="button" class="btn btn-outline-secondary w-100 mb-3"
                        data-bs-toggle="modal" data-bs-target="#availModal"
                        style="border-radius:8px;">
                    <i class="bi bi-calendar3 me-2"></i>Check Availability
                </button>
                <?php endif; ?>

                <!-- Verification status note -->
                <?php if ($isVerified): ?>
                    <div class="d-flex align-items-center gap-2 p-3 rounded mb-3"
                         style="background:#d1fae5;color:#065f46;font-size:.82rem;">
                        <i class="bi bi-patch-check-fill fs-5"></i>
                        <div>
                            <div class="fw-semibold">Verified Owner</div>
                            <div>Identity confirmed by ihomestay.my</div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex align-items-center gap-2 p-3 rounded mb-3"
                         style="background:#fef3c7;color:#92400e;font-size:.82rem;">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div>
                            <div class="fw-semibold">Owner not yet verified</div>
                            <div>Proceed with caution. Verify the property before making any payment.</div>
                        </div>
                    </div>
                <?php endif; ?>

                <hr class="my-3">

                <!-- Owner info -->
                <div class="small text-muted mb-1 fw-semibold">Listed by</div>
                <div class="fw-semibold">
                    <?= htmlspecialchars($listing['owner_company'] ?: $listing['owner_name']) ?>
                </div>
                <?php if ($listing['owner_bio']): ?>
                    <div class="text-muted small mt-1"
                         style="display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;">
                        <?= htmlspecialchars($listing['owner_bio']) ?>
                    </div>
                <?php endif; ?>

                <?php if ($isVerified && (!empty($listing['owner_facebook']) || !empty($listing['owner_instagram']) || !empty($listing['owner_website']))): ?>
                <div class="mt-3 d-flex flex-column gap-2">
                    <?php if (!empty($listing['owner_facebook'])): ?>
                        <a href="<?= htmlspecialchars($listing['owner_facebook']) ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm d-flex align-items-center gap-2"
                           style="background:#1877f2;color:#fff;border-radius:8px;font-size:.82rem;">
                            <i class="bi bi-facebook"></i> Facebook Page
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($listing['owner_instagram'])): ?>
                        <a href="<?= htmlspecialchars($listing['owner_instagram']) ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm d-flex align-items-center gap-2"
                           style="background:#e1306c;color:#fff;border-radius:8px;font-size:.82rem;">
                            <i class="bi bi-instagram"></i> Instagram
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($listing['owner_website'])): ?>
                        <a href="<?= htmlspecialchars($listing['owner_website']) ?>"
                           target="_blank" rel="noopener noreferrer"
                           class="btn btn-sm d-flex align-items-center gap-2"
                           style="background:#0f172a;color:#fff;border-radius:8px;font-size:.82rem;">
                            <i class="bi bi-globe2"></i> Website
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <hr class="my-3">

                <!-- Share / back links -->
                <a href="/<?= htmlspecialchars($listing['state_slug']) ?>" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-arrow-left me-1"></i>More in <?= htmlspecialchars($listing['state_name']) ?>
                </a>
            </div>
        </div>

    </div>
</div>
</div>

<?php if (!empty($similar)): ?>
<!-- Similar listings -->
<div style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:3rem 0 4rem;">
    <div class="container">
        <div class="mb-4">
            <div class="small fw-bold text-uppercase" style="color:#e84c2b;letter-spacing:.1em;">More in <?= htmlspecialchars($listing['city_name']) ?></div>
            <h2 class="h5 fw-bold mb-0">Similar Homestays</h2>
        </div>
        <div class="row g-3">
            <?php foreach ($similar as $s): ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="/listing/<?= htmlspecialchars($s['slug']) ?>" class="text-decoration-none">
                    <div class="similar-card position-relative">
                        <?php if (!empty($s['is_featured_active'])): ?>
                            <div class="featured-ribbon" style="top:12px;right:-24px;font-size:.55rem;padding:3px 32px;">Featured</div>
                        <?php endif; ?>
                        <?php if ($s['primary_image']): ?>
                            <img src="/uploads/listings/<?= (int)$s['id'] ?>/<?= htmlspecialchars($s['primary_image']) ?>"
                                 class="similar-thumb" alt="" loading="lazy"
                                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'similar-thumb-ph',innerHTML:'<i class=\'bi bi-image\'></i>'}))">
                        <?php else: ?>
                            <div class="similar-thumb-ph"><i class="bi bi-image"></i></div>
                        <?php endif; ?>
                        <div class="p-2">
                            <?php if (!empty($s['owner_is_verified'])): ?>
                                <div class="small mb-1" style="color:#059669;font-size:.68rem;font-weight:600;">
                                    <i class="bi bi-patch-check-fill"></i> Verified
                                </div>
                            <?php endif; ?>
                            <div class="small fw-semibold text-dark" style="line-height:1.3;font-size:.82rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?= htmlspecialchars($s['title']) ?>
                            </div>
                            <div class="fw-bold mt-1" style="color:#e84c2b;font-size:.85rem;">
                                RM <?= number_format((float)$s['price_per_night'], 0) ?><span class="fw-normal text-muted" style="font-size:.72rem;">/night</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php
if ($isVerified) {
    ob_start();
    ?>
    <style>
    .ihms-sticky { position:fixed !important; bottom:0 !important; left:0 !important; right:0 !important; z-index:9999 !important; background:#0f1923; border-top:2px solid #1e293b; padding:10px 16px; padding-bottom:max(10px,env(safe-area-inset-bottom)); display:flex !important; align-items:center; gap:8px; flex-wrap:nowrap; }
    .ihms-sticky .s-pill { display:inline-flex; align-items:center; gap:4px; background:rgba(16,185,129,.18); border:1px solid rgba(16,185,129,.35); color:#6ee7b7; border-radius:50px; padding:4px 10px; font-size:.75rem; font-weight:700; white-space:nowrap; flex-shrink:0; }
    .ihms-sticky .s-price { color:#fff; font-weight:700; font-size:.95rem; white-space:nowrap; flex-shrink:0; }
    .ihms-sticky .s-price small { color:#94a3b8; font-weight:400; font-size:.75rem; }
    .ihms-sticky .s-spacer { flex:1 1 auto; }
    .ihms-sticky .s-map { background:rgba(255,255,255,.1); color:#fff !important; border:1px solid rgba(255,255,255,.2); border-radius:8px; padding:8px 12px; font-size:.82rem; text-decoration:none; white-space:nowrap; flex-shrink:0; }
    .ihms-sticky .s-map:hover { background:rgba(255,255,255,.2); }
    .ihms-sticky .s-wa { background:#25D366 !important; color:#fff !important; border:none; border-radius:8px; padding:8px 16px; font-size:.88rem; font-weight:700; text-decoration:none; white-space:nowrap; flex-shrink:0; }
    .ihms-sticky .s-wa:hover { background:#1da851 !important; }
    </style>
    <div class="ihms-sticky">
        <span class="s-pill">
            <i class="bi bi-patch-check-fill"></i>
            Verified<span class="d-none d-sm-inline"> Owner</span>
        </span>
        <div class="s-price">
            RM <?= number_format((float)$listing['price_per_night'], 0) ?>
            <small>/night</small>
        </div>
        <div class="s-spacer"></div>
        <button type="button" class="s-map" data-bs-toggle="modal" data-bs-target="#availModal"
                style="background:none;border:1px solid rgba(255,255,255,.2);cursor:pointer;">
            <i class="bi bi-calendar3"></i><span class="d-none d-sm-inline ms-1">Availability</span>
        </button>
        <?php if ($hasMap): ?>
        <a href="#map-section" class="s-map">
            <i class="bi bi-map"></i><span class="d-none d-sm-inline ms-1">Map</span>
        </a>
        <?php endif; ?>
        <?php if ($waNumber): ?>
        <a href="<?= $waUrl ?>" target="_blank" rel="noopener" class="s-wa">
            <i class="bi bi-whatsapp me-1"></i>WhatsApp
        </a>
        <?php endif; ?>
    </div>
    <?php
    $stickyBar = ob_get_clean();
}
?>

<!-- Availability Modal -->
<?php if ($isVerified): ?>
<div class="modal fade" id="availModal" tabindex="-1" aria-labelledby="availModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="availModalLabel">
                    <i class="bi bi-calendar3 me-2" style="color:#e84c2b;"></i>Check Availability
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="d-flex gap-3 mb-3 flex-wrap" style="font-size:.8rem;">
                    <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#d1fae5;border:1px solid #6ee7b7;vertical-align:middle;"></span> Available</span>
                    <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#fee2e2;border:1px solid #fecaca;vertical-align:middle;"></span> Unavailable</span>
                    <span><span style="display:inline-block;width:12px;height:12px;border-radius:3px;background:#f1f5f9;vertical-align:middle;"></span> Past</span>
                </div>
                <div id="pubCal">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="pubCalPrev"><i class="bi bi-chevron-left"></i></button>
                        <span class="fw-semibold" id="pubCalTitle"></span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="pubCalNext"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;" id="pubCalGrid"></div>
                </div>
                <p class="text-muted small mt-3 mb-0">
                    Contact the owner via WhatsApp to confirm your dates.
                </p>
            </div>
        </div>
    </div>
</div>
<style>
#pubCalGrid .pc-label { text-align:center; font-size:.72rem; color:#94a3b8; font-weight:600; padding:4px 0; }
#pubCalGrid .pc-cell  { text-align:center; font-size:.82rem; border-radius:6px; padding:7px 2px; }
#pubCalGrid .pc-empty { }
#pubCalGrid .pc-past  { background:#f8fafc; color:#cbd5e1; }
#pubCalGrid .pc-blocked { background:#fee2e2; color:#dc2626; }
#pubCalGrid .pc-available { background:#d1fae5; color:#065f46; }
#pubCalGrid .pc-today { outline:2px solid #e84c2b; }
</style>
<script>
(function () {
    const blocked = new Set(<?= json_encode($blockedDates) ?>);
    const today   = new Date(); today.setHours(0,0,0,0);
    let cur = new Date(today.getFullYear(), today.getMonth(), 1);

    function pad(n) { return String(n).padStart(2,'0'); }
    function ds(y,m,d) { return y+'-'+pad(m+1)+'-'+pad(d); }

    function render() {
        const y = cur.getFullYear(), m = cur.getMonth();
        document.getElementById('pubCalTitle').textContent =
            new Date(y,m,1).toLocaleDateString('en-MY',{month:'long',year:'numeric'});

        const grid = document.getElementById('pubCalGrid');
        grid.innerHTML = '';
        ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(l => {
            const el = document.createElement('div');
            el.className = 'pc-label'; el.textContent = l;
            grid.appendChild(el);
        });

        const startDay = new Date(y,m,1).getDay();
        const days = new Date(y,m+1,0).getDate();

        for (let i = 0; i < startDay; i++) {
            const el = document.createElement('div'); el.className = 'pc-empty'; grid.appendChild(el);
        }
        for (let d = 1; d <= days; d++) {
            const dStr = ds(y,m,d);
            const cellDate = new Date(y,m,d);
            const isToday = cellDate.getTime() === today.getTime();
            const isPast  = cellDate < today;
            const el = document.createElement('div');
            el.textContent = d;
            el.className = 'pc-cell ' + (isPast ? 'pc-past' : blocked.has(dStr) ? 'pc-blocked' : 'pc-available');
            if (isToday) el.classList.add('pc-today');
            grid.appendChild(el);
        }
    }

    document.getElementById('pubCalPrev').addEventListener('click', () => {
        cur.setMonth(cur.getMonth()-1); render();
    });
    document.getElementById('pubCalNext').addEventListener('click', () => {
        cur.setMonth(cur.getMonth()+1); render();
    });

    document.getElementById('availModal').addEventListener('show.bs.modal', render);
})();
</script>
<?php endif; ?>

<!-- Unverified owner modal -->
<div class="modal fade" id="unverifiedModal" tabindex="-1" aria-labelledby="unverifiedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="unverifiedModalLabel">
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
                <a href="<?= $waUrl ?>" target="_blank" rel="noopener"
                   class="btn" style="background:#25D366;color:#fff;">
                    <i class="bi bi-whatsapp me-1"></i>Proceed to WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

<?php if ($hasMap): ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
    const map = L.map('listingMap').setView([<?= (float)$listing['latitude'] ?>, <?= (float)$listing['longitude'] ?>], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
    L.marker([<?= (float)$listing['latitude'] ?>, <?= (float)$listing['longitude'] ?>])
        .addTo(map)
        .bindPopup('<?= addslashes(htmlspecialchars($listing['title'])) ?>')
        .openPopup();
})();
</script>
<?php endif; ?>

<script>
function setMain(thumbEl, src) {
    const mainImg = document.getElementById('mainImg');
    if (mainImg) mainImg.src = src;
    document.querySelectorAll('.thumb-item').forEach(t => t.classList.remove('active'));
    thumbEl.classList.add('active');
}
</script>
