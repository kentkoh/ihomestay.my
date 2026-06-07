<?php

class Mailer {
    public static function send(string $toEmail, string $toName, string $subject, string $html): bool {
        $key = env('BREVO_API_KEY', '');
        if (!$key) return false;

        $payload = json_encode([
            'sender'      => [
                'name'  => env('MAIL_FROM_NAME', 'ihomestay.my'),
                'email' => env('MAIL_FROM_ADDRESS', 'no-reply@ihomestay.my'),
            ],
            'to'          => [['email' => $toEmail, 'name' => $toName]],
            'subject'     => $subject,
            'htmlContent' => $html,
        ]);

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => [
                'api-key: ' . $key,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => 10,
        ]);
        curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $status >= 200 && $status < 300;
    }

    // ── Email templates ────────────────────────────────────────────

    public static function welcome(string $toEmail, string $toName): void {
        $html = self::wrap("Welcome to ihomestay.my, {$toName}!", "
            <p>Hi <strong>{$toName}</strong>,</p>
            <p>Thank you for registering as a homestay owner on <strong>ihomestay.my</strong>.</p>
            <p>You can now start adding your listings. Each listing goes through a quick review before going live.</p>
            <p style='margin-top:24px;'>
                <a href='https://ihomestay.my/owner/listings/create'
                   style='background:#e84c2b;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:600;'>
                   Add Your First Listing
                </a>
            </p>
            <p style='margin-top:24px;color:#64748b;font-size:14px;'>
                If you have any questions, reply to this email or contact us at admin@ihomestay.my.
            </p>
        ");
        self::send($toEmail, $toName, 'Welcome to ihomestay.my!', $html);
    }

    public static function listingSubmitted(string $toEmail, string $toName, string $listingTitle): void {
        $safeTitle = htmlspecialchars($listingTitle);
        $html = self::wrap("Listing Submitted for Review", "
            <p>Hi <strong>{$toName}</strong>,</p>
            <p>Your listing <strong>&ldquo;{$safeTitle}&rdquo;</strong> has been submitted and is now awaiting approval.</p>
            <p>Our team will review it shortly. You will receive an email once it has been approved or if any changes are needed.</p>
            <p style='color:#64748b;font-size:14px;margin-top:24px;'>
                You can manage your listings anytime from your dashboard.
            </p>
        ");
        self::send($toEmail, $toName, "Listing Submitted: \"{$listingTitle}\"", $html);
    }

    public static function listingApproved(string $toEmail, string $toName, string $listingTitle, string $listingUrl): void {
        $safeTitle = htmlspecialchars($listingTitle);
        $html = self::wrap("Your Listing is Live!", "
            <p>Hi <strong>{$toName}</strong>,</p>
            <p>Great news! Your listing <strong>&ldquo;{$safeTitle}&rdquo;</strong> has been approved and is now live on ihomestay.my.</p>
            <p style='margin-top:24px;'>
                <a href='{$listingUrl}'
                   style='background:#e84c2b;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:600;'>
                   View Your Listing
                </a>
            </p>
            <p style='color:#64748b;font-size:14px;margin-top:24px;'>
                Share the link with your guests to start receiving enquiries!
            </p>
        ");
        self::send($toEmail, $toName, "Approved: \"{$listingTitle}\" is now live", $html);
    }

    public static function listingRejected(string $toEmail, string $toName, string $listingTitle, string $reason): void {
        $safeTitle  = htmlspecialchars($listingTitle);
        $safeReason = htmlspecialchars($reason);
        $html = self::wrap("Action Required: Listing Not Approved", "
            <p>Hi <strong>{$toName}</strong>,</p>
            <p>Unfortunately your listing <strong>&ldquo;{$safeTitle}&rdquo;</strong> was not approved at this time.</p>
            <div style='background:#fef2f2;border-left:4px solid #e84c2b;padding:16px;border-radius:4px;margin:20px 0;'>
                <strong>Reason:</strong><br>{$safeReason}
            </div>
            <p>Please update your listing to address the above and resubmit for review.</p>
            <p style='margin-top:24px;'>
                <a href='https://ihomestay.my/owner/listings'
                   style='background:#e84c2b;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:600;'>
                   Edit Your Listing
                </a>
            </p>
        ");
        self::send($toEmail, $toName, "Action Required: \"{$listingTitle}\" needs changes", $html);
    }

    public static function adminNewListing(string $ownerName, string $listingTitle, int $listingId): void {
        $adminEmail = env('ADMIN_EMAIL', 'admin@ihomestay.my');
        $safeOwner  = htmlspecialchars($ownerName);
        $safeTitle  = htmlspecialchars($listingTitle);
        $reviewUrl  = 'https://ihomestay.my/admin/listings?status=pending';
        $html = self::wrap("New Listing Awaiting Approval", "
            <p>A new listing has been submitted and is waiting for your review.</p>
            <table style='border-collapse:collapse;width:100%;margin:16px 0;'>
                <tr><td style='padding:8px;color:#64748b;width:120px;'>Owner</td><td style='padding:8px;font-weight:600;'>{$safeOwner}</td></tr>
                <tr style='background:#f8fafc;'><td style='padding:8px;color:#64748b;'>Listing</td><td style='padding:8px;font-weight:600;'>{$safeTitle}</td></tr>
                <tr><td style='padding:8px;color:#64748b;'>Listing ID</td><td style='padding:8px;'>#{$listingId}</td></tr>
            </table>
            <p style='margin-top:24px;'>
                <a href='{$reviewUrl}'
                   style='background:#e84c2b;color:#fff;padding:12px 24px;border-radius:6px;text-decoration:none;font-weight:600;'>
                   Review Now
                </a>
            </p>
        ");
        self::send($adminEmail, 'ihomestay Admin', "New Listing: \"{$listingTitle}\" awaiting approval", $html);
    }

    // ── Layout wrapper ─────────────────────────────────────────────

    private static function wrap(string $heading, string $body): string {
        return "<!DOCTYPE html>
<html>
<head><meta charset='UTF-8'></head>
<body style='margin:0;padding:0;background:#f1f5f9;font-family:Arial,sans-serif;'>
<table width='100%' cellpadding='0' cellspacing='0' style='background:#f1f5f9;padding:40px 16px;'>
<tr><td align='center'>
<table width='600' cellpadding='0' cellspacing='0' style='background:#fff;border-radius:8px;overflow:hidden;'>
  <tr style='background:#e84c2b;'>
    <td style='padding:24px 32px;'>
      <div style='color:#fff;font-size:20px;font-weight:700;'>ihomestay.my</div>
      <div style='color:#fca5a5;font-size:13px;margin-top:2px;'>Malaysia Homestay Directory</div>
    </td>
  </tr>
  <tr>
    <td style='padding:32px;'>
      <h2 style='margin:0 0 20px;color:#0f172a;font-size:20px;'>{$heading}</h2>
      <div style='color:#334155;line-height:1.7;font-size:15px;'>
        {$body}
      </div>
    </td>
  </tr>
  <tr style='background:#f8fafc;border-top:1px solid #e2e8f0;'>
    <td style='padding:20px 32px;text-align:center;color:#94a3b8;font-size:12px;'>
      &copy; " . date('Y') . " ihomestay.my &mdash; Malaysia Homestay Directory<br>
      This is an automated message, please do not reply directly to this email.
    </td>
  </tr>
</table>
</td></tr>
</table>
</body>
</html>";
    }
}
