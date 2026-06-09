<?php
$verificationOptions = [
    'unverified'           => 'Unverified',
    'pending_verification' => 'Pending Verification',
    'verified'             => 'Verified',
    'rejected'             => 'Rejected',
    'suspended'            => 'Suspended',
];
?>

<?php if (!empty($_SESSION['flash'])): ?>
    <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
        <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
    <?php endforeach; ?>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="/admin/owners" class="btn btn-sm btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Back
    </a>
    <h5 class="fw-bold mb-0">Edit Owner</h5>
</div>

<form method="POST" action="/admin/owners/<?= $owner['id'] ?>/update">
    <?= CSRF::field() ?>

    <div class="row g-4">

        <!-- Account details -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Account Details</div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($owner['name']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control"
                               value="<?= htmlspecialchars($owner['email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" class="form-control"
                               value="<?= htmlspecialchars($owner['phone'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">WhatsApp</label>
                        <input type="text" name="whatsapp" class="form-control"
                               value="<?= htmlspecialchars($owner['whatsapp'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Verification Status</label>
                        <select name="verification_status" class="form-select">
                            <?php foreach ($verificationOptions as $val => $label): ?>
                                <option value="<?= $val ?>" <?= $owner['verification_status'] === $val ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Reset Password
                            <span class="text-muted fw-normal small">(leave blank to keep current)</span>
                        </label>
                        <input type="password" name="new_password" class="form-control"
                               placeholder="Min 8 characters" autocomplete="new-password">
                    </div>
                </div>
            </div>
        </div>

        <!-- Owner profile -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Owner Profile</div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Company / Business Name</label>
                        <input type="text" name="company_name" class="form-control"
                               value="<?= htmlspecialchars($owner['company_name'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">About</label>
                        <textarea name="about" class="form-control" rows="4"><?= htmlspecialchars($owner['about'] ?? '') ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Address</label>
                        <input type="text" name="op_address" class="form-control"
                               value="<?= htmlspecialchars($owner['address'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Facebook URL</label>
                        <input type="url" name="facebook_url" class="form-control"
                               value="<?= htmlspecialchars($owner['facebook_url'] ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Instagram URL</label>
                        <input type="url" name="instagram_url" class="form-control"
                               value="<?= htmlspecialchars($owner['instagram_url'] ?? '') ?>">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Website URL</label>
                        <input type="url" name="website_url" class="form-control"
                               value="<?= htmlspecialchars($owner['website_url'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-lg me-1"></i> Save Changes
        </button>
        <a href="/admin/owners" class="btn btn-outline-secondary">Cancel</a>

        <!-- Delete — separate form to avoid accidental submission -->
        <div class="ms-auto">
            <form method="POST" action="/admin/owners/<?= $owner['id'] ?>/delete"
                  onsubmit="return confirm('Delete owner &quot;<?= addslashes(htmlspecialchars($owner['name'])) ?>&quot; and ALL their listings? This cannot be undone.')">
                <?= CSRF::field() ?>
                <button type="submit" class="btn btn-danger">
                    <i class="bi bi-trash me-1"></i> Delete Owner
                </button>
            </form>
        </div>
    </div>

</form>

<!-- Meta info -->
<div class="mt-4 text-muted small">
    Joined: <?= date('d M Y H:i', strtotime($owner['created_at'])) ?>
    &nbsp;·&nbsp; ID: <?= $owner['id'] ?>
    <?php if ($owner['google_id'] ?? null): ?>
        &nbsp;·&nbsp; <i class="bi bi-google"></i> Google linked
    <?php endif; ?>
</div>
