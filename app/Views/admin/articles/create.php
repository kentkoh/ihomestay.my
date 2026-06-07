<div class="mb-3">
    <a href="/admin/articles" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Articles
    </a>
</div>
<h5 class="fw-bold mb-4">New Article</h5>

<form method="POST" action="/admin/articles/store" enctype="multipart/form-data">
    <?= CSRF::field() ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Article Details</div>
        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($old['title'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Excerpt <span class="text-muted fw-normal">(short summary shown on listing cards)</span></label>
                <textarea name="excerpt" class="form-control" rows="2"><?= htmlspecialchars($old['excerpt'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
                <textarea name="body" class="form-control" rows="14" required><?= htmlspecialchars($old['body'] ?? '') ?></textarea>
                <div class="form-text">Plain text or basic HTML is supported.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Cover Image</label>
                <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">JPG, PNG, WebP. Max 5 MB.</div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                       <?= !empty($old['is_published']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_published">Publish immediately</label>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn px-4 text-white" style="background:#e84c2b;">Save Article</button>
        <a href="/admin/articles" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
