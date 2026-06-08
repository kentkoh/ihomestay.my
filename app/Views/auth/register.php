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

                    <div class="d-flex align-items-center gap-2 my-4">
                        <hr class="flex-grow-1 m-0">
                        <span class="text-muted small px-1">or</span>
                        <hr class="flex-grow-1 m-0">
                    </div>

                    <a href="/auth/google" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2">
                        <svg width="18" height="18" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/><path fill="none" d="M0 0h48v48H0z"/></svg>
                        Sign up with Google
                    </a>

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
