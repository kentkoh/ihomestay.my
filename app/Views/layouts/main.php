<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'ihomestay.my') ?> | ihomestay.my</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; color: #e84c2b !important; }
        .btn-primary { background-color: #e84c2b; border-color: #e84c2b; }
        .btn-primary:hover { background-color: #c73d22; border-color: #c73d22; }
        .text-primary { color: #e84c2b !important; }
        a { color: #e84c2b; }
        a:hover { color: #c73d22; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/">
            <img src="/assets/logo.png" alt="ihomestay.my" style="height:52px;width:auto;object-fit:contain;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <?php if (Auth::check()): ?>
                    <li class="nav-item">
                        <span class="nav-link text-muted">Hi, <?= htmlspecialchars(Auth::user()['name']) ?></span>
                    </li>
                    <?php if (Auth::isAdmin()): ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/dashboard">Admin Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/owner/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/owner/profile">My Profile</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="/logout">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/login">Login</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white px-3 ms-2" href="/register">List Your Homestay</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main>
    <?= $content ?? '' ?>
</main>

<footer class="site-footer py-5 mt-0" style="background:#0f1923;color:#64748b;">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="fw-bold mb-2" style="color:#fff;font-size:1.1rem;">ihomestay.my</div>
                <div class="small" style="color:#64748b;max-width:260px;line-height:1.7;">
                    Malaysia's homestay directory. Book direct from owners — no platform fees, no middleman.
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold small mb-2" style="color:#94a3b8;">Guests</div>
                <div class="d-flex flex-column gap-1">
                    <a href="/search" class="small" style="color:#64748b;text-decoration:none;">Search Homestays</a>
                    <a href="/articles" class="small" style="color:#64748b;text-decoration:none;">Articles &amp; Tips</a>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold small mb-2" style="color:#94a3b8;">Owners</div>
                <div class="d-flex flex-column gap-1">
                    <a href="/register" class="small" style="color:#64748b;text-decoration:none;">List for Free</a>
                    <a href="/login" class="small" style="color:#64748b;text-decoration:none;">Login</a>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="fw-semibold small mb-2" style="color:#94a3b8;">Company</div>
                <div class="d-flex flex-column gap-1">
                    <a href="/about" class="small" style="color:#64748b;text-decoration:none;">About Us</a>
                    <a href="/contact" class="small" style="color:#64748b;text-decoration:none;">Contact Us</a>
                    <a href="/terms" class="small" style="color:#64748b;text-decoration:none;">Terms of Use</a>
                </div>
            </div>
        </div>
        <div class="border-top pt-3" style="border-color:#1e293b!important;">
            <div class="small text-center" style="color:#475569;">
                &copy; <?= date('Y') ?> ihomestay.my — Malaysia Homestay Directory
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?= $stickyBar ?? '' ?>
</body>
</html>
