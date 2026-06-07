<style>
.articles-hero { background:linear-gradient(135deg,#0f1923 60%,#1e3a2f); padding:3.5rem 0 2.5rem; }
.article-card { border:none; border-radius:12px; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.07); transition:transform .2s,box-shadow .2s; height:100%; }
.article-card:hover { transform:translateY(-4px); box-shadow:0 10px 28px rgba(0,0,0,.12); }
.article-cover { height:200px; object-fit:cover; width:100%; background:#f1f5f9; }
.article-cover-ph { height:200px; background:linear-gradient(135deg,#1e293b,#334155); display:flex; align-items:center; justify-content:center; }
.article-date { font-size:.75rem; color:#94a3b8; font-weight:500; }
.article-title { font-size:1rem; font-weight:700; line-height:1.4; color:#0f1923; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
.article-excerpt { font-size:.85rem; color:#64748b; line-height:1.6; display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
.read-more { font-size:.82rem; font-weight:600; color:#e84c2b; text-decoration:none; }
.read-more:hover { color:#c73d22; }
.pagination .page-link { color:#e84c2b; border-color:#e2e8f0; }
.pagination .page-item.active .page-link { background:#e84c2b; border-color:#e84c2b; color:#fff; }
</style>

<!-- Hero -->
<div class="articles-hero">
    <div class="container">
        <div class="small mb-2" style="color:#6ee7b7;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">
            <i class="bi bi-newspaper me-1"></i> ihomestay.my
        </div>
        <h1 class="fw-bold mb-1" style="color:#fff;font-size:clamp(1.6rem,4vw,2.4rem);">Articles &amp; Tips</h1>
        <p class="mb-0" style="color:#94a3b8;max-width:480px;">Homestay travel guides, owner tips, and destination inspiration across Malaysia.</p>
    </div>
</div>

<div style="background:#f8fafc;min-height:60vh;padding:3rem 0 4rem;">
    <div class="container">

        <?php if (empty($articles)): ?>
            <div class="text-center py-5">
                <i class="bi bi-newspaper" style="font-size:3rem;color:#cbd5e1;"></i>
                <p class="mt-3 text-muted">No articles published yet. Check back soon.</p>
            </div>
        <?php else: ?>

        <div class="row g-4">
            <?php foreach ($articles as $a): ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="/articles/<?= htmlspecialchars($a['slug']) ?>" class="text-decoration-none d-block h-100">
                    <div class="article-card">
                        <?php if ($a['cover_image']): ?>
                            <img src="/uploads/articles/<?= htmlspecialchars($a['cover_image']) ?>"
                                 class="article-cover" alt="<?= htmlspecialchars($a['title']) ?>"
                                 loading="lazy"
                                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'article-cover-ph',innerHTML:'<i class=\'bi bi-image\' style=\'font-size:2rem;color:#475569\'></i>'}))">
                        <?php else: ?>
                            <div class="article-cover-ph">
                                <i class="bi bi-newspaper" style="font-size:2rem;color:#475569;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <div class="article-date mb-2">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?= $a['published_at'] ? date('d M Y', strtotime($a['published_at'])) : date('d M Y', strtotime($a['created_at'])) ?>
                            </div>
                            <div class="article-title mb-2"><?= htmlspecialchars($a['title']) ?></div>
                            <?php if ($a['excerpt']): ?>
                                <div class="article-excerpt mb-3"><?= htmlspecialchars($a['excerpt']) ?></div>
                            <?php endif; ?>
                            <span class="read-more">Read more <i class="bi bi-arrow-right"></i></span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <nav class="mt-5 d-flex justify-content-center">
            <ul class="pagination">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="/articles?page=<?= $page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                        <a class="page-link" href="/articles?page=<?= $p ?>"><?= $p ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="/articles?page=<?= $page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>

        <?php endif; ?>
    </div>
</div>
