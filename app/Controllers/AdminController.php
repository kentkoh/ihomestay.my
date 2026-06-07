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

        // Test curl connectivity to Brevo API directly
        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => '{}',
            CURLOPT_HTTPHEADER      => ['Content-Type: application/json'],
            CURLOPT_TIMEOUT         => 8,
            CURLOPT_CONNECTTIMEOUT  => 5,
        ]);
        curl_exec($ch);
        $curlError  = curl_error($ch);
        $curlErrNo  = curl_errno($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        echo 'cURL error: '   . ($curlError  ?: 'none') . "\n";
        echo 'cURL errno: '   . $curlErrNo . "\n";
        echo 'HTTP status: '  . $httpStatus . "\n\n";

        $result = Mailer::send(
            'kkentt2000@gmail.com',
            'Admin',
            'ihomestay.my Email Test',
            '<p>Test email from ihomestay.my. Email is working!</p>'
        );
        echo $result ? '✅ Email sent — check kkentt2000@gmail.com inbox.' : '❌ Email failed.';
        echo '</pre>';
        exit;
    }
}
