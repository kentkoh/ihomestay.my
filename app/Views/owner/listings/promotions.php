<div class="container py-4" style="max-width:760px;">

    <div class="d-flex align-items-center gap-3 mb-4">
        <a href="/owner/listings/<?= (int)$listing['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back to Listing
        </a>
        <div>
            <h5 class="fw-bold mb-0">Promotions</h5>
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

    <!-- Add Promotion -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Add Promotion</div>
        <div class="card-body p-4">
            <form method="POST" action="/owner/listings/<?= (int)$listing['id'] ?>/promotions">
                <?= CSRF::field() ?>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Promotion Label <span class="text-danger">*</span></label>
                        <input type="text" name="label" class="form-control"
                               placeholder="e.g. Weekend Special, Early Bird, Hari Raya Deal" maxlength="100" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount Type</label>
                        <select name="discount_type" id="discountType" class="form-select">
                            <option value="percent">Percentage (%)</option>
                            <option value="fixed">Fixed Amount (RM)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Discount Value <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text" id="discountPrefix">%</span>
                            <input type="number" name="discount_value" class="form-control"
                                   min="1" step="0.01" placeholder="10" required>
                        </div>
                        <div class="form-text">e.g. 20 for 20% off, or 50 for RM50 off</div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                               value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">End Date</label>
                        <input type="date" name="end_date" class="form-control"
                               value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn px-4" style="background:#e84c2b;color:#fff;">
                        <i class="bi bi-plus-lg me-1"></i>Add Promotion
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Existing Promotions -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Your Promotions</div>
        <?php if (empty($promotions)): ?>
            <div class="card-body text-center py-5 text-muted">
                <i class="bi bi-tag" style="font-size:2rem;color:#cbd5e1;"></i>
                <p class="mt-2 mb-0">No promotions yet. Add one above.</p>
            </div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <thead style="background:#f8fafc;font-size:.8rem;">
                    <tr>
                        <th class="ps-3">Label</th>
                        <th>Discount</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($promotions as $p):
                    $today  = date('Y-m-d');
                    $isPast = $p['end_date'] < $today;
                    $isFuture = $p['start_date'] > $today;
                    $isRunning = !$isPast && !$isFuture && $p['is_active'];
                ?>
                    <tr>
                        <td class="ps-3 fw-semibold"><?= htmlspecialchars($p['label']) ?></td>
                        <td>
                            <?php if ($p['discount_type'] === 'percent'): ?>
                                <span class="badge" style="background:#fef2f2;color:#e84c2b;"><?= (int)$p['discount_value'] ?>% OFF</span>
                            <?php else: ?>
                                <span class="badge" style="background:#fef2f2;color:#e84c2b;">RM<?= number_format($p['discount_value'], 0) ?> OFF</span>
                            <?php endif; ?>
                        </td>
                        <td class="small text-muted">
                            <?= date('d M Y', strtotime($p['start_date'])) ?> –
                            <?= date('d M Y', strtotime($p['end_date'])) ?>
                        </td>
                        <td>
                            <?php if ($isPast): ?>
                                <span class="badge bg-secondary">Expired</span>
                            <?php elseif ($isFuture): ?>
                                <span class="badge" style="background:#dbeafe;color:#1e40af;">Upcoming</span>
                            <?php elseif ($isRunning): ?>
                                <span class="badge" style="background:#d1fae5;color:#065f46;">Active</span>
                            <?php else: ?>
                                <span class="badge bg-light text-secondary border">Paused</span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-3 text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <?php if (!$isPast): ?>
                                <form method="POST" action="/owner/listings/<?= (int)$listing['id'] ?>/promotions/<?= (int)$p['id'] ?>/toggle">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-outline-secondary" style="font-size:.75rem;padding:2px 8px;"
                                            title="<?= $p['is_active'] ? 'Pause' : 'Activate' ?>">
                                        <i class="bi bi-<?= $p['is_active'] ? 'pause' : 'play' ?>-fill"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <form method="POST" action="/owner/listings/<?= (int)$listing['id'] ?>/promotions/<?= (int)$p['id'] ?>/delete"
                                      onsubmit="return confirm('Delete this promotion?')">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-outline-danger" style="font-size:.75rem;padding:2px 8px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.getElementById('discountType').addEventListener('change', function () {
    document.getElementById('discountPrefix').textContent = this.value === 'percent' ? '%' : 'RM';
});
</script>
