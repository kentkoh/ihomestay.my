<?php
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
    <h5 class="fw-bold mb-4">Edit Listing</h5>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="/owner/listings/<?= $listing['id'] ?>/update" enctype="multipart/form-data">
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
                               value="<?= htmlspecialchars($listing['postcode']) ?>" maxlength="10">
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
                <input type="hidden" name="latitude" id="latitude" value="<?= htmlspecialchars($listing['latitude'] ?? '') ?>">
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

        <!-- Contact -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Contact</div>
            <div class="card-body p-4">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">WhatsApp Number</label>
                    <div class="input-group">
                        <span class="input-group-text">+60</span>
                        <input type="text" name="whatsapp" class="form-control"
                               value="<?= htmlspecialchars($listing['whatsapp']) ?>">
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
                                               <?= in_array($f['id'], $selectedFacIds) ? 'checked' : '' ?>>
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

        <!-- Photos (managed via AJAX — no nested forms) -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Photos</span>
                <small class="text-muted"><span id="photo-count"><?= count($images) ?></span> / 10</small>
            </div>
            <div class="card-body p-4">
                <div class="row g-2 mb-3" id="photo-grid">
                    <?php foreach ($images as $img): ?>
                        <div class="col-6 col-md-3 photo-item" data-image-id="<?= $img['id'] ?>">
                            <div class="position-relative">
                                <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($img['filename']) ?>"
                                     class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;" alt="">
                                <?php if ($img['is_primary']): ?>
                                    <span class="position-absolute top-0 start-0 m-1 badge bg-warning text-dark primary-badge" style="font-size:.65rem;">Main</span>
                                <?php else: ?>
                                    <button type="button" class="position-absolute top-0 start-0 m-1 btn btn-warning btn-sm set-primary-btn"
                                            style="font-size:.6rem;padding:1px 6px;line-height:1.4;" title="Set as main photo"
                                            data-image-id="<?= $img['id'] ?>">★</button>
                                <?php endif; ?>
                                <button type="button" class="position-absolute top-0 end-0 m-1 btn btn-danger btn-sm delete-image-btn"
                                        style="font-size:.6rem;padding:1px 6px;line-height:1.4;"
                                        data-image-id="<?= $img['id'] ?>">✕</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Upload progress bar -->
                <div id="upload-progress-wrap" class="mb-3" style="display:none;">
                    <div class="d-flex justify-content-between small mb-1">
                        <span id="upload-status-text" class="text-muted">Uploading…</span>
                        <span id="upload-pct" class="text-muted">0%</span>
                    </div>
                    <div class="progress" style="height:10px;border-radius:6px;">
                        <div id="upload-progress-bar" class="progress-bar progress-bar-striped progress-bar-animated"
                             role="progressbar" style="width:0%;transition:width .15s;"></div>
                    </div>
                </div>

                <!-- File picker -->
                <div id="upload-area">
                    <label for="photo-input" class="btn btn-outline-secondary btn-sm" id="photo-pick-btn">
                        <i class="bi bi-image me-1"></i> Choose Photos
                    </label>
                    <input type="file" id="photo-input" multiple accept="image/jpeg,image/png,image/webp" style="display:none;">
                    <div class="form-text mt-1">
                        Up to <span id="remaining-count"><?= 10 - count($images) ?></span> more photo(s). Max 5 MB each. JPG, PNG, WebP.
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn px-4" style="background:#e84c2b;color:#fff;">
                <?= $listing['status'] === 'rejected' ? 'Update & Resubmit for Review' : 'Save Changes' ?>
            </button>
            <a href="/owner/listings" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
const citiesByState = <?= json_encode($citiesByState) ?>;
const citySelect    = document.getElementById('city_id');
const stateSelect   = document.getElementById('state_id');

function populateCities(stateId, selectedCityId) {
    citySelect.innerHTML = '<option value="">Select city</option>';
    (citiesByState[stateId] || []).forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        if (String(c.id) === String(selectedCityId)) opt.selected = true;
        citySelect.appendChild(opt);
    });
}

stateSelect.addEventListener('change', () => populateCities(stateSelect.value, ''));
if (stateSelect.value) populateCities(stateSelect.value, '<?= $listing['city_id'] ?>');

const initLat = <?= json_encode($listing['latitude']) ?>;
const initLng = <?= json_encode($listing['longitude']) ?>;
const center  = (initLat && initLng) ? [initLat, initLng] : [4.2105, 108.9758];
const zoom    = (initLat && initLng) ? 14 : 6;
const map     = L.map('map').setView(center, zoom);
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

if (initLat && initLng) setPin(initLat, initLng);

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
                const lat = parseFloat(data[0].lat); const lng = parseFloat(data[0].lon);
                setPin(lat, lng); map.setView([lat, lng], 16);
            } else { alert('Address not found. Try pinning manually on the map.'); }
        });
}
document.getElementById('map-search-btn').addEventListener('click', searchAddress);
document.getElementById('map-search').addEventListener('keypress', e => { if (e.key === 'Enter') { e.preventDefault(); searchAddress(); } });

// ── Photo management (AJAX) ──────────────────────────────────────────────────
const LISTING_ID  = <?= $listing['id'] ?>;
const CSRF_TOKEN  = <?= json_encode(CSRF::token()) ?>;
let   photoCount  = <?= count($images) ?>;

function updateCountLabels() {
    document.getElementById('photo-count').textContent     = photoCount;
    document.getElementById('remaining-count').textContent = Math.max(0, 10 - photoCount);
    document.getElementById('photo-pick-btn').disabled     = photoCount >= 10;
}

// Attach events to a .photo-item element
function attachPhotoEvents(item) {
    item.querySelector('.delete-image-btn')?.addEventListener('click', function () {
        deleteImage(this.dataset.imageId, item);
    });
    item.querySelector('.set-primary-btn')?.addEventListener('click', function () {
        setPrimaryImage(this.dataset.imageId);
    });
}

document.querySelectorAll('.photo-item').forEach(attachPhotoEvents);

// File picker → auto-upload
document.getElementById('photo-input').addEventListener('change', function () {
    if (!this.files.length) return;
    uploadPhotos(this.files);
    this.value = '';
});

function uploadPhotos(files) {
    const wrap  = document.getElementById('upload-progress-wrap');
    const bar   = document.getElementById('upload-progress-bar');
    const label = document.getElementById('upload-status-text');
    const pct   = document.getElementById('upload-pct');

    bar.className = 'progress-bar progress-bar-striped progress-bar-animated';
    bar.style.width = '0%';
    label.textContent = 'Uploading…';
    pct.textContent   = '0%';
    wrap.style.display = 'block';

    const fd = new FormData();
    fd.append('csrf_token', CSRF_TOKEN);
    Array.from(files).forEach(f => fd.append('files[]', f));

    const xhr = new XMLHttpRequest();

    xhr.upload.addEventListener('progress', e => {
        if (!e.lengthComputable) return;
        const p = Math.round(e.loaded / e.total * 100);
        bar.style.width   = p + '%';
        pct.textContent   = p + '%';
    });

    xhr.addEventListener('load', () => {
        let data;
        try { data = JSON.parse(xhr.responseText); } catch { data = { success: false }; }

        if (data.success && data.images.length) {
            bar.className   = 'progress-bar bg-success';
            bar.style.width = '100%';
            label.textContent = '✓ Uploaded!';
            pct.textContent   = '';
            data.images.forEach(addImageToGrid);
            if (data.skipped && data.skipped.length) {
                label.textContent = '✓ Done. Skipped: ' + data.skipped.join(', ');
            }
            setTimeout(() => { wrap.style.display = 'none'; }, 3000);
        } else if (data.success && data.images.length === 0) {
            bar.className   = 'progress-bar bg-danger';
            label.textContent = 'No files saved. ' + (data.skipped?.join(', ') || 'Check file format (JPG/PNG/WebP) and size (max 5MB).');
        } else {
            bar.className   = 'progress-bar bg-danger';
            label.textContent = 'Error: ' + (data.error || 'Unknown error. Try again.');
        }
    });

    xhr.addEventListener('error', () => {
        bar.className   = 'progress-bar bg-danger';
        label.textContent = 'Upload failed. Check connection.';
    });

    xhr.open('POST', `/owner/listings/${LISTING_ID}/images/upload`);
    xhr.send(fd);
}

function addImageToGrid(img) {
    photoCount++;
    updateCountLabels();

    const col = document.createElement('div');
    col.className = 'col-6 col-md-3 photo-item';
    col.dataset.imageId = img.id;

    const isPrimary = parseInt(img.is_primary) === 1;
    col.innerHTML = `
        <div class="position-relative">
            <img src="/uploads/listings/${LISTING_ID}/${img.filename}"
                 class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;" alt="">
            ${isPrimary
                ? `<span class="position-absolute top-0 start-0 m-1 badge bg-warning text-dark primary-badge" style="font-size:.65rem;">Main</span>`
                : `<button type="button" class="position-absolute top-0 start-0 m-1 btn btn-warning btn-sm set-primary-btn"
                       style="font-size:.6rem;padding:1px 6px;line-height:1.4;" title="Set as main photo"
                       data-image-id="${img.id}">★</button>`
            }
            <button type="button" class="position-absolute top-0 end-0 m-1 btn btn-danger btn-sm delete-image-btn"
                    style="font-size:.6rem;padding:1px 6px;line-height:1.4;"
                    data-image-id="${img.id}">✕</button>
        </div>`;

    document.getElementById('photo-grid').appendChild(col);
    attachPhotoEvents(col);
}

function deleteImage(imageId, item) {
    if (!confirm('Delete this photo?')) return;

    const fd = new FormData();
    fd.append('csrf_token', CSRF_TOKEN);

    fetch(`/owner/listings/${LISTING_ID}/images/${imageId}/delete`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            item.remove();
            photoCount--;
            updateCountLabels();

            if (data.new_primary) {
                document.querySelectorAll('.photo-item').forEach(el => {
                    if (String(el.dataset.imageId) === String(data.new_primary)) {
                        const btn = el.querySelector('.set-primary-btn');
                        if (btn) {
                            const badge = document.createElement('span');
                            badge.className = 'position-absolute top-0 start-0 m-1 badge bg-warning text-dark primary-badge';
                            badge.style.fontSize = '.65rem';
                            badge.textContent = 'Main';
                            btn.replaceWith(badge);
                        }
                    }
                });
            }
        })
        .catch(err => alert('Delete failed: ' + err));
}

function setPrimaryImage(imageId) {
    const fd = new FormData();
    fd.append('csrf_token', CSRF_TOKEN);

    fetch(`/owner/listings/${LISTING_ID}/images/${imageId}/primary`, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            document.querySelectorAll('.photo-item').forEach(el => {
                const id      = el.dataset.imageId;
                const wrapper = el.querySelector('.position-relative');
                if (String(id) === String(imageId)) {
                    const btn = wrapper.querySelector('.set-primary-btn');
                    if (btn) {
                        const badge = document.createElement('span');
                        badge.className = 'position-absolute top-0 start-0 m-1 badge bg-warning text-dark primary-badge';
                        badge.style.fontSize = '.65rem';
                        badge.textContent = 'Main';
                        btn.replaceWith(badge);
                    }
                } else {
                    const badge = wrapper.querySelector('.primary-badge');
                    if (badge) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'position-absolute top-0 start-0 m-1 btn btn-warning btn-sm set-primary-btn';
                        btn.style.cssText = 'font-size:.6rem;padding:1px 6px;line-height:1.4;';
                        btn.title = 'Set as main photo';
                        btn.dataset.imageId = id;
                        btn.textContent = '★';
                        btn.addEventListener('click', function () { setPrimaryImage(this.dataset.imageId); });
                        badge.replaceWith(btn);
                    }
                }
            });
        })
        .catch(() => alert('Failed. Try again.'));
}
</script>
