<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4" style="font-size:4rem;">😕</div>
            <h2 class="fw-bold mb-2" style="color:#0f172a;">Payment Not Completed</h2>
            <p class="text-muted mb-4">
                Your payment was not completed or was cancelled. Your listing has not been charged.
                You can try again at any time.
            </p>

            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <?php if ($listing): ?>
                    <a href="/feature/<?= $listing['id'] ?>" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i>Try Again
                    </a>
                <?php endif; ?>
                <a href="/owner/listings" class="btn btn-outline-secondary">My Listings</a>
                <a href="/contact" class="btn btn-outline-secondary">
                    <i class="bi bi-whatsapp me-1"></i>Get Help
                </a>
            </div>

            <p class="text-muted small mt-4">
                If you were charged but your listing was not featured, please contact us immediately at
                <a href="mailto:admin@ihomestay.my" style="color:#e84c2b;">admin@ihomestay.my</a>
            </p>
        </div>
    </div>
</div>
