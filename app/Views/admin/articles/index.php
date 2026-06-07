<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-0">Articles</h5>
        <small class="text-muted"><?= count($articles) ?> total</small>
    </div>
    <a href="/admin/articles/create" class="btn btn-sm text-white" style="background:#e84c2b;">
        <i class="bi bi-plus-lg me-1"></i> New Article
    </a>
</div>

<?php if (empty($articles)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="bi bi-newspaper fs-2 d-block mb-2"></i>
            No articles yet. <a href="/admin/articles/create">Create the first one</a>.
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $a): ?>
                    <tr>
                        <td>
                            <?php if ($a['cover_image']): ?>
                                <img src="/uploads/articles/<?= htmlspecialchars($a['cover_image']) ?>"
                                     class="rounded me-2" style="width:40px;height:40px;object-fit:cover;" alt="">
                            <?php endif; ?>
                            <span class="fw-semibold"><?= htmlspecialchars($a['title']) ?></span>
                        </td>
                        <td>
                            <?php if ($a['is_published']): ?>
                                <span class="badge bg-success">Published</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= $a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : '—' ?>
                        </td>
                        <td class="text-muted small">
                            <?= date('d M Y', strtotime($a['created_at'])) ?>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="/admin/articles/<?= $a['id'] ?>/edit" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="/admin/articles/<?= $a['id'] ?>/toggle">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm <?= $a['is_published'] ? 'btn-outline-warning' : 'btn-outline-success' ?>"
                                            title="<?= $a['is_published'] ? 'Unpublish' : 'Publish' ?>">
                                        <i class="bi bi-<?= $a['is_published'] ? 'eye-slash' : 'eye' ?>"></i>
                                    </button>
                                </form>
                                <form method="POST" action="/admin/articles/<?= $a['id'] ?>/delete"
                                      onsubmit="return confirm('Delete this article?')">
                                    <?= CSRF::field() ?>
                                    <button class="btn btn-sm btn-outline-danger">
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
<?php endif; ?>
