<div class="mb-3">
    <a href="/admin/articles" class="text-decoration-none text-muted small">
        <i class="bi bi-arrow-left me-1"></i> Back to Articles
    </a>
</div>
<h5 class="fw-bold mb-4">Edit Article</h5>

<form method="POST" action="/admin/articles/<?= $article['id'] ?>/update" enctype="multipart/form-data">
    <?= CSRF::field() ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Article Details</div>
        <div class="card-body p-4">

            <div class="mb-3">
                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control"
                       value="<?= htmlspecialchars($article['title']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Excerpt <span class="text-muted fw-normal">(short summary shown on listing cards)</span></label>
                <textarea name="excerpt" class="form-control" rows="2"><?= htmlspecialchars($article['excerpt'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Body <span class="text-danger">*</span></label>
                <textarea name="body" class="form-control" rows="14" required><?= htmlspecialchars($article['body']) ?></textarea>
                <div class="form-text">Plain text or basic HTML is supported.</div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Cover Image</label>
                <?php if ($article['cover_image']): ?>
                    <div class="mb-2">
                        <img src="/uploads/articles/<?= htmlspecialchars($article['cover_image']) ?>"
                             class="rounded" style="height:100px;object-fit:cover;" alt="Current cover">
                        <div class="form-text">Upload a new image below to replace it.</div>
                    </div>
                <?php endif; ?>
                <input type="file" name="cover_image" class="form-control" accept="image/jpeg,image/png,image/webp">
                <div class="form-text">JPG, PNG, WebP. Max 5 MB.</div>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_published" id="is_published"
                       <?= $article['is_published'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_published">Published</label>
            </div>
        </div>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn px-4 text-white" style="background:#e84c2b;">Save Changes</button>
        <a href="/admin/articles" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
