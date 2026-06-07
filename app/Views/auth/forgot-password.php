<?php $pageTitle = 'Forgot Password'; ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="mb-4">
                        <a href="/login" class="text-muted small" style="text-decoration:none;">
                            <i class="bi bi-arrow-left me-1"></i>Back to login
                        </a>
                    </div>

                    <h4 class="mb-1 fw-bold">Forgot your password?</h4>
                    <p class="text-muted mb-4" style="font-size:.93rem;">
                        Enter your registered email address and we'll send you a link to reset your password.
                    </p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="/forgot-password">
                        <?= CSRF::field() ?>

                        <div class="mb-3">
                            <label class="form-label">Email address</label>
                            <input type="email" name="email" class="form-control" required autofocus
                                   placeholder="your@email.com">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Send Reset Link</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/main.php'; ?>
