<?php

class AdminController {
    public function dashboard(): void {
        Auth::requireAdmin();
        require APP_PATH . '/Views/admin/dashboard.php';
    }

    public function testEmail(): void {
        Auth::requireAdmin();
        $curlAvailable = function_exists('curl_init');
        $apiKey        = env('BREVO_API_KEY', '');
        $adminEmail    = env('ADMIN_EMAIL', 'admin@ihomestay.my');

        echo '<pre>';
        echo 'cURL available: '   . ($curlAvailable ? 'YES' : 'NO') . "\n";
        echo 'BREVO_API_KEY set: ' . ($apiKey ? 'YES (' . substr($apiKey, 0, 10) . '...)' : 'NO - check .env') . "\n";
        echo 'Sending to: ' . $adminEmail . "\n\n";

        $result = Mailer::send(
            $adminEmail,
            'Admin',
            'ihomestay.my Email Test',
            '<p>Test email from ihomestay.my. Email is working!</p>'
        );
        echo $result ? '✅ Email sent — check inbox.' : '❌ Email failed — check Brevo API key and cURL.';
        echo '</pre>';
        exit;
    }
}
