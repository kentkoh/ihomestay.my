# SECURITY_NOTES.md — ihomestay.my

Last updated: Stage 0

## Mandatory security requirements

### Authentication
- Password hashing: PHP password_hash() with PASSWORD_DEFAULT
- Password verify: password_verify()
- Session regeneration on login
- Secure session cookie settings

### CSRF Protection
- Generate CSRF token per session
- Validate token on all POST/PUT/DELETE requests
- Store token in $_SESSION['csrf_token']

### XSS Prevention
- htmlspecialchars() on all output
- Content-Security-Policy header
- Never output raw user input

### SQL Injection Prevention
- Use PDO prepared statements exclusively
- Never concatenate user input into SQL strings

### Input Validation
- Validate and sanitize all user input at controller level
- Use filter_var() and custom validators
- Fail fast with clear error messages

### File Upload Security
- Check file extension whitelist (jpg, jpeg, png, webp only for images)
- Check MIME type (not just extension)
- Rename uploaded file to random hash
- Store uploads outside web root if possible, or in /uploads with no PHP execution
- Limit file size (2MB for banners, larger for listing images)
- Never allow: .php, .svg, .html, .js, .gif (Version 1), .mp4 (Version 1)

### Login Security
- Rate limiting on login endpoint (max 5 attempts per 15 minutes per IP)
- Lock account after repeated failures
- Secure password reset flow (email token, single-use, 1-hour expiry)

### Session Security
```php
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);   // enable on HTTPS
ini_set('session.use_strict_mode', 1);
session_regenerate_id(true);           // on login
```

### Environment File
- Never commit .env to git
- .env is in .gitignore
- .env.example contains keys but no real values
- Config reads from environment variables or .env parser

### Analytics Privacy
- Hash visitor IP before storing in listing_views, listing_clicks, ad_impressions, ad_clicks
- Use: hash('sha256', $ip . $salt) where salt is APP_KEY

### Admin Protection
- Admin routes behind AdminMiddleware
- Check role = 'admin' and status = 'active' on every admin request
- No admin path guessing (use /admin prefix only)

### Public Exposure Rules
- Do NOT expose: /app, /config, /storage, /database, /vendor, /tools
- Only /public is accessible from the web
- .htaccess blocks direct access to sensitive paths

### Payment Security
- Never activate paid features from redirect URL alone
- Always verify via Billplz webhook or server-side API check
- Store raw webhook payload for audit
- Use X-Signature verification for Billplz webhook

## Checklist before every commit

- [ ] No hardcoded passwords, API keys, or tokens
- [ ] No SQL string concatenation with user input
- [ ] All user output is htmlspecialchars'd
- [ ] CSRF token validated on state-changing requests
- [ ] File uploads validated (type + MIME + size)
- [ ] .env not in git
- [ ] No debug output in production code
