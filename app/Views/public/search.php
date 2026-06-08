<?php
function searchPageUrl(int $page, array $filters): string {
    $q = array_filter($filters, fn($v) => $v !== null && $v !== '' && $v !== 0);
    $q['page'] = $page;
    return '/search?' . http_build_query($q);
}
?>
<style>
.search-filter-bar { background:#fff; border-bottom:1px solid #e2e8f0; position:sticky; top:0; z-index:90; box-shadow:0 2px 8px rgba(0,0,0,.06); }
.listing-card { transition:transform .2s ease, box-shadow .2s ease; border:none !important; }
.listing-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,.1) !important; }
.listing-thumb { height:210px; object-fit:cover; width:100%; }
.listing-thumb-ph { height:210px; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8; }
.badge-featured { position:absolute; top:10px; left:10px; background:#e84c2b; color:#fff; font-size:.7rem; padding:3px 9px; border-radius:4px; font-weight:700; letter-spacing:.03em; }
.featured-ribbon { position:absolute; top:18px; right:-30px; background:#e84c2b; color:#fff; font-size:.62rem; font-weight:800; letter-spacing:.12em; text-transform:uppercase; padding:5px 44px; transform:rotate(45deg); z-index:3; box-shadow:0 2px 8px rgba(0,0,0,.25); pointer-events:none; }
.verified-badge { position:absolute; bottom:8px; left:8px; background:#16a34a; color:#fff; border-radius:99px; padding:3px 9px; font-size:.62rem; font-weight:700; display:flex; align-items:center; gap:3px; z-index:2; box-shadow:0 1px 6px rgba(0,0,0,.25); }
.promo-badge { position:absolute; top:8px; left:8px; background:#f59e0b; color:#fff; border-radius:6px; padding:3px 8px; font-size:.62rem; font-weight:800; z-index:2; box-shadow:0 1px 6px rgba(0,0,0,.25); letter-spacing:.04em; }
.price-tag { color:#e84c2b; font-weight:700; font-size:1.05rem; }
.wa-btn { background:#25D366; color:#fff; border:none; position:relative; z-index:2; }
.wa-btn:hover { background:#1da851; color:#fff; }
.pagination .page-link { color:#e84c2b; border-color:#e2e8f0; }
.pagination .page-item.active .page-link { background:#e84c2b; border-color:#e84c2b; color:#fff; }
</style>

<!-- Filter Bar -->
<div class="search-filter-bar py-2 shadow-sm">
    <div class="container">
        <form method="GET" action="/search" class="row g-2 align-items-center">
            <div class="col-12 col-sm-6 col-md-3">
                <select name="state_id" id="filterState" class="form-select form-select-sm">
                    <option value="">All States</option>
                    <?php foreach ($states as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= ($filters['state_id'] ?? 0) == $s['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <select name="city_id" id="filterCity" class="form-select form-select-sm">
                    <option value="">All Cities</option>
                </select>
            </div>
            <div class="col-12 col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="q" class="form-control border-start-0"
                           placeholder="Search by name or keyword…"
                           value="<?= htmlspecialchars($filters['q'] ?? '') ?>">
                </div>
            </div>
            <div class="col-6 col-md-1">
                <button type="submit" class="btn btn-primary btn-sm w-100">Search</button>
            </div>
            <?php if (array_filter($filters)): ?>
            <div class="col-6 col-md-1">
                <a href="/search" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Main content -->
<div style="background:#f8fafc; min-height:70vh;">
    <div class="container py-4">

        <!-- Heading row -->
        <div class="d-flex align-items-baseline gap-2 mb-4">
            <?php if ($contextCity): ?>
                <h1 class="h4 mb-0 fw-bold"><?= htmlspecialchars($contextCity['name']) ?> Homestays</h1>
                <span class="text-muted small">&mdash; <?= htmlspecialchars($contextState['name']) ?></span>
            <?php elseif ($contextState): ?>
                <h1 class="h4 mb-0 fw-bold"><?= htmlspecialchars($contextState['name']) ?> Homestays</h1>
            <?php else: ?>
                <h1 class="h4 mb-0 fw-bold">Search Results</h1>
            <?php endif; ?>
            <span class="badge bg-light text-secondary border ms-1"><?= $total ?> found</span>
        </div>

        <!-- Empty state -->
        <?php if (empty($listings)): ?>
            <div class="text-center py-5">
                <i class="bi bi-house-slash" style="font-size:3.5rem;color:#cbd5e1;"></i>
                <p class="mt-3 mb-1 fw-semibold text-secondary">No homestays found</p>
                <p class="text-muted small">Try adjusting your filters or searching a different area.</p>
                <a href="/search" class="btn btn-outline-secondary btn-sm mt-2">Clear Filters</a>
            </div>

        <?php else: ?>

        <!-- Listing grid -->
        <div class="row g-4">
            <?php foreach ($listings as $listing): ?>
            <div class="col-12 col-sm-6 col-xl-4">
                <div class="card listing-card h-100 shadow-sm overflow-hidden">
                    <div class="position-relative">
                        <?php if (!empty($listing['is_featured_active'])): ?>
                            <div class="featured-ribbon">Featured</div>
                        <?php endif; ?>
                        <?php if ($listing['primary_image']): ?>
                            <img src="/uploads/listings/<?= (int)$listing['id'] ?>/<?= htmlspecialchars($listing['primary_image']) ?>"
                                 class="listing-thumb"
                                 alt="<?= htmlspecialchars($listing['title']) ?>"
                                 loading="lazy"
                                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'listing-thumb-ph',innerHTML:'<i class=\'bi bi-image\' style=\'font-size:2rem\'></i>'}))">
                        <?php else: ?>
                            <div class="listing-thumb-ph">
                                <i class="bi bi-image" style="font-size:2rem;"></i>
                            </div>
                        <?php endif; ?>
                        <?php if ($listing['is_featured']): ?>
                            <span class="badge-featured">Featured</span>
                        <?php endif; ?>
                        <?php
                        $promo = !empty($listing['active_promo']) ? json_decode($listing['active_promo'], true) : null;
                        if ($promo && !empty($listing['owner_is_verified'])):
                            $promoText = $promo['type'] === 'percent'
                                ? (int)$promo['value'] . '% OFF'
                                : 'RM' . (int)$promo['value'] . ' OFF';
                        ?>
                            <span class="promo-badge"><i class="bi bi-tag-fill me-1"></i><?= $promoText ?></span>
                        <?php endif; ?>
                        <?php if (!empty($listing['owner_is_verified'])): ?>
                            <span class="verified-badge"><i class="bi bi-patch-check-fill"></i> Verified Host</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body d-flex flex-column pb-3">
                        <div class="small text-muted mb-1">
                            <i class="bi bi-geo-alt-fill" style="color:#e84c2b;"></i>
                            <?= htmlspecialchars($listing['city_name']) ?>, <?= htmlspecialchars($listing['state_name']) ?>
                        </div>
                        <h2 class="h6 fw-semibold mb-2 flex-grow-1" style="line-height:1.4;">
                            <a href="/listing/<?= htmlspecialchars($listing['slug']) ?>"
                               class="text-dark text-decoration-none stretched-link">
                                <?= htmlspecialchars($listing['title']) ?>
                            </a>
                        </h2>
                        <div class="d-flex gap-3 text-muted small mb-3">
                            <span><i class="bi bi-door-open"></i> <?= (int) $listing['bedrooms'] ?> bed</span>
                            <span><i class="bi bi-droplet"></i> <?= (int) $listing['bathrooms'] ?> bath</span>
                            <span><i class="bi bi-people"></i> <?= (int) $listing['max_guests'] ?> guests</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="price-tag">
                                RM <?= number_format((float) $listing['price_per_night'], 0) ?>
                                <span class="fw-normal text-muted" style="font-size:.85rem;">/night</span>
                            </div>
                            <?php if ($listing['owner_whatsapp']): ?>
                            <?php $wa = preg_replace('/\D/', '', $listing['owner_whatsapp']); ?>
                            <a href="https://wa.me/<?= $wa ?>?text=<?= urlencode('Hi, I\'m interested in your homestay: ' . $listing['title']) ?>"
                               class="btn btn-sm wa-btn"
                               target="_blank" rel="noopener noreferrer"
                               style="position:relative;z-index:2;">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-5 d-flex justify-content-center" aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= searchPageUrl($page - 1, $filters) ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php
                $prev = 0;
                for ($p = 1; $p <= $totalPages; $p++):
                    $show = ($p == 1 || $p == $totalPages || abs($p - $page) <= 2);
                    if (!$show) {
                        if ($prev) echo '<li class="page-item disabled"><span class="page-link">…</span></li>';
                        $prev = 0;
                        continue;
                    }
                    $prev = $p;
                ?>
                    <li class="page-item <?= $p == $page ? 'active' : '' ?>">
                        <a class="page-link" href="<?= searchPageUrl($p, $filters) ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= searchPageUrl($page + 1, $filters) ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</div>

<script>
(function () {
    const allCities   = <?= json_encode($allCities) ?>;
    const filterState = document.getElementById('filterState');
    const filterCity  = document.getElementById('filterCity');
    const selectedCityId = <?= (int) ($filters['city_id'] ?? 0) ?>;

    function updateCities() {
        const stateId = parseInt(filterState.value) || 0;
        filterCity.innerHTML = '<option value="">All Cities</option>';
        allCities
            .filter(c => !stateId || parseInt(c.state_id) === stateId)
            .forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.id;
                opt.textContent = c.name;
                if (parseInt(c.id) === selectedCityId) opt.selected = true;
                filterCity.appendChild(opt);
            });
    }

    filterState.addEventListener('change', updateCities);
    updateCities();
})();
</script>
