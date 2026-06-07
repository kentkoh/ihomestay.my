<?php
$isVerified = ($listing['owner_verification_status'] === 'verified');
$waNumber   = preg_replace('/\D/', '', $listing['whatsapp'] ?? '');
$waText     = urlencode('Hi, I saw your Listing "' . $listing['title'] . '" at ihomestay.my. Can I know more about this unit?');
$waUrl      = 'https://wa.me/' . $waNumber . '?text=' . $waText;
$hasMap     = !empty($listing['latitude']) && !empty($listing['longitude']);
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

/* Sticky bottom bar (verified only) */
.sticky-cta { position:fixed; bottom:0; left:0; right:0; z-index:200; background:#0f1923; border-top:1px solid #1e293b; padding:12px 16px; display:flex; align-items:center; gap:12px; }
.sticky-cta .price-sm { color:#fff; font-weight:700; font-size:1rem; white-space:nowrap; }
.sticky-cta .price-sm span { color:#94a3b8; font-weight:400; font-size:.8rem; }
.sticky-cta .verified-pill { display:inline-flex; align-items:center; gap:5px; background:rgba(16,185,129,.15); border:1px solid rgba(16,185,129,.3); color:#6ee7b7; border-radius:50px; padding:4px 12px; font-size:.78rem; font-weight:600; white-space:nowrap; }
.sticky-cta .btn-map { background:rgba(255,255,255,.1); color:#fff; border:1px solid rgba(255,255,255,.2); border-radius:8px; padding:8px 14px; font-size:.85rem; white-space:nowrap; }
.sticky-cta .btn-map:hover { background:rgba(255,255,255,.2); color:#fff; }
.sticky-cta .btn-wa { background:#25D366; color:#fff; border:none; border-radius:8px; padding:8px 18px; font-size:.9rem; font-weight:600; white-space:nowrap; }
.sticky-cta .btn-wa:hover { background:#1da851; color:#fff; }
.has-sticky-bar { padding-bottom:72px; }

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
                <div class="small text-muted mb-4">
                    Minimum <?= (int)$listing['min_nights'] ?> night<?= $listing['min_nights'] != 1 ? 's' : '' ?>
                    &nbsp;&middot;&nbsp;
                    Up to <?= (int)$listing['max_guests'] ?> guests
                </div>

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

<?php if ($isVerified && $waNumber): ?>
<!-- Sticky bottom bar — verified listings only -->
<div class="sticky-cta">
    <span class="verified-pill d-none d-sm-inline-flex">
        <i class="bi bi-patch-check-fill"></i> Verified Owner
    </span>
    <div class="price-sm ms-sm-auto">
        RM <?= number_format((float)$listing['price_per_night'], 0) ?>
        <span>/night</span>
    </div>
    <?php if ($hasMap): ?>
    <a href="#map-section" class="btn btn-map">
        <i class="bi bi-map me-1"></i>Map
    </a>
    <?php endif; ?>
    <a href="<?= $waUrl ?>" target="_blank" rel="noopener" class="btn btn-wa">
        <i class="bi bi-whatsapp me-1"></i>WhatsApp
    </a>
</div>
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
