<?php $pageTitle = 'Reset Password'; ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-1 fw-bold">Set a new password</h4>
                    <p class="text-muted mb-4" style="font-size:.93rem;">
                        Choose a strong password for your account.
                    </p>

                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="/reset-password">
                        <?= CSRF::field() ?>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control" required
                                   minlength="8" autofocus placeholder="Minimum 8 characters">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirm" class="form-control" required
                                   minlength="8" placeholder="Re-enter your password">
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Reset Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/main.php'; ?>
