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

        header('Location: /owner/dashboard');
        exit;
    }

    public function logout(): void {
        Auth::logout();
        header('Location: /login');
        exit;
    }
}
