<?php $pageTitle = 'Owner Dashboard'; ob_start(); ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">My Dashboard</h4>
        <span class="badge bg-secondary">Free Owner</span>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-primary">0</div>
                <div class="text-muted small">My Listings</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-warning">0</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-success">0</div>
                <div class="text-muted small">Published</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm text-center p-3">
                <div class="fs-2 fw-bold text-info">0</div>
                <div class="text-muted small">Total Views</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <h6 class="fw-bold">Free Account — 0 of 3 listings used</h6>
            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar bg-primary" style="width: 0%"></div>
            </div>
            <p class="text-muted small mb-2">Free owners can submit up to 3 listings.</p>
            <a href="#" class="btn btn-primary btn-sm">+ Add New Listing</a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-1">Upgrade to Verified Owner</h6>
            <p class="text-muted small mb-2">Get a Verified Owner badge, unlimited listings, and higher search ranking.</p>
            <a href="#" class="btn btn-outline-primary btn-sm">Learn More</a>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/main.php'; ?>
