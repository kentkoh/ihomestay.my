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
        $phone    = User::normalizePhone(trim($_POST['phone'] ?? ''));
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

    public function googleRedirect(): void {
        Auth::redirectIfLoggedIn();

        $state = bin2hex(random_bytes(16));
        $_SESSION['google_oauth_state'] = $state;

        $params = http_build_query([
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
            'response_type' => 'code',
            'scope'         => 'openid email profile',
            'state'         => $state,
            'prompt'        => 'select_account',
        ]);

        header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . $params);
        exit;
    }

    public function googleCallback(): void {
        $state = $_GET['state'] ?? '';
        $code  = $_GET['code']  ?? '';

        if (!$state || $state !== ($_SESSION['google_oauth_state'] ?? '')) {
            $_SESSION['auth_error'] = 'Invalid OAuth state. Please try again.';
            header('Location: /login');
            exit;
        }
        unset($_SESSION['google_oauth_state']);

        if (empty($code)) {
            $_SESSION['auth_error'] = 'Google login was cancelled or failed.';
            header('Location: /login');
            exit;
        }

        // Exchange code for access token
        $tokenBody = http_build_query([
            'code'          => $code,
            'client_id'     => env('GOOGLE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_CLIENT_SECRET'),
            'redirect_uri'  => env('GOOGLE_REDIRECT_URI'),
            'grant_type'    => 'authorization_code',
        ]);

        $tokenCtx = stream_context_create([
            'http' => [
                'method'        => 'POST',
                'header'        => "Content-Type: application/x-www-form-urlencoded\r\nContent-Length: " . strlen($tokenBody),
                'content'       => $tokenBody,
                'timeout'       => 15,
                'ignore_errors' => true,
            ],
        ]);

        $tokenResponse = @file_get_contents('https://oauth2.googleapis.com/token', false, $tokenCtx);
        if (!$tokenResponse) {
            $_SESSION['auth_error'] = 'Could not connect to Google. Please try again.';
            header('Location: /login');
            exit;
        }

        $tokenData   = json_decode($tokenResponse, true);
        $accessToken = $tokenData['access_token'] ?? '';
        if (!$accessToken) {
            $_SESSION['auth_error'] = 'Google authentication failed. Please try again.';
            header('Location: /login');
            exit;
        }

        // Fetch user info
        $userCtx = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => 'Authorization: Bearer ' . $accessToken,
                'timeout' => 15,
            ],
        ]);

        $userResponse = @file_get_contents('https://www.googleapis.com/oauth2/v3/userinfo', false, $userCtx);
        if (!$userResponse) {
            $_SESSION['auth_error'] = 'Could not fetch your Google profile. Please try again.';
            header('Location: /login');
            exit;
        }

        $googleUser = json_decode($userResponse, true);
        $googleId   = $googleUser['sub']   ?? '';
        $email      = $googleUser['email'] ?? '';
        $name       = $googleUser['name']  ?? '';

        if (!$googleId || !$email) {
            $_SESSION['auth_error'] = 'Google did not return a valid account. Please try again.';
            header('Location: /login');
            exit;
        }

        $db = Database::get();

        // 1. Find by google_id
        $stmt = $db->prepare("SELECT * FROM users WHERE google_id = ? LIMIT 1");
        $stmt->execute([$googleId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. Find by email and link google_id
        if (!$user) {
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $db->prepare("UPDATE users SET google_id = ?, updated_at = NOW() WHERE id = ?")
                   ->execute([$googleId, $user['id']]);
                $user['google_id'] = $googleId;
            }
        }

        // 3. New user — create owner account
        if (!$user) {
            $userId = $this->userModel->create([
                'name'      => $name,
                'email'     => $email,
                'password'  => bin2hex(random_bytes(32)), // unusable random password
                'role'      => 'owner',
                'google_id' => $googleId,
            ]);
            $this->userModel->createOwnerProfile($userId);
            $db->prepare("UPDATE users SET google_id = ?, updated_at = NOW() WHERE id = ?")
               ->execute([$googleId, $userId]);
            $user = $this->userModel->findById($userId);
            Mailer::welcome($email, $name);
        }

        if (($user['status'] ?? '') !== 'active') {
            $_SESSION['auth_error'] = 'Your account has been suspended. Please contact support.';
            header('Location: /login');
            exit;
        }

        Auth::login($user);
        Auth::redirectToDashboard();
    }
}
