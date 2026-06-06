<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex gap-3">
            <span class="text-success fw-semibold"><?= $counts['active'] ?> active</span>
            <span class="text-secondary"><?= $counts['inactive'] ?> inactive</span>
            <span class="text-secondary"><?= count($grouped) ?> categories</span>
        </div>
    </div>
    <a href="/admin/facilities/create" class="btn btn-sm" style="background:#e84c2b;color:#fff;">
        <i class="bi bi-plus-lg me-1"></i> Add Facility
    </a>
</div>

<?php if (empty($grouped)): ?>
    <div class="card border-0 shadow-sm text-center p-5 text-muted">
        No facilities yet. <a href="/admin/facilities/create">Add one</a>.
    </div>
<?php else: ?>
    <?php foreach ($grouped as $category => $facilities): ?>
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                <span class="fw-semibold small text-uppercase" style="letter-spacing:.05em;"><?= htmlspecialchars($category) ?></span>
                <span class="badge bg-light text-secondary border"><?= count($facilities) ?></span>
            </div>
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Name</th>
                            <th style="width:80px;">Order</th>
                            <th style="width:90px;">Status</th>
                            <th style="width:160px;" class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($facilities as $f): ?>
                            <tr>
                                <td class="ps-3"><?= htmlspecialchars($f['name']) ?></td>
                                <td class="text-muted small"><?= (int) $f['sort_order'] ?></td>
                                <td>
                                    <?php if ($f['is_active']): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <!-- Toggle -->
                                        <form method="POST" action="/admin/facilities/<?= $f['id'] ?>/toggle">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-secondary" title="<?= $f['is_active'] ? 'Deactivate' : 'Activate' ?>">
                                                <i class="bi bi-<?= $f['is_active'] ? 'eye-slash' : 'eye' ?>"></i>
                                            </button>
                                        </form>
                                        <!-- Edit -->
                                        <a href="/admin/facilities/<?= $f['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- Delete -->
                                        <form method="POST" action="/admin/facilities/<?= $f['id'] ?>/delete" onsubmit="return confirm('Delete \'<?= htmlspecialchars(addslashes($f['name'])) ?>\'? This cannot be undone.')">
                                            <?= CSRF::field() ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
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
        </div>
    <?php endforeach; ?>
<?php endif; ?>
