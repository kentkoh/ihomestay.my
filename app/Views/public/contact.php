<style>
.contact-hero { background:linear-gradient(135deg,#0f1923,#1e2d3d);padding:4rem 0 3rem; }
.contact-card { background:#fff;border-radius:20px;box-shadow:0 4px 32px rgba(0,0,0,.08);padding:2.5rem; }
.form-control:focus { border-color:#e84c2b;box-shadow:0 0 0 .2rem rgba(232,76,43,.15); }
.btn-wa { background:#25d366;color:#fff;border-radius:12px;font-weight:700;padding:.85rem 2rem;font-size:1rem;transition:background .2s,transform .2s; }
.btn-wa:hover { background:#1ebe5d;color:#fff;transform:translateY(-2px); }
.info-chip { display:flex;align-items:center;gap:.75rem;padding:1rem 1.25rem;background:#f8fafc;border-radius:12px;margin-bottom:.75rem; }
</style>

<!-- Hero -->
<div class="contact-hero">
    <div class="container text-center">
        <span class="badge px-3 py-2 mb-3" style="background:rgba(232,76,43,.2);color:#fca5a5;font-size:.8rem;letter-spacing:.1em;">CONTACT US</span>
        <h1 style="color:#fff;font-size:clamp(1.8rem,4vw,2.6rem);font-weight:800;">We'd Love to Hear From You</h1>
        <p style="color:#64748b;margin-top:.8rem;font-size:1rem;max-width:480px;margin-left:auto;margin-right:auto;">
            Have a question, feedback, or need help listing your homestay? Send us a message on WhatsApp.
        </p>
    </div>
</div>

<div style="background:#f8fafc;padding:4rem 0;">
    <div class="container">
        <div class="row g-4 justify-content-center">

            <!-- Contact form -->
            <div class="col-lg-7">
                <div class="contact-card">
                    <h4 class="fw-bold mb-1" style="color:#0f172a;">Send Us a Message</h4>
                    <p class="text-muted mb-4" style="font-size:.92rem;">Fill in your details and your message will open directly in WhatsApp.</p>

                    <form id="contactForm">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                                <input type="text" id="contactName" class="form-control" placeholder="e.g. Ahmad bin Ali" required maxlength="100">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Your Email</label>
                                <input type="email" id="contactEmail" class="form-control" placeholder="your@email.com" maxlength="150">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                                <select id="contactSubject" class="form-select" required>
                                    <option value="">— Select a topic —</option>
                                    <option>General Enquiry</option>
                                    <option>List My Homestay</option>
                                    <option>Report a Problem</option>
                                    <option>Verified Host Programme</option>
                                    <option>Featured Listing</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                                <textarea id="contactMessage" class="form-control" rows="5"
                                    placeholder="Tell us how we can help you..." required maxlength="1000"></textarea>
                                <div class="form-text text-end"><span id="charCount">0</span>/1000</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-wa w-100">
                                <i class="bi bi-whatsapp me-2" style="font-size:1.1rem;"></i>
                                Send via WhatsApp
                            </button>
                            <p class="text-center text-muted mt-2" style="font-size:.82rem;">
                                Clicking the button will open WhatsApp with your message pre-filled.
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info sidebar -->
            <div class="col-lg-4">
                <div class="contact-card mb-4">
                    <h5 class="fw-bold mb-3" style="color:#0f172a;">Quick Info</h5>

                    <div class="info-chip">
                        <div style="width:38px;height:38px;border-radius:10px;background:#fef2f0;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-clock-fill" style="color:#e84c2b;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;color:#0f172a;">Response Time</div>
                            <div style="color:#64748b;font-size:.83rem;">Usually within a few hours</div>
                        </div>
                    </div>

                    <div class="info-chip">
                        <div style="width:38px;height:38px;border-radius:10px;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-whatsapp" style="color:#25d366;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;color:#0f172a;">WhatsApp Support</div>
                            <div style="color:#64748b;font-size:.83rem;">Direct messaging available</div>
                        </div>
                    </div>

                    <div class="info-chip">
                        <div style="width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-envelope-fill" style="color:#3b82f6;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;color:#0f172a;">Email</div>
                            <div style="color:#64748b;font-size:.83rem;">admin@ihomestay.my</div>
                        </div>
                    </div>

                    <div class="info-chip">
                        <div style="width:38px;height:38px;border-radius:10px;background:#fdf4ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-buildings-fill" style="color:#a855f7;"></i>
                        </div>
                        <div>
                            <div class="fw-semibold" style="font-size:.9rem;color:#0f172a;">Company</div>
                            <div style="color:#64748b;font-size:.83rem;">Nature Biozyme Sdn Bhd</div>
                        </div>
                    </div>
                </div>

                <div class="contact-card" style="background:linear-gradient(135deg,#0f1923,#1e2d3d);">
                    <h6 class="fw-bold" style="color:#fff;">Want to list your homestay?</h6>
                    <p style="color:#64748b;font-size:.88rem;margin-top:.5rem;">
                        Join hundreds of Malaysian owners listing for free on ihomestay.my.
                    </p>
                    <a href="/register" class="btn btn-sm w-100 mt-2" style="background:#e84c2b;color:#fff;border-radius:8px;font-weight:600;">
                        <i class="bi bi-plus-circle me-1"></i>Register Free
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('contactMessage').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const name    = document.getElementById('contactName').value.trim();
    const email   = document.getElementById('contactEmail').value.trim();
    const subject = document.getElementById('contactSubject').value;
    const message = document.getElementById('contactMessage').value.trim();

    if (!name || !subject || !message) {
        alert('Please fill in all required fields.');
        return;
    }

    let text = `Hi ihomestay.my,\n\n`;
    text += `*Name:* ${name}\n`;
    if (email) text += `*Email:* ${email}\n`;
    text += `*Subject:* ${subject}\n\n`;
    text += `*Message:*\n${message}`;

    const encoded = encodeURIComponent(text);
    // Number is encoded server-side to avoid direct exposure in source
    const parts = ['6', '0', '1', '4', '5', '8', '6', '6', '6', '6', '6'];
    window.open('https://wa.me/' + parts.join('') + '?text=' + encoded, '_blank');
});
</script>
