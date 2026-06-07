<?php

class OwnerController {
    public function dashboard(): void {
        Auth::requireOwner();
        $user      = Auth::user();
        $listings  = Listing::byOwner($user['id']);
        $total     = count($listings);
        $published = count(array_filter($listings, fn($l) => $l['status'] === 'published'));
        $pending   = count(array_filter($listings, fn($l) => $l['status'] === 'pending'));
        $featured  = count(array_filter($listings, fn($l) =>
            $l['is_featured'] && (!$l['featured_until'] || strtotime($l['featured_until']) > time())
        ));
        $isPro        = $user['plan_type'] !== 'free';
        $isVerified   = in_array($user['verification_status'] ?? '', ['verified']);
        $profile      = User::getFullProfile($user['id']);
        $pageTitle    = 'My Dashboard';

        ob_start();
        require APP_PATH . '/Views/owner/dashboard.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function profile(): void {
        Auth::requireOwner();
        $profile   = User::getFullProfile(Auth::id());
        $pageTitle = 'My Profile';
        ob_start();
        require APP_PATH . '/Views/owner/profile.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function updateProfile(): void {
        Auth::requireOwner();
        CSRF::verify();

        $userId = Auth::id();
        $name   = trim($_POST['name'] ?? '');
        $wa     = User::normalizePhone(trim($_POST['whatsapp'] ?? ''));
        $errors = [];

        if ($name === '') $errors[] = 'Name is required.';

        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header('Location: /owner/profile');
            exit;
        }

        // Handle profile photo upload
        $photoFilename = null;
        if (!empty($_FILES['profile_photo']['name'])) {
            $file     = $_FILES['profile_photo'];
            $allowed  = ['image/jpeg', 'image/png', 'image/webp'];
            $maxBytes = 2 * 1024 * 1024;

            if (!in_array($file['type'], $allowed, true)) {
                $errors[] = 'Photo must be JPG, PNG, or WebP.';
            } elseif ($file['size'] > $maxBytes) {
                $errors[] = 'Photo must be under 2 MB.';
            } else {
                $ext           = pathinfo($file['name'], PATHINFO_EXTENSION);
                $photoFilename = uniqid('photo_') . '.' . strtolower($ext);
                $dir           = UPLOAD_PATH . '/profiles/' . $userId;
                if (!is_dir($dir)) mkdir($dir, 0755, true);
                move_uploaded_file($file['tmp_name'], $dir . '/' . $photoFilename);
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header('Location: /owner/profile');
            exit;
        }

        // Update users table
        $oldWa = Auth::user()['whatsapp'] ?? '';
        User::updateUserFields($userId, [
            'name'     => $name,
            'phone'    => trim($_POST['phone'] ?? ''),
            'whatsapp' => $wa,
        ]);

        // Sync whatsapp to all listings if it changed
        if ($wa !== $oldWa) {
            Listing::syncWhatsappForOwner($userId, $wa);
        }

        // Update owner_profiles table
        User::updateOwnerProfile($userId, [
            'company_name'  => trim($_POST['company_name'] ?? '') ?: null,
            'about'         => trim($_POST['about'] ?? '')        ?: null,
            'address'       => trim($_POST['address'] ?? '')      ?: null,
            'profile_photo' => $photoFilename,
        ]);

        // Refresh session
        Auth::refreshSession(['name' => $name, 'whatsapp' => $wa]);

        $_SESSION['flash']['success'] = 'Profile updated successfully.';
        header('Location: /owner/profile');
        exit;
    }

    public function changePassword(): void {
        Auth::requireOwner();
        CSRF::verify();

        $userId  = Auth::id();
        $userObj = new User();
        $user    = $userObj->findById($userId);

        $current = $_POST['current_password'] ?? '';
        $new     = $_POST['new_password']     ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$userObj->verifyPassword($current, $user['password'])) {
            $_SESSION['flash']['danger'] = 'Current password is incorrect.';
            header('Location: /owner/profile');
            exit;
        }
        if (strlen($new) < 8) {
            $_SESSION['flash']['danger'] = 'New password must be at least 8 characters.';
            header('Location: /owner/profile');
            exit;
        }
        if ($new !== $confirm) {
            $_SESSION['flash']['danger'] = 'New passwords do not match.';
            header('Location: /owner/profile');
            exit;
        }

        $userObj->updatePassword($userId, $new);
        $_SESSION['flash']['success'] = 'Password updated successfully.';
        header('Location: /owner/profile');
        exit;
    }
}
