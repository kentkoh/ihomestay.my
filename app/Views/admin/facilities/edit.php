<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-3">
            <a href="/admin/facilities" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left me-1"></i> Back to Facilities
            </a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/admin/facilities/<?= $facility['id'] ?>/update">
                    <?= CSRF::field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facility Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($facility['name']) ?>"
                               required maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control"
                               value="<?= htmlspecialchars($facility['category']) ?>"
                               list="category-suggestions" required maxlength="100">
                        <datalist id="category-suggestions">
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>">
                            <?php endforeach; ?>
                        </datalist>
                        <div class="form-text">Pick an existing category or type a new one.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" style="width:120px;"
                               value="<?= (int) $facility['sort_order'] ?>" min="0" max="999">
                        <div class="form-text">Lower number appears first within the category.</div>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                   <?= $facility['is_active'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">Active (visible to owners)</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn" style="background:#e84c2b;color:#fff;">Update Facility</button>
                        <a href="/admin/facilities" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3 border-danger-subtle">
            <div class="card-body p-3 d-flex justify-content-between align-items-center">
                <div>
                    <div class="fw-semibold text-danger small">Delete Facility</div>
                    <div class="text-muted small">This cannot be undone.</div>
                </div>
                <form method="POST" action="/admin/facilities/<?= $facility['id'] ?>/delete"
                      onsubmit="return confirm('Delete \'<?= htmlspecialchars(addslashes($facility['name'])) ?>\'? This cannot be undone.')">
                    <?= CSRF::field() ?>
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
