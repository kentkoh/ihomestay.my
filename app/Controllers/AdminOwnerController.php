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

    public function editForm(string $id): void {
        Auth::requireAdmin();
        $owner = User::getFullProfile((int) $id);
        if (!$owner || $owner['role'] !== 'owner') {
            $_SESSION['flash']['danger'] = 'Owner not found.';
            header('Location: /admin/owners');
            exit;
        }
        $title = 'Edit Owner — ' . htmlspecialchars($owner['name']);
        ob_start();
        require APP_PATH . '/Views/admin/owners/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function update(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $owner = User::getFullProfile((int) $id);
        if (!$owner || $owner['role'] !== 'owner') {
            header('Location: /admin/owners');
            exit;
        }
        $errors = $this->validate($_POST);
        if ($errors) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header("Location: /admin/owners/$id/edit");
            exit;
        }
        User::adminUpdate((int) $id, [
            'name'                => trim($_POST['name']),
            'email'               => trim($_POST['email']),
            'phone'               => trim($_POST['phone']        ?? ''),
            'whatsapp'            => trim($_POST['whatsapp']     ?? ''),
            'verification_status' => $_POST['verification_status'],
            'company_name'        => trim($_POST['company_name'] ?? ''),
            'about'               => trim($_POST['about']        ?? ''),
            'op_address'          => trim($_POST['op_address']   ?? ''),
            'facebook_url'        => trim($_POST['facebook_url'] ?? ''),
            'instagram_url'       => trim($_POST['instagram_url'] ?? ''),
            'website_url'         => trim($_POST['website_url']  ?? ''),
            'new_password'        => trim($_POST['new_password'] ?? ''),
        ]);
        $_SESSION['flash']['success'] = 'Owner updated successfully.';
        header("Location: /admin/owners/$id/edit");
        exit;
    }

    public function delete(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $owner = User::getFullProfile((int) $id);
        if (!$owner || $owner['role'] !== 'owner') {
            header('Location: /admin/owners');
            exit;
        }
        User::deleteOwner((int) $id);
        $_SESSION['flash']['success'] = 'Owner "' . $owner['name'] . '" and all their listings have been deleted.';
        header('Location: /admin/owners');
        exit;
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

    private function validate(array $post): array {
        $errors = [];
        if (trim($post['name']  ?? '') === '') $errors[] = 'Name is required.';
        if (trim($post['email'] ?? '') === '') $errors[] = 'Email is required.';
        if (!filter_var($post['email'] ?? '', FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email address.';
        $allowed = ['unverified', 'pending_verification', 'verified', 'rejected', 'suspended'];
        if (!in_array($post['verification_status'] ?? '', $allowed, true)) $errors[] = 'Invalid verification status.';
        $pw = trim($post['new_password'] ?? '');
        if ($pw !== '' && strlen($pw) < 8) $errors[] = 'New password must be at least 8 characters.';
        return $errors;
    }
}
