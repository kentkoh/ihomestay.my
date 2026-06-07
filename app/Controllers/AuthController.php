<?php

class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin(): void {
        Auth::redirectIfLoggedIn();
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);
        require APP_PATH . '/Views/auth/login.php';
    }

    public function handleLogin(): void {
        CSRF::verify();

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['auth_error'] = 'Please enter your email and password.';
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['auth_error'] = 'Incorrect email or password.';
            header('Location: /login');
            exit;
        }

        if ($user['status'] !== 'active') {
            $_SESSION['auth_error'] = 'Your account has been suspended. Please contact support.';
            header('Location: /login');
            exit;
        }

        Auth::login($user);

        if ($user['password_reset_required']) {
            $_SESSION['flash']['warning'] = 'Welcome back! Your account has been migrated — please set a new password to continue.';
            header('Location: /owner/profile');
            exit;
        }

        Auth::redirectToDashboard();
    }

    public function showRegister(): void {
        Auth::redirectIfLoggedIn();
        $error  = $_SESSION['auth_error'] ?? null;
        $old    = $_SESSION['auth_old'] ?? [];
        unset($_SESSION['auth_error'], $_SESSION['auth_old']);
        require APP_PATH . '/Views/auth/register.php';
    }

    public function handleRegister(): void {
        CSRF::verify();

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        $_SESSION['auth_old'] = ['name' => $name, 'email' => $email, 'phone' => $phone];

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['auth_error'] = 'Please fill in all required fields.';
            header('Location: /register');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Please enter a valid email address.';
            header('Location: /register');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['auth_error'] = 'Password must be at least 8 characters.';
            header('Location: /register');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            header('Location: /register');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['auth_error'] = 'This email is already registered. Please login.';
            header('Location: /register');
            exit;
        }

        $userId = $this->userModel->create([
            'name'     => $name,
            'email'    => $email,
            'password' => $password,
            'phone'    => $phone,
            'whatsapp' => $phone,
            'role'     => 'owner',
        ]);

        $this->userModel->createOwnerProfile($userId);

        $user = $this->userModel->findById($userId);
        Auth::login($user);

        Mailer::welcome($email, $name);

        header('Location: /owner/dashboard');
        exit;
    }

    public function showForgotPassword(): void {
        Auth::redirectIfLoggedIn();
        $error   = $_SESSION['auth_error'] ?? null;
        $success = $_SESSION['auth_success'] ?? null;
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);
        require APP_PATH . '/Views/auth/forgot-password.php';
    }

    public function handleForgotPassword(): void {
        CSRF::verify();

        $email = trim(strtolower($_POST['email'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Please enter a valid email address.';
            header('Location: /forgot-password');
            exit;
        }

        $db   = Database::get();
        $user = $this->userModel->findByEmail($email);

        if ($user) {
            // Delete any existing tokens for this email
            $db->prepare("DELETE FROM password_resets WHERE email = ?")
               ->execute([$email]);

            // Generate token: store SHA256 hash, send raw token in email
            $rawToken    = bin2hex(random_bytes(32));
            $hashedToken = hash('sha256', $rawToken);
            $expiresAt   = date('Y-m-d H:i:s', strtotime('+1 hour'));

            $db->prepare("
                INSERT INTO password_resets (email, token, expires_at, created_at)
                VALUES (?, ?, ?, NOW())
            ")->execute([$email, $hashedToken, $expiresAt]);

            $baseUrl  = rtrim(env('APP_URL', 'https://new.ihomestay.my'), '/');
            $resetUrl = $baseUrl . '/reset-password?token=' . urlencode($rawToken) . '&email=' . urlencode($email);

            Mailer::passwordReset($email, $user['name'], $resetUrl);
        }

        // Always show success to prevent email enumeration
        $_SESSION['auth_success'] = 'If that email is registered, you will receive a password reset link shortly.';
        header('Location: /forgot-password');
        exit;
    }

    public function showResetPassword(): void {
        Auth::redirectIfLoggedIn();
        $token = trim($_GET['token'] ?? '');
        $email = trim($_GET['email'] ?? '');
        $error = $_SESSION['auth_error'] ?? null;
        unset($_SESSION['auth_error']);

        if (!$token || !$email) {
            $_SESSION['auth_error'] = 'Invalid or missing reset link.';
            header('Location: /forgot-password');
            exit;
        }

        // Validate token
        $db          = Database::get();
        $hashedToken = hash('sha256', $token);
        $stmt        = $db->prepare("
            SELECT * FROM password_resets
            WHERE email = ? AND token = ? AND expires_at > NOW()
            LIMIT 1
        ");
        $stmt->execute([$email, $hashedToken]);
        $record = $stmt->fetch();

        if (!$record) {
            $_SESSION['auth_error'] = 'This reset link is invalid or has expired. Please request a new one.';
            header('Location: /forgot-password');
            exit;
        }

        require APP_PATH . '/Views/auth/reset-password.php';
    }

    public function handleResetPassword(): void {
        CSRF::verify();

        $token    = trim($_POST['token'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['password_confirm'] ?? '';

        if (!$token || !$email) {
            $_SESSION['auth_error'] = 'Invalid request.';
            header('Location: /forgot-password');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['auth_error'] = 'Password must be at least 8 characters.';
            header('Location: /reset-password?token=' . urlencode($token) . '&email=' . urlencode($email));
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            header('Location: /reset-password?token=' . urlencode($token) . '&email=' . urlencode($email));
            exit;
        }

        $db          = Database::get();
        $hashedToken = hash('sha256', $token);
        $stmt        = $db->prepare("
            SELECT * FROM password_resets
            WHERE email = ? AND token = ? AND expires_at > NOW()
            LIMIT 1
        ");
        $stmt->execute([$email, $hashedToken]);
        $record = $stmt->fetch();

        if (!$record) {
            $_SESSION['auth_error'] = 'This reset link is invalid or has expired. Please request a new one.';
            header('Location: /forgot-password');
            exit;
        }

        // Update password and clear reset flag
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $db->prepare("
            UPDATE users
            SET password = ?, password_reset_required = 0, updated_at = NOW()
            WHERE email = ?
        ")->execute([$hashed, $email]);

        // Delete used token
        $db->prepare("DELETE FROM password_resets WHERE email = ?")
           ->execute([$email]);

        $_SESSION['flash']['success'] = 'Password reset successfully. Please log in with your new password.';
        header('Location: /login');
        exit;
    }

    public function logout(): void {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}
