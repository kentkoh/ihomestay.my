<?php

class AdminController {
    public function dashboard(): void {
        Auth::requireAdmin();

        $db = Database::get();

        $stats = [
            'total_listings'     => (int) $db->query("SELECT COUNT(*) FROM listings")->fetchColumn(),
            'published_listings' => (int) $db->query("SELECT COUNT(*) FROM listings WHERE status='published'")->fetchColumn(),
            'pending_listings'   => (int) $db->query("SELECT COUNT(*) FROM listings WHERE status='pending'")->fetchColumn(),
            'featured_listings'  => (int) $db->query("SELECT COUNT(*) FROM listings WHERE is_featured=1 AND (featured_until IS NULL OR featured_until > NOW())")->fetchColumn(),
            'total_owners'       => (int) $db->query("SELECT COUNT(*) FROM users WHERE role='owner'")->fetchColumn(),
            'verified_owners'    => (int) $db->query("SELECT COUNT(*) FROM users WHERE role='owner' AND verification_status='verified'")->fetchColumn(),
            'total_articles'     => (int) $db->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
            'total_revenue'      => (float) $db->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status='paid'")->fetchColumn(),
            'paid_payments'      => (int) $db->query("SELECT COUNT(*) FROM payments WHERE status='paid'")->fetchColumn(),
        ];

        $pending = $db->query("
            SELECT l.id, l.title, l.created_at, u.name AS owner_name, s.name AS state_name
            FROM listings l
            JOIN users u ON u.id = l.owner_id
            JOIN states s ON s.id = l.state_id
            WHERE l.status = 'pending'
            ORDER BY l.created_at DESC
            LIMIT 5
        ")->fetchAll();

        $recent_owners = $db->query("
            SELECT u.id, u.name, u.email, u.verification_status, u.created_at,
                   COUNT(l.id) AS listing_count
            FROM users u
            LEFT JOIN listings l ON l.owner_id = u.id
            WHERE u.role = 'owner'
            GROUP BY u.id
            ORDER BY u.created_at DESC
            LIMIT 5
        ")->fetchAll();

        $title = 'Dashboard';
        ob_start();
        require APP_PATH . '/Views/admin/dashboard.php';
        $content = ob_get_clean();
        require APP_PATH . '/Views/layouts/admin.php';
    }
}
