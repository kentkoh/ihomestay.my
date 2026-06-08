<?php
$old = $_SESSION['form_old'] ?? [];
unset($_SESSION['form_old']);
$citiesByState = [];
foreach ($cities as $city) {
    $citiesByState[$city['state_id']][] = $city;
}
?>
<div class="container py-4">
    <div class="mb-3">
        <a href="/owner/listings" class="text-decoration-none text-muted small">
            <i class="bi bi-arrow-left me-1"></i> Back to My Listings
        </a>
    </div>
    <h5 class="fw-bold mb-4">Add New Listing</h5>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="/owner/listings/store" enctype="multipart/form-data">
        <?= CSRF::field() ?>

        <!-- Basic Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Basic Info</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Listing Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control"
                           value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                           placeholder="e.g. Cozy 3-Bedroom Homestay Near Kuantan Beach"
                           required maxlength="200">
                </div>
                <div class="mb-0">
                    <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                    <textarea name="description" class="form-control" rows="5" required
                              placeholder="Describe your homestay — location, surroundings, what makes it special..."><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Location -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Location</div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">State <span class="text-danger">*</span></label>
                        <select name="state_id" id="state_id" class="form-select" required>
                            <option value="">Select state</option>
                            <?php foreach ($states as $s): ?>
                                <option value="<?= $s['id'] ?>"
                                    <?= ($old['state_id'] ?? '') == $s['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">City <span class="text-danger">*</span></label>
                        <select name="city_id" id="city_id" class="form-select" required>
                            <option value="">Select state first</option>
                        </select>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-md-9">
                        <label class="form-label fw-semibold">Full Address <span class="text-danger">*</span></label>
                        <input type="text" name="address" class="form-control"
                               value="<?= htmlspecialchars($old['address'] ?? '') ?>"
                               placeholder="Street, area, district..." required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Postcode</label>
                        <input type="text" name="postcode" class="form-control"
                               value="<?= htmlspecialchars($old['postcode'] ?? '') ?>"
                               placeholder="e.g. 25000" maxlength="10">
                    </div>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Pin Location on Map</div>
            <div class="card-body p-4">
                <p class="text-muted small mb-2">Search your address or click on the map to drop a pin. This helps guests find you easily.</p>
                <div class="input-group mb-1">
                    <input type="text" id="map-search" class="form-control" placeholder="e.g. 25200 or Jalan Merdeka, Kuantan">
                    <button type="button" class="btn btn-primary" id="map-search-btn">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary mb-2" id="use-address-btn">
                    <i class="bi bi-arrow-up-circle me-1"></i> Use address &amp; postcode from form
                </button>
                <div id="map-error" class="alert alert-warning py-1 px-2 small mb-2" style="display:none;"></div>
                <div id="map" style="height:350px;border-radius:8px;border:1px solid #dee2e6;"></div>
                <div class="d-flex gap-3 mt-2">
                    <div class="text-muted small">
                        <i class="bi bi-geo-alt me-1"></i>
                        Lat: <span id="lat-display">not set</span> &nbsp; Lng: <span id="lng-display">not set</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" id="clear-pin">Clear pin</button>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="<?= htmlspecialchars($old['latitude'] ?? '') ?>">
                <input type="hidden" name="longitude" id="longitude" value="<?= htmlspecialchars($old['longitude'] ?? '') ?>">
            </div>
        </div>

        <!-- Pricing & Details -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Pricing & Details</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Price / Night (RM) <span class="text-danger">*</span></label>
                        <input type="number" name="price_per_night" class="form-control"
                               value="<?= htmlspecialchars($old['price_per_night'] ?? '') ?>"
                               min="1" step="0.01" required placeholder="150">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Min Nights</label>
                        <input type="number" name="min_nights" class="form-control"
                               value="<?= (int) ($old['min_nights'] ?? 1) ?>" min="1" max="30">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Max Guests</label>
                        <input type="number" name="max_guests" class="form-control"
                               value="<?= (int) ($old['max_guests'] ?? 4) ?>" min="1" max="50">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bedrooms</label>
                        <input type="number" name="bedrooms" class="form-control"
                               value="<?= (int) ($old['bedrooms'] ?? 2) ?>" min="0" max="20"
                               title="Enter 0 for studio">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Bathrooms</label>
                        <input type="number" name="bathrooms" class="form-control"
                               value="<?= (int) ($old['bathrooms'] ?? 1) ?>" min="1" max="20">
                    </div>
                </div>
            </div>
        </div>

        <!-- Long-Stay Pricing -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex align-items-center justify-content-between">
                <span class="fw-semibold">Long-Stay Pricing <span class="text-muted fw-normal" style="font-size:.85rem;">(optional)</span></span>
                <span class="badge px-2 py-1" style="background:#fef3c7;color:#92400e;font-size:.72rem;">
                    <i class="bi bi-patch-check me-1"></i>Shown publicly for Verified Hosts only
                </span>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">Set discounted rates for longer stays — save now, they go live automatically once you're a Verified Host.</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">2-Night Rate (RM/night)</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="price_2nights" class="form-control"
                                   value="<?= htmlspecialchars($old['price_2nights'] ?? '') ?>"
                                   min="1" step="0.01" placeholder="e.g. 130">
                        </div>
                        <div class="form-text">Rate per night when guest stays 2 nights.</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">3-Night Rate (RM/night)</label>
                        <div class="input-group">
                            <span class="input-group-text">RM</span>
                            <input type="number" name="price_3nights" class="form-control"
                                   value="<?= htmlspecialchars($old['price_3nights'] ?? '') ?>"
                                   min="1" step="0.01" placeholder="e.g. 110">
                        </div>
                        <div class="form-text">Rate per night when guest stays 3+ nights.</div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="p-3 rounded-3 w-100" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="small text-muted mb-1">Example savings shown to guests</div>
                            <div class="small"><span class="fw-semibold">2 nights:</span> <span id="save2">—</span></div>
                            <div class="small"><span class="fw-semibold">3 nights:</span> <span id="save3">—</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Contact</div>
            <div class="card-body p-4">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">WhatsApp Number</label>
                    <?php $wa = Auth::user()['whatsapp'] ?? ''; ?>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                        <input type="text" class="form-control bg-light"
                               value="<?= htmlspecialchars($wa ?: 'Not set') ?>" readonly>
                    </div>
                    <div class="form-text">
                        <?php if ($wa): ?>
                            Your account WhatsApp number is applied automatically to all your listings.
                            To change it, <a href="/owner/profile">update your profile</a>.
                        <?php else: ?>
                            <span class="text-danger"><i class="bi bi-exclamation-circle"></i>
                            No WhatsApp number on your account. <a href="/owner/profile">Set it in your profile</a> so guests can contact you.</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Facilities -->
        <?php if (!empty($facilities)): ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Facilities</div>
            <div class="card-body p-4">
                <?php foreach ($facilities as $category => $items): ?>
                    <div class="mb-3">
                        <div class="text-muted small fw-semibold text-uppercase mb-2" style="letter-spacing:.06em;"><?= htmlspecialchars($category) ?></div>
                        <div class="row g-2">
                            <?php foreach ($items as $f): ?>
                                <div class="col-6 col-md-4 col-lg-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="facilities[]" value="<?= $f['id'] ?>"
                                               id="fac_<?= $f['id'] ?>">
                                        <label class="form-check-label small" for="fac_<?= $f['id'] ?>">
                                            <?= htmlspecialchars($f['name']) ?>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Photos -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Photos</div>
            <div class="card-body p-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="text-muted" style="font-size:2rem;line-height:1;">&#128247;</div>
                    <div>
                        <div class="fw-semibold mb-1">Upload photos in the next step</div>
                        <div class="text-muted small">After saving your listing details, you will be taken to the photo upload screen where you can upload and preview your photos before submitting for review.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn px-4" style="background:#e84c2b;color:#fff;">Save &amp; Continue to Photos &rarr;</button>
            <a href="/owner/listings" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Savings calculator for tiered pricing
(function () {
    const baseInput = document.querySelector('[name="price_per_night"]');
    const p2Input   = document.querySelector('[name="price_2nights"]');
    const p3Input   = document.querySelector('[name="price_3nights"]');
    const save2El   = document.getElementById('save2');
    const save3El   = document.getElementById('save3');
    function updateSavings() {
        const base = parseFloat(baseInput.value) || 0;
        const p2   = parseFloat(p2Input.value)   || 0;
        const p3   = parseFloat(p3Input.value)   || 0;
        save2El.textContent = (base > 0 && p2 > 0 && p2 < base)
            ? 'RM' + p2.toFixed(0) + '/night (Save ' + Math.round((1 - p2/base)*100) + '%)'
            : '—';
        save3El.textContent = (base > 0 && p3 > 0 && p3 < base)
            ? 'RM' + p3.toFixed(0) + '/night (Save ' + Math.round((1 - p3/base)*100) + '%)'
            : '—';
    }
    [baseInput, p2Input, p3Input].forEach(el => el.addEventListener('input', updateSavings));
    updateSavings();
})();

const citiesByState = <?= json_encode($citiesByState) ?>;
const citySelect    = document.getElementById('city_id');
const stateSelect   = document.getElementById('state_id');

function populateCities(stateId, selectedCityId) {
    citySelect.innerHTML = '<option value="">Select city</option>';
    const cities = citiesByState[stateId] || [];
    cities.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        if (String(c.id) === String(selectedCityId)) opt.selected = true;
        citySelect.appendChild(opt);
    });
}

stateSelect.addEventListener('change', () => populateCities(stateSelect.value, ''));
if (stateSelect.value) populateCities(stateSelect.value, '<?= htmlspecialchars($old['city_id'] ?? '') ?>');

// Map
const map = L.map('map').setView([4.2105, 108.9758], 6);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

let marker = null;

function setPin(lat, lng) {
    if (marker) marker.remove();
    marker = L.marker([lat, lng], {draggable: true}).addTo(map);
    marker.on('dragend', e => updateCoords(e.target.getLatLng().lat, e.target.getLatLng().lng));
    updateCoords(lat, lng);
}

function updateCoords(lat, lng) {
    document.getElementById('latitude').value  = lat.toFixed(8);
    document.getElementById('longitude').value = lng.toFixed(8);
    document.getElementById('lat-display').textContent = lat.toFixed(6);
    document.getElementById('lng-display').textContent = lng.toFixed(6);
}

map.on('click', e => { setPin(e.latlng.lat, e.latlng.lng); map.setView(e.latlng, Math.max(map.getZoom(), 14)); });

document.getElementById('clear-pin').addEventListener('click', () => {
    if (marker) { marker.remove(); marker = null; }
    document.getElementById('latitude').value  = '';
    document.getElementById('longitude').value = '';
    document.getElementById('lat-display').textContent = 'not set';
    document.getElementById('lng-display').textContent = 'not set';
});

function showMapError(msg) {
    const el = document.getElementById('map-error');
    el.textContent = msg;
    el.style.display = '';
}
function hideMapError() {
    document.getElementById('map-error').style.display = 'none';
}

function searchAddress() {
    const q = document.getElementById('map-search').value.trim();
    if (!q) return;
    const btn = document.getElementById('map-search-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    hideMapError();
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&countrycodes=my&limit=1`, {
        headers: { 'Accept': 'application/json' }
    })
        .then(r => { if (!r.ok) throw new Error('Search failed (HTTP ' + r.status + ')'); return r.json(); })
        .then(data => {
            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lng = parseFloat(data[0].lon);
                setPin(lat, lng);
                map.setView([lat, lng], 16);
            } else {
                showMapError('Address not found. Try a different postcode or address, or pin manually on the map.');
            }
        })
        .catch(err => {
            showMapError('Search error: ' + err.message + '. Try pinning manually on the map.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-search"></i> Search';
        });
}

document.getElementById('use-address-btn').addEventListener('click', () => {
    const address  = (document.querySelector('[name="address"]')?.value  ?? '').trim();
    const postcode = (document.querySelector('[name="postcode"]')?.value ?? '').trim();
    const state    = document.querySelector('[name="state_id"] option:checked')?.textContent?.trim() ?? '';
    const parts    = [address, postcode, state, 'Malaysia'].filter(Boolean);
    if (!parts.length) return;
    document.getElementById('map-search').value = parts.join(', ');
    searchAddress();
});

document.getElementById('map-search-btn').addEventListener('click', searchAddress);
document.getElementById('map-search').addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); searchAddress(); } });

// Restore existing pin if form was resubmitted
const initLat = '<?= htmlspecialchars($old['latitude'] ?? '') ?>';
const initLng = '<?= htmlspecialchars($old['longitude'] ?? '') ?>';
if (initLat && initLng) { setPin(parseFloat(initLat), parseFloat(initLng)); map.setView([parseFloat(initLat), parseFloat(initLng)], 14); }
</script>
