<?php

class AdminFacilityController {
    public function index(): void {
        Auth::requireAdmin();
        $grouped = Facility::allGrouped();
        $counts  = Facility::countByStatus();
        $title   = 'Facilities';
        ob_start();
        require APP_PATH . '/Views/admin/facilities/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function create(): void {
        Auth::requireAdmin();
        $categories = Facility::categories();
        $old        = $_SESSION['form_old'] ?? [];
        unset($_SESSION['form_old']);
        $title = 'Add Facility';
        ob_start();
        require APP_PATH . '/Views/admin/facilities/create.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function store(): void {
        Auth::requireAdmin();
        CSRF::verify();

        $name      = trim($_POST['name'] ?? '');
        $category  = trim($_POST['category'] ?? '');
        $sortOrder = $_POST['sort_order'] ?? '0';

        if ($name === '' || $category === '') {
            $_SESSION['flash']['danger']  = 'Name and category are required.';
            $_SESSION['form_old']         = $_POST;
            header('Location: /admin/facilities/create');
            exit;
        }

        if (!is_numeric($sortOrder)) {
            $sortOrder = 0;
        }

        Facility::create([
            'name'       => $name,
            'category'   => $category,
            'sort_order' => (int) $sortOrder,
            'is_active'  => isset($_POST['is_active']) ? 1 : 0,
        ]);

        $_SESSION['flash']['success'] = "Facility \"$name\" added.";
        header('Location: /admin/facilities');
        exit;
    }

    public function edit(string $id): void {
        Auth::requireAdmin();
        $facility = Facility::findById((int) $id);
        if (!$facility) {
            http_response_code(404);
            echo '<h1>Facility not found</h1>';
            return;
        }
        $categories = Facility::categories();
        $title = 'Edit Facility';
        ob_start();
        require APP_PATH . '/Views/admin/facilities/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function update(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $facility = Facility::findById((int) $id);
        if (!$facility) {
            http_response_code(404);
            echo '<h1>Facility not found</h1>';
            return;
        }

        $name      = trim($_POST['name'] ?? '');
        $category  = trim($_POST['category'] ?? '');
        $sortOrder = $_POST['sort_order'] ?? '0';

        if ($name === '' || $category === '') {
            $_SESSION['flash']['danger'] = 'Name and category are required.';
            header("Location: /admin/facilities/$id/edit");
            exit;
        }

        if (!is_numeric($sortOrder)) {
            $sortOrder = 0;
        }

        Facility::update((int) $id, [
            'name'       => $name,
            'category'   => $category,
            'sort_order' => (int) $sortOrder,
            'is_active'  => isset($_POST['is_active']) ? 1 : 0,
        ]);

        $_SESSION['flash']['success'] = "Facility \"$name\" updated.";
        header('Location: /admin/facilities');
        exit;
    }

    public function delete(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $facility = Facility::findById((int) $id);
        if ($facility) {
            Facility::delete((int) $id);
            $_SESSION['flash']['success'] = "Facility \"{$facility['name']}\" deleted.";
        }

        header('Location: /admin/facilities');
        exit;
    }

    public function toggle(string $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $facility = Facility::findById((int) $id);
        if ($facility) {
            Facility::toggle((int) $id);
            $status = $facility['is_active'] ? 'deactivated' : 'activated';
            $_SESSION['flash']['success'] = "Facility \"{$facility['name']}\" $status.";
        }

        header('Location: /admin/facilities');
        exit;
    }
}
