<style>
.article-hero-img { width:100%; max-height:420px; object-fit:cover; border-radius:0 0 16px 16px; }
.article-body { font-size:1.05rem; line-height:1.85; color:#1e293b; }
.article-body h1,.article-body h2,.article-body h3 { font-weight:700; margin-top:2rem; margin-bottom:.75rem; }
.article-body p { margin-bottom:1.2rem; }
.article-body img { max-width:100%; border-radius:8px; margin:1rem 0; }
.article-body ul,.article-body ol { padding-left:1.5rem; margin-bottom:1.2rem; }
.article-body li { margin-bottom:.4rem; }
.article-body blockquote { border-left:4px solid #e84c2b; padding:.75rem 1.25rem; background:#fff8f7; border-radius:0 8px 8px 0; margin:1.5rem 0; color:#374151; }
.article-body a { color:#e84c2b; }
.related-card { border:none; border-radius:10px; overflow:hidden; box-shadow:0 2px 10px rgba(0,0,0,.07); transition:transform .2s; height:100%; }
.related-card:hover { transform:translateY(-3px); }
.related-cover { height:150px; object-fit:cover; width:100%; background:#f1f5f9; }
.related-cover-ph { height:150px; background:linear-gradient(135deg,#1e293b,#334155); display:flex; align-items:center; justify-content:center; }
</style>

<!-- Cover image -->
<?php if ($article['cover_image']): ?>
<img src="/uploads/articles/<?= htmlspecialchars($article['cover_image']) ?>"
     class="article-hero-img" alt="<?= htmlspecialchars($article['title']) ?>"
     onerror="this.style.display='none'">
<?php endif; ?>

<div style="background:#f8fafc; min-height:60vh; padding-bottom:4rem;">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <!-- Breadcrumb -->
            <nav class="pt-4 pb-2">
                <ol class="breadcrumb small mb-0">
                    <li class="breadcrumb-item"><a href="/" style="color:#e84c2b;">Home</a></li>
                    <li class="breadcrumb-item"><a href="/articles" style="color:#e84c2b;">Articles</a></li>
                    <li class="breadcrumb-item active text-truncate" style="max-width:200px;">
                        <?= htmlspecialchars($article['title']) ?>
                    </li>
                </ol>
            </nav>

            <!-- Article header -->
            <div class="bg-white rounded-3 shadow-sm p-4 p-md-5 mt-3 mb-4">
                <div class="small mb-3" style="color:#94a3b8;">
                    <i class="bi bi-calendar3 me-1"></i>
                    <?= $article['published_at'] ? date('d M Y', strtotime($article['published_at'])) : date('d M Y', strtotime($article['created_at'])) ?>
                </div>
                <h1 class="fw-bold mb-3" style="font-size:clamp(1.4rem,3.5vw,2rem);line-height:1.3;">
                    <?= htmlspecialchars($article['title']) ?>
                </h1>
                <?php if ($article['excerpt']): ?>
                    <p class="lead mb-0" style="color:#475569;font-size:1.05rem;line-height:1.6;">
                        <?= htmlspecialchars($article['excerpt']) ?>
                    </p>
                <?php endif; ?>
            </div>

            <!-- Article body -->
            <div class="bg-white rounded-3 shadow-sm p-4 p-md-5 mb-4">
                <div class="article-body">
                    <?= $article['body'] ?>
                </div>
            </div>

            <!-- Back link -->
            <a href="/articles" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Back to Articles
            </a>

        </div>
    </div>
</div>
</div>

<!-- Related articles -->
<?php if (!empty($related)): ?>
<div style="background:#fff;border-top:1px solid #e2e8f0;padding:3rem 0 4rem;">
    <div class="container">
        <div class="small fw-bold text-uppercase mb-1" style="color:#e84c2b;letter-spacing:.1em;">Keep Reading</div>
        <h2 class="h5 fw-bold mb-4">More Articles</h2>
        <div class="row g-4">
            <?php foreach ($related as $r): ?>
            <div class="col-12 col-sm-6 col-md-4">
                <a href="/articles/<?= htmlspecialchars($r['slug']) ?>" class="text-decoration-none d-block h-100">
                    <div class="related-card">
                        <?php if ($r['cover_image']): ?>
                            <img src="/uploads/articles/<?= htmlspecialchars($r['cover_image']) ?>"
                                 class="related-cover" alt="" loading="lazy"
                                 onerror="this.replaceWith(Object.assign(document.createElement('div'),{className:'related-cover-ph',innerHTML:'<i class=\'bi bi-newspaper\' style=\'font-size:1.5rem;color:#475569\'></i>'}))">
                        <?php else: ?>
                            <div class="related-cover-ph">
                                <i class="bi bi-newspaper" style="font-size:1.5rem;color:#475569;"></i>
                            </div>
                        <?php endif; ?>
                        <div class="p-3">
                            <div class="small mb-1" style="color:#94a3b8;">
                                <?= $r['published_at'] ? date('d M Y', strtotime($r['published_at'])) : '' ?>
                            </div>
                            <div class="fw-semibold text-dark" style="font-size:.9rem;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                <?= htmlspecialchars($r['title']) ?>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php
$_ldBase    = rtrim(env('APP_URL', 'https://ihomestay.my'), '/');
$_ldImage   = !empty($article['cover_image']) ? $_ldBase . '/uploads/articles/' . $article['cover_image'] : null;
$_ldDate    = substr($article['published_at'] ?? date('Y-m-d'), 0, 10);
$_ldExcerpt = $article['excerpt'] ?? mb_substr(strip_tags($article['body'] ?? ''), 0, 200);
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Article",
  "headline": <?= json_encode($article['title']) ?>,
  "description": <?= json_encode($_ldExcerpt) ?>,
  "datePublished": <?= json_encode($_ldDate) ?>,
  "url": <?= json_encode($_ldBase . '/articles/' . $article['slug']) ?>,
  <?php if ($_ldImage): ?>"image": <?= json_encode($_ldImage) ?>,<?php endif; ?>
  "publisher": {
    "@type": "Organization",
    "name": "ihomestay.my",
    "url": <?= json_encode($_ldBase) ?>,
    "logo": {
      "@type": "ImageObject",
      "url": <?= json_encode($_ldBase . '/assets/logo.png') ?>
    }
  }
}
</script>
