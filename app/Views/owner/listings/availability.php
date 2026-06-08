<style>
.avail-cal { user-select:none; }
.avail-cal .cal-header { display:flex; align-items:center; justify-content:space-between; padding:10px 0 8px; }
.avail-cal .cal-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
.avail-cal .day-label { text-align:center; font-size:.72rem; color:#94a3b8; font-weight:600; padding:4px 0; }
.avail-cal .day-cell { text-align:center; font-size:.8rem; border-radius:6px; padding:6px 2px; cursor:pointer; border:1px solid #e2e8f0; transition:background .15s; }
.avail-cal .day-cell.empty { border:none; cursor:default; }
.avail-cal .day-cell.past { background:#f8fafc; color:#cbd5e1; cursor:not-allowed; border-color:#f1f5f9; }
.avail-cal .day-cell.today { border-color:#e84c2b; font-weight:700; }
.avail-cal .day-cell.blocked-manual { background:#fee2e2; color:#dc2626; border-color:#fecaca; }
.avail-cal .day-cell.blocked-ical { background:#fef3c7; color:#92400e; border-color:#fde68a; }
.avail-cal .day-cell.available:hover { background:#d1fae5; border-color:#6ee7b7; }
</style>

<div class="container py-4" style="max-width:780px;">

    <div class="d-flex align-items-center gap-3 mb-4 flex-wrap">
        <a href="/owner/listings/<?= (int)$listing['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Listing
        </a>
        <div>
            <h5 class="fw-bold mb-0">Availability Calendar</h5>
            <div class="text-muted small"><?= htmlspecialchars($listing['title']) ?></div>
        </div>
        <span class="badge ms-auto px-2 py-1" style="background:#fef3c7;color:#92400e;font-size:.72rem;">
            <i class="bi bi-patch-check me-1"></i>Shown publicly for Verified Hosts only
        </span>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade show mb-3">
                <?= $msg ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <!-- Legend -->
    <div class="d-flex gap-3 mb-3 flex-wrap" style="font-size:.8rem;">
        <span><span style="display:inline-block;width:14px;height:14px;border-radius:3px;background:#fff;border:1px solid #e2e8f0;vertical-align:middle;"></span> Available</span>
        <span><span style="display:inline-block;width:14px;height:14px;border-radius:3px;background:#fee2e2;vertical-align:middle;"></span> Blocked (manual)</span>
        <span><span style="display:inline-block;width:14px;height:14px;border-radius:3px;background:#fef3c7;vertical-align:middle;"></span> Blocked (iCal import)</span>
    </div>

    <!-- Calendar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 avail-cal" id="ownerCal">
            <div class="cal-header">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="calPrev"><i class="bi bi-chevron-left"></i></button>
                <span class="fw-semibold" id="calTitle"></span>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="calNext"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="cal-grid" id="calGrid"></div>
            <div class="text-muted small mt-3">Click a future date to block or unblock it manually. iCal-imported dates can only be removed by clearing the iCal URL.</div>
        </div>
    </div>

    <!-- iCal Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold d-flex align-items-center gap-2">
            <i class="bi bi-calendar2-check" style="color:#e84c2b;"></i> iCal Sync (Airbnb / Booking.com)
        </div>
        <div class="card-body p-4">
            <p class="text-muted small mb-3">
                Paste your Airbnb or Booking.com iCal export URL to automatically import blocked dates.
                <br>The calendar syncs every 30 minutes automatically.
            </p>

            <?php if (!empty($listing['ical_last_synced_at'])): ?>
                <div class="alert alert-success py-2 small mb-3">
                    <i class="bi bi-check-circle me-1"></i>
                    Last synced: <?= date('d M Y, g:i A', strtotime($listing['ical_last_synced_at'])) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/owner/listings/<?= (int)$listing['id'] ?>/availability/ical">
                <?= CSRF::field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Airbnb / Booking.com iCal URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                        <input type="url" name="ical_url" class="form-control"
                               value="<?= htmlspecialchars($listing['ical_import_url'] ?? '') ?>"
                               placeholder="https://www.airbnb.com/calendar/ical/...">
                    </div>
                    <div class="form-text">Leave blank to remove the iCal sync.</div>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm px-3" style="background:#e84c2b;color:#fff;">
                        <i class="bi bi-save me-1"></i>Save & Sync Now
                    </button>
                    <?php if (!empty($listing['ical_import_url'])): ?>
                    <form method="POST" action="/owner/listings/<?= (int)$listing['id'] ?>/availability/sync" class="m-0">
                        <?= CSRF::field() ?>
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-arrow-repeat me-1"></i>Sync Now
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- How to get iCal URL -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">How to get your iCal URL</div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="fw-semibold small mb-1"><i class="bi bi-house-fill me-1" style="color:#e84c2b;"></i>Airbnb</div>
                    <ol class="small text-muted ps-3 mb-0" style="line-height:1.9;">
                        <li>Go to your listing on Airbnb</li>
                        <li>Click <strong>Calendar</strong></li>
                        <li>Click <strong>Availability settings</strong></li>
                        <li>Scroll to <strong>Sync calendars</strong></li>
                        <li>Click <strong>Export Calendar</strong> → copy the link</li>
                    </ol>
                </div>
                <div class="col-md-6">
                    <div class="fw-semibold small mb-1"><i class="bi bi-building me-1" style="color:#003580;"></i>Booking.com</div>
                    <ol class="small text-muted ps-3 mb-0" style="line-height:1.9;">
                        <li>Go to your property on Booking.com extranet</li>
                        <li>Click <strong>Calendar</strong></li>
                        <li>Click <strong>Sync calendar</strong></li>
                        <li>Select <strong>Export</strong> → copy the iCal link</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CSRF_TOKEN = '<?= $_SESSION['csrf_token'] ?? '' ?>';
const LISTING_ID = <?= (int)$listing['id'] ?>;
const TODAY = new Date(); TODAY.setHours(0,0,0,0);

// Build blocked map: date string → source
const blockedMap = {};
<?php foreach ($blocked as $b): ?>
blockedMap['<?= $b['blocked_date'] ?>'] = '<?= $b['source'] ?>';
<?php endforeach; ?>

let current = new Date(TODAY.getFullYear(), TODAY.getMonth(), 1);

function pad(n) { return String(n).padStart(2, '0'); }
function dateStr(y, m, d) { return y + '-' + pad(m+1) + '-' + pad(d); }

function render() {
    const y = current.getFullYear(), m = current.getMonth();
    document.getElementById('calTitle').textContent =
        new Date(y, m, 1).toLocaleDateString('en-MY', { month:'long', year:'numeric' });

    const grid = document.getElementById('calGrid');
    grid.innerHTML = '';

    ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(d => {
        const el = document.createElement('div');
        el.className = 'day-label'; el.textContent = d;
        grid.appendChild(el);
    });

    const startDay = new Date(y, m, 1).getDay();
    const daysInMonth = new Date(y, m + 1, 0).getDate();

    for (let i = 0; i < startDay; i++) {
        const el = document.createElement('div');
        el.className = 'day-cell empty';
        grid.appendChild(el);
    }

    for (let d = 1; d <= daysInMonth; d++) {
        const ds = dateStr(y, m, d);
        const cellDate = new Date(y, m, d);
        const isPast   = cellDate < TODAY;
        const isToday  = cellDate.getTime() === TODAY.getTime();
        const source   = blockedMap[ds];

        const el = document.createElement('div');
        el.textContent = d;
        el.dataset.date = ds;

        if (isPast) {
            el.className = 'day-cell past';
        } else if (source === 'ical') {
            el.className = 'day-cell blocked-ical';
            el.title = 'Blocked via iCal import';
        } else if (source === 'manual') {
            el.className = 'day-cell blocked-manual' + (isToday ? ' today' : '');
            el.title = 'Click to unblock';
            el.addEventListener('click', toggleDate);
        } else {
            el.className = 'day-cell available' + (isToday ? ' today' : '');
            el.title = 'Click to block';
            el.addEventListener('click', toggleDate);
        }

        grid.appendChild(el);
    }
}

function toggleDate(e) {
    const date = e.currentTarget.dataset.date;
    fetch('/owner/listings/' + LISTING_ID + '/availability/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'csrf_token=' + encodeURIComponent(CSRF_TOKEN) + '&date=' + encodeURIComponent(date)
    })
    .then(r => r.json())
    .then(data => {
        if (data.status === 'blocked')   blockedMap[date] = 'manual';
        if (data.status === 'unblocked') delete blockedMap[date];
        render();
    });
}

document.getElementById('calPrev').addEventListener('click', () => {
    current.setMonth(current.getMonth() - 1);
    render();
});
document.getElementById('calNext').addEventListener('click', () => {
    current.setMonth(current.getMonth() + 1);
    render();
});

render();
</script>
