<?php

class AdminController {
    public function dashboard(): void {
        Auth::requireAdmin();
        require APP_PATH . '/Views/admin/dashboard.php';
    }

    public function testEmail(): void {
        Auth::requireAdmin();
        echo '<pre>';
        echo 'SMTP host: '  . env('SMTP_HOST', 'NOT SET') . "\n";
        echo 'SMTP user: '  . env('SMTP_USER', 'NOT SET') . "\n";
        echo 'SMTP pass: '  . (env('SMTP_PASS') ? 'SET' : 'NOT SET') . "\n\n";
        echo 'Sending to kkentt2000@gmail.com via SMTP...' . "\n";
        flush();
        ob_flush();
        $result = Mailer::send(
            'kkentt2000@gmail.com',
            'Admin',
            'ihomestay.my Email Test',
            '<p>Test email from ihomestay.my via SMTP. Email is working!</p>'
        );
        echo $result ? '✅ Sent! Check kkentt2000@gmail.com inbox.' : '❌ Failed — SMTP connection refused or credentials wrong.';
        echo '</pre>';
        exit;
    }
}
