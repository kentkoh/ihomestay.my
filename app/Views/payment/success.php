<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4" style="font-size:4rem;">🎉</div>
            <h2 class="fw-bold mb-2" style="color:#0f172a;">Payment Successful!</h2>
            <p class="text-muted mb-4">
                Your listing is now <strong>featured</strong> and appearing at the top of search results.
                <?php if ($payment): ?>
                    It will stay featured for <strong><?= $payment['duration_days'] ?> days</strong>.
                <?php endif; ?>
            </p>

            <?php if ($listing): ?>
                <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <?php if ($listing['primary_image'] ?? null): ?>
                                <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($listing['primary_image']) ?>"
                                     style="width:64px;height:64px;border-radius:12px;object-fit:cover;" alt="">
                            <?php endif; ?>
                            <div class="text-start">
                                <div class="fw-semibold" style="color:#0f172a;"><?= htmlspecialchars($listing['title']) ?></div>
                                <span class="badge mt-1" style="background:#fef2f0;color:#e84c2b;">
                                    <i class="bi bi-star-fill me-1"></i>Featured
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-3 justify-content-center">
                    <a href="/listing/<?= htmlspecialchars($listing['slug']) ?>" class="btn btn-primary">
                        <i class="bi bi-eye me-1"></i>View Listing
                    </a>
                    <a href="/owner/listings" class="btn btn-outline-secondary">
                        My Listings
                    </a>
                </div>
            <?php else: ?>
                <a href="/owner/listings" class="btn btn-primary">Go to My Listings</a>
            <?php endif; ?>
        </div>
    </div>
</div>
