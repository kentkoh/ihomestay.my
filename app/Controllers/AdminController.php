<?php

class AdminController {
    public function dashboard(): void {
        Auth::requireAdmin();
        require APP_PATH . '/Views/admin/dashboard.php';
    }

    public function testEmail(): void {
        Auth::requireAdmin();
        $host = env('SMTP_HOST', 'smtp-relay.brevo.com');
        $port = (int) env('SMTP_PORT', '587');
        $user = env('SMTP_USER', '');
        $pass = env('SMTP_PASS', '');

        echo '<pre>';

        // Step 1: fsockopen
        echo "1. Connecting to {$host}:{$port}...\n";
        flush(); ob_flush();
        $sock = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$sock) {
            echo "❌ Connection failed: [{$errno}] {$errstr}\n";
            echo "   Port 587 is blocked by your hosting. Contact Kuantan1 host to open it.\n";
            echo '</pre>'; exit;
        }
        echo "✅ Connected.\n";
        $greeting = fgets($sock, 512);
        echo "   Server: " . trim($greeting) . "\n\n";

        // Step 2: EHLO
        fputs($sock, "EHLO ihomestay.my\r\n");
        $ehlo = '';
        while ($line = fgets($sock, 512)) { $ehlo .= trim($line) . "\n"; if ($line[3] === ' ') break; }
        echo "2. EHLO response:\n{$ehlo}\n";

        // Step 3: STARTTLS
        fputs($sock, "STARTTLS\r\n");
        $tls = fgets($sock, 512);
        echo "3. STARTTLS: " . trim($tls) . "\n";
        if (!stream_socket_enable_crypto($sock, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
            echo "❌ TLS handshake failed.\n"; fclose($sock);
            echo '</pre>'; exit;
        }
        echo "✅ TLS OK.\n\n";

        // Step 4: AUTH
        fputs($sock, "EHLO ihomestay.my\r\n");
        while ($line = fgets($sock, 512)) { if ($line[3] === ' ') break; }
        fputs($sock, "AUTH LOGIN\r\n");
        fgets($sock, 512);
        fputs($sock, base64_encode($user) . "\r\n");
        fgets($sock, 512);
        fputs($sock, base64_encode($pass) . "\r\n");
        $auth = fgets($sock, 512);
        echo "4. AUTH result: " . trim($auth) . "\n";
        fclose($sock);

        if (substr($auth, 0, 3) === '235') {
            echo "✅ Auth OK — credentials are correct!\n\n";
            echo "Sending full test email...\n";
            $result = Mailer::send('kkentt2000@gmail.com', 'Admin', 'ihomestay.my Email Test', '<p>Test email from ihomestay.my. Working!</p>');
            echo $result ? '✅ Email sent! Check kkentt2000@gmail.com inbox.' : '❌ Send failed.';
        } else {
            echo "❌ Auth failed — wrong SMTP user or password.\n";
        }
        echo '</pre>'; exit;
    }
}
