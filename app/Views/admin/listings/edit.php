<?php
$citiesByState = [];
foreach ($cities as $city) {
    $citiesByState[$city['state_id']][] = $city;
}
$statusOptions = ['pending' => 'Pending', 'published' => 'Published', 'rejected' => 'Rejected', 'suspended' => 'Suspended', 'draft' => 'Draft'];
?>
<div class="mb-3">
    <a href="/admin/listings" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Listings
    </a>
</div>

<?php if (!empty($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
        <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Owner info bar -->
<div class="card border-0 shadow-sm mb-4" style="background:#f8fafc;">
    <div class="card-body py-3 px-4 d-flex align-items-center gap-3">
        <i class="bi bi-person-circle fs-4 text-muted"></i>
        <div>
            <div class="fw-semibold small"><?= htmlspecialchars($listing['owner_name']) ?></div>
            <div class="text-muted" style="font-size:.78rem;"><?= htmlspecialchars($listing['owner_email']) ?></div>
        </div>
        <a href="/listing/<?= htmlspecialchars($listing['slug']) ?>" target="_blank"
           class="btn btn-sm btn-outline-secondary ms-auto">
            <i class="bi bi-box-arrow-up-right me-1"></i>View public page
        </a>
    </div>
</div>

<form method="POST" action="/admin/listings/<?= $listing['id'] ?>/update">
    <?= CSRF::field() ?>

    <!-- Basic Info -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Basic Info</div>
        <div class="card-body p-4">
            <div class="mb-3">
                <label class="form-label fw-semibold">Listing Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($listing['title']) ?>" required maxlength="200">
            </div>
            <div class="mb-0">
                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" required><?= htmlspecialchars($listing['description']) ?></textarea>
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
                            <option value="<?= $s['id'] ?>" <?= $s['id'] == $listing['state_id'] ? 'selected' : '' ?>>
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
                           value="<?= htmlspecialchars($listing['address']) ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Postcode</label>
                    <input type="text" name="postcode" class="form-control"
                           value="<?= htmlspecialchars($listing['postcode'] ?? '') ?>" maxlength="10">
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Pin Location on Map</div>
        <div class="card-body p-4">
            <div class="input-group mb-2">
                <input type="text" id="map-search" class="form-control" placeholder="Search address to reposition pin">
                <button type="button" class="btn btn-outline-secondary" id="map-search-btn"><i class="bi bi-search"></i></button>
            </div>
            <div id="map" style="height:350px;border-radius:8px;border:1px solid #dee2e6;"></div>
            <div class="d-flex gap-3 mt-2">
                <div class="text-muted small">
                    <i class="bi bi-geo-alt me-1"></i>
                    Lat: <span id="lat-display"><?= $listing['latitude'] ?? 'not set' ?></span>
                    &nbsp; Lng: <span id="lng-display"><?= $listing['longitude'] ?? 'not set' ?></span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary ms-auto" id="clear-pin">Clear pin</button>
            </div>
            <input type="hidden" name="latitude"  id="latitude"  value="<?= htmlspecialchars($listing['latitude']  ?? '') ?>">
            <input type="hidden" name="longitude" id="longitude" value="<?= htmlspecialchars($listing['longitude'] ?? '') ?>">
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
                           value="<?= htmlspecialchars($listing['price_per_night']) ?>" min="1" step="0.01" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Min Nights</label>
                    <input type="number" name="min_nights" class="form-control"
                           value="<?= (int) $listing['min_nights'] ?>" min="1" max="30">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max Guests</label>
                    <input type="number" name="max_guests" class="form-control"
                           value="<?= (int) $listing['max_guests'] ?>" min="1" max="50">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control"
                           value="<?= (int) $listing['bedrooms'] ?>" min="0" max="20">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Bathrooms</label>
                    <input type="number" name="bathrooms" class="form-control"
                           value="<?= (int) $listing['bathrooms'] ?>" min="1" max="20">
                </div>
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Listing Status</div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" id="statusSelect" class="form-select">
                        <?php foreach ($statusOptions as $val => $label): ?>
                            <option value="<?= $val ?>" <?= $listing['status'] === $val ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-8" id="rejectionReasonWrap" style="display:none;">
                    <label class="form-label fw-semibold">Rejection Reason</label>
                    <textarea name="rejection_reason" class="form-control" rows="2"
                              placeholder="Reason shown to owner"><?= htmlspecialchars($listing['rejection_reason'] ?? '') ?></textarea>
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
                                           id="fac_<?= $f['id'] ?>"
                                           <?= in_array((string) $f['id'], array_map('strval', $selectedFacIds)) ? 'checked' : '' ?>>
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

    <!-- Photos (view-only) -->
    <?php if (!empty($images)): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Photos</div>
        <div class="card-body p-4">
            <div class="d-flex flex-wrap gap-2">
                <?php foreach ($images as $img): ?>
                    <div class="position-relative">
                        <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($img['filename']) ?>"
                             style="width:120px;height:90px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;"
                             alt="">
                        <?php if ($img['is_primary']): ?>
                            <span class="position-absolute top-0 start-0 badge bg-success" style="font-size:.6rem;">Primary</span>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-text mt-2">Photos can be managed by the listing owner. Admin editing does not affect photos.</div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex gap-2">
        <button type="submit" class="btn px-4" style="background:#e84c2b;color:#fff;">Save Changes</button>
        <a href="/admin/listings" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
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
    if (stateSelect.value) populateCities(stateSelect.value, '<?= (int) $listing['city_id'] ?>');

    // Status → rejection reason
    const statusSelect = document.getElementById('statusSelect');
    const rejWrap      = document.getElementById('rejectionReasonWrap');
    function toggleRejection() {
        rejWrap.style.display = statusSelect.value === 'rejected' ? 'flex' : 'none';
    }
    statusSelect.addEventListener('change', toggleRejection);
    toggleRejection();

    // Map
    const initLat = <?= json_encode($listing['latitude']  ?? null) ?>;
    const initLng = <?= json_encode($listing['longitude'] ?? null) ?>;
    const map     = L.map('map').setView(
        initLat && initLng ? [initLat, initLng] : [4.2105, 108.9758],
        initLat && initLng ? 14 : 6
    );
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

    if (initLat && initLng) setPin(parseFloat(initLat), parseFloat(initLng));

    map.on('click', e => { setPin(e.latlng.lat, e.latlng.lng); map.setView(e.latlng, Math.max(map.getZoom(), 14)); });

    document.getElementById('clear-pin').addEventListener('click', () => {
        if (marker) { marker.remove(); marker = null; }
        document.getElementById('latitude').value  = '';
        document.getElementById('longitude').value = '';
        document.getElementById('lat-display').textContent = 'not set';
        document.getElementById('lng-display').textContent = 'not set';
    });

    function searchAddress() {
        const q = document.getElementById('map-search').value.trim();
        if (!q) return;
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&countrycodes=my&limit=1`)
            .then(r => r.json())
            .then(data => {
                if (data.length > 0) {
                    const lat = parseFloat(data[0].lat);
                    const lng = parseFloat(data[0].lon);
                    setPin(lat, lng);
                    map.setView([lat, lng], 16);
                } else {
                    alert('Address not found. Try a more specific search or pin manually.');
                }
            });
    }

    document.getElementById('map-search-btn').addEventListener('click', searchAddress);
    document.getElementById('map-search').addEventListener('keypress', e => {
        if (e.key === 'Enter') { e.preventDefault(); searchAddress(); }
    });
})();
</script>
