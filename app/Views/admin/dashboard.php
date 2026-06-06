<?php $title = 'Dashboard'; ob_start(); ?>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-primary">0</div>
            <div class="text-muted small">Total Listings</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-warning">0</div>
            <div class="text-muted small">Pending Approval</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold text-success">0</div>
            <div class="text-muted small">Total Owners</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <div class="fs-2 fw-bold" style="color:#e84c2b;">0</div>
            <div class="text-muted small">Verified Owners</div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h6 class="fw-bold mb-3">Quick Links</h6>
        <div class="d-flex flex-wrap gap-2">
            <a href="/admin/facilities" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-grid-3x3-gap-fill me-1"></i> Manage Facilities
            </a>
            <a href="#" class="btn btn-outline-secondary btn-sm disabled">Manage Listings</a>
            <a href="#" class="btn btn-outline-secondary btn-sm disabled">Manage Owners</a>
            <a href="#" class="btn btn-outline-secondary btn-sm disabled">Manage Articles</a>
            <a href="#" class="btn btn-outline-secondary btn-sm disabled">Manage Ads</a>
        </div>
        <p class="text-muted small mt-3 mb-0">Disabled sections will be available in upcoming stages.</p>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/admin.php'; ?>
