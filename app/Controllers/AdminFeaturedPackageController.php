<?php

class AdminFeaturedPackageController {
    private FeaturedPackage $model;

    public function __construct() {
        $this->model = new FeaturedPackage();
    }

    public function index(): void {
        Auth::requireAdmin();
        $packages  = $this->model->all();
        $pageTitle = 'Featured Packages';

        ob_start();
        require APP_PATH . '/Views/admin/featured-packages/index.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function edit(int $id): void {
        Auth::requireAdmin();
        $package   = $this->model->findById($id);
        if (!$package) { header('Location: /admin/featured-packages'); exit; }
        $pageTitle = 'Edit Package';

        ob_start();
        require APP_PATH . '/Views/admin/featured-packages/edit.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }

    public function update(int $id): void {
        Auth::requireAdmin();
        CSRF::verify();

        $package = $this->model->findById($id);
        if (!$package) { header('Location: /admin/featured-packages'); exit; }

        $promoRaw = trim($_POST['promo_price'] ?? '');

        $this->model->update($id, [
            'label'        => trim($_POST['label'] ?? $package['label']),
            'days'         => max(1, (int) ($_POST['days'] ?? $package['days'])),
            'normal_price' => max(0.01, (float) ($_POST['normal_price'] ?? $package['normal_price'])),
            'promo_price'  => $promoRaw !== '' ? max(0, (float) $promoRaw) : null,
            'is_active'    => isset($_POST['is_active']) ? 1 : 0,
            'sort_order'   => (int) ($_POST['sort_order'] ?? $package['sort_order']),
        ]);

        $_SESSION['flash']['success'] = 'Package updated successfully.';
        header('Location: /admin/featured-packages');
        exit;
    }
}
