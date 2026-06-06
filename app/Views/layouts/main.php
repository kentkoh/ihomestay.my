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
        <a class="navbar-brand" href="/">ihomestay.my</a>
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
                        <li class="nav-item"><a class="nav-link" href="/owner/listings">My Listings</a></li>
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

<footer class="bg-white border-top mt-5 py-4">
    <div class="container text-center text-muted small">
        &copy; <?= date('Y') ?> ihomestay.my — Malaysia Homestay Directory
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
