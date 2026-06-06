# BILLPLZ_INTEGRATION.md — ihomestay.my

Last updated: Stage 0

## Purpose

Billplz is used for all payment transactions on ihomestay.my:
- Verified Owner upgrade fee
- Featured Listing purchase
- Banner ad purchase
- Sponsored article purchase
- Pro Owner package (future)

## Critical payment rule

**NEVER activate a paid feature based only on redirect URL.**

All activations must be triggered by:
1. Billplz webhook (preferred)
2. Server-side payment status API check (fallback)

## Billplz flow

```
1. User selects package on the website
2. System creates a payment record (status = pending)
3. System calls Billplz API to create a bill
4. User is redirected to Billplz payment page
5. User pays
6. Billplz redirects user back to callback URL
7. Billplz sends webhook POST to webhook URL
8. System verifies X-Signature
9. System marks payment as paid
10. System activates the correct feature based on payment type
```

## Activation rules by payment type

| Payment type          | After paid action                                          |
|-----------------------|------------------------------------------------------------|
| Verification fee      | verification_status = 'pending_verification' (admin still approves) |
| Featured listing      | If owner is verified → activate featured listing, set featured_until |
| Ad banner order       | review_status = 'paid_pending_review' (admin must approve) |
| Sponsored article     | status = 'paid_pending_review' (admin must approve)        |

## Billplz API config (Stage 6)

Keys stored in .env:
```
BILLPLZ_API_KEY=
BILLPLZ_COLLECTION_ID=
BILLPLZ_X_SIGNATURE_KEY=
BILLPLZ_SANDBOX=false
```

Sandbox URL: https://www.billplz-sandbox.com
Production URL: https://www.billplz.com

## Webhook verification

Billplz sends X-Signature-Key header.
Must verify using HMAC before processing.

```php
$expectedSig = hash_hmac('sha256', $rawBody, $xSignatureKey);
if (!hash_equals($expectedSig, $receivedSig)) {
    // reject
}
```

## Bill creation payload

```json
{
  "collection_id": "COLLECTION_ID",
  "email": "customer@email.com",
  "mobile": "0123456789",
  "name": "Customer Name",
  "amount": 4900,
  "callback_url": "https://new.ihomestay.my/payment/webhook",
  "redirect_url": "https://new.ihomestay.my/payment/return",
  "description": "Verified Owner - 1 year"
}
```

Note: amount is in cents (RM49.00 = 4900)

## Raw response storage

Store full Billplz webhook payload in payments.raw_response (LONGTEXT).
This is important for dispute resolution and audit.

## Implementation stage

Billplz integration is built in Stage 6.
Do not implement payment in earlier stages.
