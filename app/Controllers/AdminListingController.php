<?php

class AdminListingController {
    public function index(): void {
        Auth::requireAdmin();
        $status  = $_GET['status'] ?? null;
        $status  = in_array($status, ['pending','published','rejected','suspended','draft'], true) ? $status : null;
        $listings = Listing::allForAdmin($status);
        $counts   = Listing::countByStatus();
        $title    = 'Listings';
        ob_start();
        require APP_PATH . '/Views/admin/listings/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function approve(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if ($listing) {
            Listing::approve((int) $id);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" approved and published.";
            $owner = (new User())->findById((int) $listing['owner_id']);
            if ($owner) {
                $appUrl = env('APP_URL', 'https://ihomestay.my');
                Mailer::listingApproved(
                    $owner['email'],
                    $owner['name'],
                    $listing['title'],
                    $appUrl . '/listing/' . $listing['slug']
                );
            }
        }
        header('Location: /admin/listings?status=pending');
        exit;
    }

    public function reject(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        $reason  = trim($_POST['reason'] ?? '');
        if ($listing && $reason !== '') {
            Listing::reject((int) $id, $reason);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" rejected.";
            $owner = (new User())->findById((int) $listing['owner_id']);
            if ($owner) {
                Mailer::listingRejected($owner['email'], $owner['name'], $listing['title'], $reason);
            }
        }
        header('Location: /admin/listings?status=pending');
        exit;
    }

    public function suspend(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if ($listing) {
            Listing::suspend((int) $id);
            $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" suspended.";
        }
        header('Location: /admin/listings');
        exit;
    }

    public function feature(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $duration = $_POST['duration'] ?? 'forever';
        $until    = null;
        if ($duration === '7')  $until = date('Y-m-d H:i:s', strtotime('+7 days'));
        if ($duration === '14') $until = date('Y-m-d H:i:s', strtotime('+14 days'));
        if ($duration === '30') $until = date('Y-m-d H:i:s', strtotime('+30 days'));
        if ($duration === 'custom' && !empty($_POST['custom_date'])) {
            $until = date('Y-m-d 23:59:59', strtotime($_POST['custom_date']));
        }
        Listing::feature((int) $id, $until);
        $_SESSION['flash']['success'] = 'Listing marked as featured.';
        header('Location: /admin/listings?status=published');
        exit;
    }

    public function unfeature(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        Listing::unfeature((int) $id);
        $_SESSION['flash']['success'] = 'Listing removed from featured.';
        header('Location: /admin/listings?status=published');
        exit;
    }

    public function edit(string $id): void {
        Auth::requireAdmin();
        $listing = Listing::findByIdWithDetails((int) $id);
        if (!$listing) {
            http_response_code(404);
            echo 'Listing not found.';
            return;
        }
        $states          = State::all();
        $cities          = City::all();
        $facilities      = Facility::activeGrouped();
        $selectedFacIds  = Listing::getFacilityIds((int) $id);
        $images          = Listing::getImages((int) $id);
        $title           = 'Edit Listing #' . $id;
        ob_start();
        require APP_PATH . '/Views/admin/listings/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function update(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if (!$listing) {
            header('Location: /admin/listings');
            exit;
        }

        $errors = [];
        if (trim($_POST['title'] ?? '') === '') $errors[] = 'Title is required.';
        if (!is_numeric($_POST['price_per_night'] ?? '') || (float) $_POST['price_per_night'] <= 0) $errors[] = 'A valid price is required.';
        if (empty($_POST['state_id'])) $errors[] = 'State is required.';
        if (empty($_POST['city_id']))  $errors[] = 'City is required.';

        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header("Location: /admin/listings/$id/edit");
            exit;
        }

        $newStatus = in_array($_POST['status'] ?? '', ['pending','published','rejected','suspended','draft'], true)
            ? $_POST['status']
            : $listing['status'];

        Listing::update((int) $id, [
            'title'            => trim($_POST['title']),
            'description'      => trim($_POST['description'] ?? ''),
            'address'          => trim($_POST['address'] ?? ''),
            'state_id'         => (int) $_POST['state_id'],
            'city_id'          => (int) $_POST['city_id'],
            'postcode'         => trim($_POST['postcode'] ?? ''),
            'latitude'         => $_POST['latitude'] !== '' ? $_POST['latitude'] : null,
            'longitude'        => $_POST['longitude'] !== '' ? $_POST['longitude'] : null,
            'price_per_night'  => (float) $_POST['price_per_night'],
            'min_nights'       => max(1, (int) ($_POST['min_nights']  ?? 1)),
            'max_guests'       => max(1, (int) ($_POST['max_guests']  ?? 1)),
            'bedrooms'         => max(0, (int) ($_POST['bedrooms']    ?? 0)),
            'bathrooms'        => max(1, (int) ($_POST['bathrooms']   ?? 1)),
            'whatsapp'         => $listing['whatsapp'],
            'status'           => $newStatus,
            'rejection_reason' => $newStatus === 'rejected' ? trim($_POST['rejection_reason'] ?? '') : null,
        ]);

        Listing::syncFacilities((int) $id, array_map('intval', $_POST['facilities'] ?? []));

        $_SESSION['flash']['success'] = 'Listing updated successfully.';
        header('Location: /admin/listings');
        exit;
    }

    public function deleteListing(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();
        $listing = Listing::findById((int) $id);
        if (!$listing) {
            header('Location: /admin/listings');
            exit;
        }

        $images = Listing::getImages((int) $id);
        foreach ($images as $img) {
            $path = UPLOAD_PATH . '/listings/' . $id . '/' . $img['filename'];
            if (file_exists($path)) unlink($path);
        }
        $dir = UPLOAD_PATH . '/listings/' . $id;
        if (is_dir($dir)) @rmdir($dir);

        Listing::delete((int) $id);
        $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" deleted.";
        header('Location: /admin/listings');
        exit;
    }
}
