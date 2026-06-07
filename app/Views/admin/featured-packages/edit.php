<?php $pageTitle = 'Edit Package'; ?>

<div class="mb-4">
    <a href="/admin/featured-packages" class="text-muted small" style="text-decoration:none;">
        <i class="bi bi-arrow-left me-1"></i>Back to Featured Packages
    </a>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius:16px;">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-1">Edit Package</h5>
                <p class="text-muted small mb-4">Changes sync immediately to the public feature listing page.</p>

                <form method="POST" action="/admin/featured-packages/<?= $package['id'] ?>/update">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Package Label</label>
                        <input type="text" name="label" class="form-control"
                               value="<?= htmlspecialchars($package['label']) ?>" required>
                        <div class="form-text">e.g. "7-Day Boost", "Weekend Special"</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Duration (days)</label>
                            <input type="number" name="days" class="form-control"
                                   value="<?= $package['days'] ?>" min="1" max="365" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control"
                                   value="<?= $package['sort_order'] ?>" min="0" max="99">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold">Normal Price (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="normal_price" class="form-control"
                                       value="<?= number_format($package['normal_price'], 2, '.', '') ?>"
                                       step="0.01" min="1" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold">Promo Price (RM)</label>
                            <div class="input-group">
                                <span class="input-group-text">RM</span>
                                <input type="number" name="promo_price" class="form-control"
                                       value="<?= $package['promo_price'] !== null ? number_format($package['promo_price'], 2, '.', '') : '' ?>"
                                       step="0.01" min="0" placeholder="Leave blank = no promo">
                            </div>
                            <div class="form-text">Guests pay promo price if set. Strikethrough shown on normal price.</div>
                        </div>
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                               <?= $package['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label fw-semibold" for="is_active">Active (visible to owners)</label>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">Save Changes</button>
                        <a href="/admin/featured-packages" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
