<?php

class AdminOwnerController {

    public function index(): void {
        Auth::requireAdmin();
        $owners = User::allOwners();
        $title  = 'Owners';
        ob_start();
        require APP_PATH . '/Views/admin/owners/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function verify(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        User::setVerification((int) $id, 'verified');
        $_SESSION['flash']['success'] = 'Owner marked as verified.';
        header('Location: /admin/owners');
        exit;
    }

    public function unverify(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        User::setVerification((int) $id, 'unverified');
        $_SESSION['flash']['success'] = 'Owner verification removed.';
        header('Location: /admin/owners');
        exit;
    }
}
