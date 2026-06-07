<?php
$currentStatus = $_GET['status'] ?? null;
$statusColors  = ['pending'=>'warning','published'=>'success','rejected'=>'danger','suspended'=>'dark','draft'=>'secondary'];
?>

<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="/admin/listings" class="btn btn-sm <?= !$currentStatus ? 'btn-dark' : 'btn-outline-secondary' ?>">
        All <span class="badge bg-secondary ms-1"><?= $counts['all'] ?></span>
    </a>
    <a href="/admin/listings?status=pending" class="btn btn-sm <?= $currentStatus === 'pending' ? 'btn-warning' : 'btn-outline-warning' ?>">
        Pending <span class="badge bg-warning text-dark ms-1"><?= $counts['pending'] ?></span>
    </a>
    <a href="/admin/listings?status=published" class="btn btn-sm <?= $currentStatus === 'published' ? 'btn-success' : 'btn-outline-success' ?>">
        Published <span class="badge bg-success ms-1"><?= $counts['published'] ?></span>
    </a>
    <a href="/admin/listings?status=rejected" class="btn btn-sm <?= $currentStatus === 'rejected' ? 'btn-danger' : 'btn-outline-danger' ?>">
        Rejected <span class="badge bg-danger ms-1"><?= $counts['rejected'] ?></span>
    </a>
    <a href="/admin/listings?status=suspended" class="btn btn-sm <?= $currentStatus === 'suspended' ? 'btn-dark' : 'btn-outline-dark' ?>">
        Suspended <span class="badge bg-dark ms-1"><?= $counts['suspended'] ?></span>
    </a>
</div>

<?php if (empty($listings)): ?>
    <div class="card border-0 shadow-sm text-center p-5 text-muted">
        No listings <?= $currentStatus ? "with status \"$currentStatus\"" : '' ?> yet.
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Listing</th>
                        <th>Owner</th>
                        <th>Location</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th class="text-center">Featured</th>
                        <th class="text-end pe-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($listings as $l): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($l['primary_image']): ?>
                                        <img src="/uploads/listings/<?= $l['id'] ?>/<?= htmlspecialchars($l['primary_image']) ?>"
                                             style="width:48px;height:36px;object-fit:cover;border-radius:4px;" alt="">
                                    <?php else: ?>
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                             style="width:48px;height:36px;flex-shrink:0;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-semibold small"><?= htmlspecialchars($l['title']) ?></div>
                                        <div class="text-muted" style="font-size:.72rem;">#<?= $l['id'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="small"><?= htmlspecialchars($l['owner_name']) ?></td>
                            <td class="small"><?= htmlspecialchars($l['city_name']) ?>, <?= htmlspecialchars($l['state_name']) ?></td>
                            <td class="small">RM<?= number_format($l['price_per_night'], 0) ?>/night</td>
                            <td>
                                <span class="badge bg-<?= $statusColors[$l['status']] ?? 'secondary' ?> text-capitalize">
                                    <?= $l['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if ($l['is_featured_active']): ?>
                                    <span title="<?= $l['featured_until'] ? 'Until ' . date('d M Y', strtotime($l['featured_until'])) : 'Featured forever' ?>">
                                        <i class="bi bi-star-fill text-warning fs-5"></i>
                                    </span>
                                    <?php if ($l['featured_until']): ?>
                                        <div class="text-muted" style="font-size:.68rem;">
                                            until <?= date('d M Y', strtotime($l['featured_until'])) ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-muted" style="font-size:.68rem;">forever</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="bi bi-star text-muted"></i>
                                <?php endif; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="d-flex gap-1 justify-content-end flex-wrap">
                                    <!-- Always available: view public page and edit -->
                                    <?php if ($l['status'] === 'published'): ?>
                                        <a href="/listing/<?= htmlspecialchars($l['slug']) ?>" target="_blank"
                                           class="btn btn-sm btn-outline-secondary" title="View public page">
                                            <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="/admin/listings/<?= $l['id'] ?>/edit"
                                       class="btn btn-sm btn-outline-primary" title="Edit listing">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    <!-- Status-specific actions -->
                                    <?php if ($l['status'] === 'pending'): ?>
                                        <form method="POST" action="/admin/listings/<?= $l['id'] ?>/approve">
                                            <?= CSRF::field() ?>
                                            <button class="btn btn-sm btn-success" title="Approve">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <button class="btn btn-sm btn-danger" title="Reject"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal"
                                                data-listing-id="<?= $l['id'] ?>"
                                                data-listing-title="<?= htmlspecialchars($l['title']) ?>">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    <?php endif; ?>
                                    <?php if ($l['status'] === 'published'): ?>
                                        <form method="POST" action="/admin/listings/<?= $l['id'] ?>/suspend">
                                            <?= CSRF::field() ?>
                                            <button class="btn btn-sm btn-outline-dark" title="Suspend"
                                                    onclick="return confirm('Suspend this listing?')">
                                                <i class="bi bi-pause-circle"></i>
                                            </button>
                                        </form>
                                        <?php if ($l['is_featured_active']): ?>
                                            <form method="POST" action="/admin/listings/<?= $l['id'] ?>/unfeature">
                                                <?= CSRF::field() ?>
                                                <button class="btn btn-sm btn-warning" title="Remove Featured"
                                                        onclick="return confirm('Remove featured status?')">
                                                    <i class="bi bi-star-fill"></i>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-outline-warning" title="Set as Featured"
                                                    data-bs-toggle="modal" data-bs-target="#featureModal"
                                                    data-listing-id="<?= $l['id'] ?>"
                                                    data-listing-title="<?= htmlspecialchars($l['title']) ?>">
                                                <i class="bi bi-star"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if ($l['status'] === 'suspended'): ?>
                                        <form method="POST" action="/admin/listings/<?= $l['id'] ?>/approve">
                                            <?= CSRF::field() ?>
                                            <button class="btn btn-sm btn-outline-success" title="Reinstate">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- Delete — always available -->
                                    <form method="POST" action="/admin/listings/<?= $l['id'] ?>/delete"
                                          onsubmit="return confirm('Permanently delete \"<?= addslashes(htmlspecialchars($l['title'])) ?>\"? This cannot be undone.')">
                                        <?= CSRF::field() ?>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete listing">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php if ($l['status'] === 'rejected' && $l['rejection_reason']): ?>
                            <tr>
                                <td colspan="6" class="ps-3 py-1">
                                    <small class="text-danger"><i class="bi bi-info-circle me-1"></i>Rejection reason: <?= htmlspecialchars($l['rejection_reason']) ?></small>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<!-- Feature Modal -->
<div class="modal fade" id="featureModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="featureForm" method="POST">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold"><i class="bi bi-star-fill text-warning me-2"></i>Set Featured Listing</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-3" id="feature-listing-name"></p>
                    <label class="form-label fw-semibold">Duration</label>
                    <div class="d-flex flex-column gap-2">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="duration" id="dur7" value="7">
                            <label class="form-check-label" for="dur7">7 days</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="duration" id="dur14" value="14">
                            <label class="form-check-label" for="dur14">14 days</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="duration" id="dur30" value="30">
                            <label class="form-check-label" for="dur30">30 days</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="duration" id="durCustom" value="custom">
                            <label class="form-check-label" for="durCustom">Custom date</label>
                        </div>
                        <div id="customDateWrap" class="ps-4" style="display:none;">
                            <input type="date" name="custom_date" id="customDate" class="form-control form-control-sm"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="duration" id="durForever" value="forever" checked>
                            <label class="form-check-label" for="durForever">Forever (no expiry)</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-semibold">
                        <i class="bi bi-star-fill me-1"></i>Set Featured
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="rejectForm" method="POST">
            <?= CSRF::field() ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-semibold">Reject Listing</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted small mb-2" id="reject-listing-name"></p>
                    <label class="form-label fw-semibold">Reason for rejection <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control" rows="3" required
                              placeholder="e.g. Photos are too blurry. Please upload clearer photos."></textarea>
                    <div class="form-text">The owner will see this reason.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger btn-sm">Confirm Reject</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('rejectModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('rejectForm').action = '/admin/listings/' + btn.dataset.listingId + '/reject';
    document.getElementById('reject-listing-name').textContent = btn.dataset.listingTitle;
});

document.getElementById('featureModal').addEventListener('show.bs.modal', function(e) {
    const btn = e.relatedTarget;
    document.getElementById('featureForm').action = '/admin/listings/' + btn.dataset.listingId + '/feature';
    document.getElementById('feature-listing-name').textContent = btn.dataset.listingTitle;
});

document.querySelectorAll('input[name="duration"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('customDateWrap').style.display =
            this.value === 'custom' ? 'block' : 'none';
    });
});
</script>
