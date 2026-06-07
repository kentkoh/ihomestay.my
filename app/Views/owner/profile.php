<div class="container py-4" style="max-width:720px;">
    <h5 class="fw-bold mb-4">My Profile</h5>

    <?php if (!empty($_SESSION['flash'])): ?>
        <?php foreach ($_SESSION['flash'] as $type => $msg): ?>
            <div class="alert alert-<?= $type ?>"><?= $msg ?></div>
        <?php endforeach; ?>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <form method="POST" action="/owner/profile/update" enctype="multipart/form-data">
        <?= CSRF::field() ?>

        <!-- Profile Photo -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Profile Photo</div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4">
                    <?php
                    $photoPath = !empty($profile['profile_photo'])
                        ? '/uploads/profiles/' . $profile['id'] . '/' . $profile['profile_photo']
                        : null;
                    ?>
                    <div class="flex-shrink-0">
                        <?php if ($photoPath): ?>
                            <img src="<?= htmlspecialchars($photoPath) ?>" id="photoPreview"
                                 style="width:90px;height:90px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;" alt="Profile photo">
                        <?php else: ?>
                            <div id="photoPreview" style="width:90px;height:90px;border-radius:50%;background:#f1f5f9;border:3px solid #e2e8f0;display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-person-fill" style="font-size:2.5rem;color:#94a3b8;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <input type="file" name="profile_photo" id="profile_photo"
                               class="form-control form-control-sm" accept="image/jpeg,image/png,image/webp"
                               style="max-width:280px;">
                        <div class="form-text">JPG, PNG or WebP. Max 2 MB.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Basic Information</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                               value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required maxlength="191">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Company / Business Name</label>
                        <input type="text" name="company_name" class="form-control"
                               value="<?= htmlspecialchars($profile['company_name'] ?? '') ?>" maxlength="191"
                               placeholder="Optional">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">About / Bio</label>
                        <textarea name="about" class="form-control" rows="4"
                                  placeholder="Tell guests about yourself and your properties..."><?= htmlspecialchars($profile['about'] ?? '') ?></textarea>
                        <div class="form-text">This appears on your listing pages.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Contact Details</div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phone Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="phone" class="form-control"
                                   value="<?= htmlspecialchars($profile['phone'] ?? '') ?>"
                                   placeholder="e.g. 0123456789" maxlength="50">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">WhatsApp Number</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-whatsapp text-success"></i></span>
                            <input type="text" name="whatsapp" class="form-control"
                                   value="<?= htmlspecialchars($profile['whatsapp'] ?? '') ?>"
                                   placeholder="e.g. 60123456789" maxlength="50">
                        </div>
                        <div class="form-text">Include country code (e.g. 601x). Automatically applied to all your listings.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" class="form-control bg-light"
                               value="<?= htmlspecialchars($profile['email'] ?? '') ?>" readonly>
                        <div class="form-text">Email cannot be changed. Contact support if needed.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold">Address</div>
            <div class="card-body p-4">
                <textarea name="address" class="form-control" rows="3"
                          placeholder="Your business or home address (optional, not shown publicly)"><?= htmlspecialchars($profile['address'] ?? '') ?></textarea>
                <div class="form-text">For admin reference only — not displayed to guests.</div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn px-4" style="background:#e84c2b;color:#fff;">
                <i class="bi bi-check-lg me-1"></i>Save Profile
            </button>
            <a href="/owner/dashboard" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>

    <!-- Change Password -->
    <div class="card border-0 shadow-sm mt-5">
        <div class="card-header bg-white fw-semibold">Change Password</div>
        <div class="card-body p-4">
            <form method="POST" action="/owner/profile/change-password">
                <?= CSRF::field() ?>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="8">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="8">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-lock me-1"></i>Update Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_photo').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('photoPreview');
        if (preview.tagName === 'IMG') {
            preview.src = e.target.result;
        } else {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.id  = 'photoPreview';
            img.style.cssText = 'width:90px;height:90px;object-fit:cover;border-radius:50%;border:3px solid #e2e8f0;';
            preview.replaceWith(img);
        }
    };
    reader.readAsDataURL(file);
});
</script>
