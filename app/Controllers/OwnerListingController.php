<?php

class OwnerListingController {
    public function index(): void {
        Auth::requireOwner();
        $listings = Listing::byOwner(Auth::id());
        $count    = count($listings);
        $pageTitle = 'My Listings';
        ob_start();
        require APP_PATH . '/Views/owner/listings/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function create(): void {
        Auth::requireOwner();

        $user = Auth::user();
        if ($user['plan_type'] === 'free' && Listing::countByOwner(Auth::id()) >= 3) {
            $_SESSION['flash']['danger'] = 'Free plan allows maximum 3 listings. Upgrade to add more.';
            header('Location: /owner/listings');
            exit;
        }

        $states    = State::all();
        $cities    = City::all();
        $facilities = Facility::activeGrouped();
        $pageTitle = 'Add New Listing';
        ob_start();
        require APP_PATH . '/Views/owner/listings/create.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function store(): void {
        Auth::requireOwner();
        CSRF::verify();

        $user = Auth::user();
        if ($user['plan_type'] === 'free' && Listing::countByOwner(Auth::id()) >= 3) {
            $_SESSION['flash']['danger'] = 'Free plan allows maximum 3 listings.';
            header('Location: /owner/listings');
            exit;
        }

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            $_SESSION['form_old'] = $_POST;
            header('Location: /owner/listings/create');
            exit;
        }

        $listingId = Listing::create([
            'owner_id'        => Auth::id(),
            'title'           => trim($_POST['title']),
            'description'     => trim($_POST['description']),
            'address'         => trim($_POST['address']),
            'state_id'        => (int) $_POST['state_id'],
            'city_id'         => (int) $_POST['city_id'],
            'postcode'        => trim($_POST['postcode'] ?? ''),
            'latitude'        => $_POST['latitude'] !== '' ? $_POST['latitude'] : null,
            'longitude'       => $_POST['longitude'] !== '' ? $_POST['longitude'] : null,
            'price_per_night' => (float) $_POST['price_per_night'],
            'min_nights'      => max(1, (int) ($_POST['min_nights'] ?? 1)),
            'max_guests'      => max(1, (int) ($_POST['max_guests'] ?? 1)),
            'bedrooms'        => max(0, (int) ($_POST['bedrooms'] ?? 1)),
            'bathrooms'       => max(1, (int) ($_POST['bathrooms'] ?? 1)),
            'whatsapp'        => trim($_POST['whatsapp'] ?? ''),
            'status'          => 'pending',
        ]);

        Listing::syncFacilities($listingId, $_POST['facilities'] ?? []);
        $this->handleImageUploads($listingId, true);

        $_SESSION['flash']['success'] = 'Listing submitted for review. We will notify you once approved.';
        header('Location: /owner/listings');
        exit;
    }

    public function edit(string $id): void {
        Auth::requireOwner();
        $listing = $this->ownerListing((int) $id);

        $states         = State::all();
        $cities         = City::all();
        $facilities     = Facility::activeGrouped();
        $selectedFacIds = Listing::getFacilityIds((int) $id);
        $images         = Listing::getImages((int) $id);
        $pageTitle      = 'Edit Listing';
        ob_start();
        require APP_PATH . '/Views/owner/listings/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/main.php';
    }

    public function update(string $id): void {
        Auth::requireOwner();
        CSRF::verify();
        $listing = $this->ownerListing((int) $id);

        $errors = $this->validate($_POST);
        if (!empty($errors)) {
            $_SESSION['flash']['danger'] = implode('<br>', $errors);
            header("Location: /owner/listings/$id/edit");
            exit;
        }

        $newStatus = $listing['status'] === 'rejected' ? 'pending' : $listing['status'];

        Listing::update((int) $id, [
            'title'           => trim($_POST['title']),
            'description'     => trim($_POST['description']),
            'address'         => trim($_POST['address']),
            'state_id'        => (int) $_POST['state_id'],
            'city_id'         => (int) $_POST['city_id'],
            'postcode'        => trim($_POST['postcode'] ?? ''),
            'latitude'        => $_POST['latitude'] !== '' ? $_POST['latitude'] : null,
            'longitude'       => $_POST['longitude'] !== '' ? $_POST['longitude'] : null,
            'price_per_night' => (float) $_POST['price_per_night'],
            'min_nights'      => max(1, (int) ($_POST['min_nights'] ?? 1)),
            'max_guests'      => max(1, (int) ($_POST['max_guests'] ?? 1)),
            'bedrooms'        => max(0, (int) ($_POST['bedrooms'] ?? 1)),
            'bathrooms'       => max(1, (int) ($_POST['bathrooms'] ?? 1)),
            'whatsapp'        => trim($_POST['whatsapp'] ?? ''),
            'status'          => $newStatus,
            'rejection_reason' => $newStatus === 'pending' ? null : $listing['rejection_reason'],
        ]);

        Listing::syncFacilities((int) $id, $_POST['facilities'] ?? []);
        $this->handleImageUploads((int) $id, false);

        $msg = $newStatus === 'pending'
            ? 'Listing updated and resubmitted for review.'
            : 'Listing updated successfully.';
        $_SESSION['flash']['success'] = $msg;
        header('Location: /owner/listings');
        exit;
    }

    public function uploadImages(string $id): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $id);
        header('Content-Type: application/json');

        $existing = Listing::imageCount((int) $id);
        if ($existing >= 10) {
            echo json_encode(['success' => false, 'error' => 'Maximum 10 photos reached.']);
            exit;
        }
        if (empty($_FILES['files']['name'][0])) {
            echo json_encode(['success' => false, 'error' => 'No files received.']);
            exit;
        }

        $dir = UPLOAD_PATH . '/listings/' . $id;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
        $maxBytes     = 5 * 1024 * 1024;
        $saved        = [];

        foreach ($_FILES['files']['name'] as $i => $name) {
            if ($_FILES['files']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (($existing + count($saved)) >= 10) break;
            if ($_FILES['files']['size'][$i] > $maxBytes) continue;

            $tmpPath = $_FILES['files']['tmp_name'][$i];
            $mime    = mime_content_type($tmpPath);
            if (!in_array($mime, $allowedMimes, true)) continue;

            $ext = match($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                default      => 'jpg',
            };
            $filename = $id . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            if (move_uploaded_file($tmpPath, $dir . '/' . $filename)) {
                $isPrimary = $existing === 0 && count($saved) === 0;
                Listing::addImage((int) $id, $filename, $isPrimary);
                $allImages = Listing::getImages((int) $id);
                foreach ($allImages as $img) {
                    if ($img['filename'] === $filename) { $saved[] = $img; break; }
                }
            }
        }

        echo json_encode(['success' => true, 'images' => $saved]);
        exit;
    }

    public function deleteImage(string $listingId, string $imageId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);

        $filename = Listing::deleteImage((int) $imageId, (int) $listingId);
        if ($filename) {
            $path = UPLOAD_PATH . '/listings/' . $listingId . '/' . $filename;
            if (file_exists($path)) unlink($path);
        }

        header('Content-Type: application/json');
        $remaining  = Listing::getImages((int) $listingId);
        $newPrimary = null;
        foreach ($remaining as $img) {
            if ($img['is_primary']) { $newPrimary = (int) $img['id']; break; }
        }
        echo json_encode(['success' => true, 'new_primary' => $newPrimary]);
        exit;
    }

    public function setPrimary(string $listingId, string $imageId): void {
        Auth::requireOwner();
        CSRF::verify();
        $this->ownerListing((int) $listingId);
        Listing::setPrimaryImage((int) $imageId, (int) $listingId);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    public function delete(string $id): void {
        Auth::requireOwner();
        CSRF::verify();
        $listing = $this->ownerListing((int) $id);

        $images = Listing::getImages((int) $id);
        foreach ($images as $img) {
            $path = UPLOAD_PATH . '/listings/' . $id . '/' . $img['filename'];
            if (file_exists($path)) unlink($path);
        }

        Listing::delete((int) $id);
        $_SESSION['flash']['success'] = "Listing \"{$listing['title']}\" deleted.";
        header('Location: /owner/listings');
        exit;
    }

    private function ownerListing(int $id): array {
        $listing = Listing::findById($id);
        if (!$listing || (int) $listing['owner_id'] !== Auth::id()) {
            http_response_code(403);
            echo '<h1>Access denied</h1>';
            exit;
        }
        return $listing;
    }

    private function validate(array $post): array {
        $errors = [];
        if (trim($post['title'] ?? '') === '')            $errors[] = 'Title is required.';
        if (trim($post['description'] ?? '') === '')      $errors[] = 'Description is required.';
        if (empty($post['state_id']))                     $errors[] = 'State is required.';
        if (empty($post['city_id']))                      $errors[] = 'City is required.';
        if (trim($post['address'] ?? '') === '')          $errors[] = 'Address is required.';
        if (!is_numeric($post['price_per_night'] ?? '') || (float)$post['price_per_night'] <= 0)
                                                          $errors[] = 'Price per night must be greater than 0.';
        return $errors;
    }

    private function handleImageUploads(int $listingId, bool $isCreate): void {
        if (empty($_FILES['images']['name'][0])) return;

        $dir = UPLOAD_PATH . '/listings/' . $listingId;
        if (!is_dir($dir)) mkdir($dir, 0755, true);

        $existingCount = Listing::imageCount($listingId);
        $allowedMimes  = ['image/jpeg', 'image/png', 'image/webp'];
        $maxBytes      = 5 * 1024 * 1024;
        $uploaded      = 0;

        foreach ($_FILES['images']['name'] as $i => $name) {
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) continue;
            if (($existingCount + $uploaded) >= 10) break;

            $tmpPath = $_FILES['images']['tmp_name'][$i];
            if ($_FILES['images']['size'][$i] > $maxBytes) continue;

            $mime = mime_content_type($tmpPath);
            if (!in_array($mime, $allowedMimes, true)) continue;

            $ext = match($mime) {
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/webp' => 'webp',
                default      => 'jpg',
            };

            $filename = $listingId . '_' . time() . '_' . bin2hex(random_bytes(3)) . '.' . $ext;
            if (move_uploaded_file($tmpPath, $dir . '/' . $filename)) {
                $isPrimary = $isCreate && $existingCount === 0 && $uploaded === 0;
                Listing::addImage($listingId, $filename, $isPrimary);
                $uploaded++;
            }
        }
    }
}
