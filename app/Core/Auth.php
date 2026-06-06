<?php

class Auth {
    public static function login(array $user): void {
        session_regenerate_id(true);
        $_SESSION['user_id']   = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_email'] = $user['email'];
    }

    public static function logout(): void {
        $_SESSION = [];
        session_destroy();
    }

    public static function check(): bool {
        return isset($_SESSION['user_id']);
    }

    public static function user(): ?array {
        if (!self::check()) return null;
        return [
            'id'    => $_SESSION['user_id'],
            'name'  => $_SESSION['user_name'],
            'role'  => $_SESSION['user_role'],
            'email' => $_SESSION['user_email'],
        ];
    }

    public static function id(): ?int {
        return $_SESSION['user_id'] ?? null;
    }

    public static function role(): ?string {
        return $_SESSION['user_role'] ?? null;
    }

    public static function isAdmin(): bool {
        return self::role() === 'admin';
    }

    public static function isOwner(): bool {
        return self::role() === 'owner';
    }

    public static function requireLogin(): void {
        if (!self::check()) {
            header('Location: /login');
            exit;
        }
    }

    public static function requireAdmin(): void {
        self::requireLogin();
        if (!self::isAdmin()) {
            header('Location: /owner/dashboard');
            exit;
        }
    }

    public static function requireOwner(): void {
        self::requireLogin();
        if (self::role() !== 'owner' && !self::isAdmin()) {
            header('Location: /login');
            exit;
        }
    }

    public static function redirectIfLoggedIn(): void {
        if (self::check()) {
            self::redirectToDashboard();
        }
    }

    public static function redirectToDashboard(): void {
        $role = self::role();
        if ($role === 'admin') {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /owner/dashboard');
        }
        exit;
    }
}
