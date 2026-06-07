<?php

class AdminController {
    public function dashboard(): void {
        Auth::requireAdmin();
        require APP_PATH . '/Views/admin/dashboard.php';
    }

    public function testEmail(): void {
        Auth::requireAdmin();
        $result = Mailer::send(
            env('ADMIN_EMAIL', 'admin@ihomestay.my'),
            'Admin',
            'ihomestay.my Email Test',
            '<p>This is a test email from ihomestay.my. If you received this, email is working correctly.</p>'
        );
        die($result ? '✅ Email sent successfully — check your inbox.' : '❌ Email failed — check Brevo API key and cURL availability on server.');
    }
}
