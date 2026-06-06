<?php $pageTitle = 'Register'; ob_start(); ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-1 fw-bold">List your homestay</h4>
                    <p class="text-muted mb-4">Create a free account to get started</p>

                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="/register">
                        <?= CSRF::field() ?>

                        <div class="mb-3">
                            <label class="form-label">Full name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control"
                                   value="<?= htmlspecialchars($old['name'] ?? '') ?>" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone / WhatsApp number</label>
                            <input type="text" name="phone" class="form-control" placeholder="e.g. 0123456789"
                                   value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required minlength="8">
                            <div class="form-text">Minimum 8 characters</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm password <span class="text-danger">*</span></label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Create Free Account</button>
                        </div>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0 small">
                        Already have an account? <a href="/login">Login here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require APP_PATH . '/Views/layouts/main.php'; ?>
