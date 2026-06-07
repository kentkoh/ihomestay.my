<style>
@keyframes fadeUp {
    from { opacity:0; transform:translateY(40px); }
    to   { opacity:1; transform:translateY(0); }
}
@keyframes float {
    0%,100% { transform:translateY(0); }
    50%      { transform:translateY(-12px); }
}
@keyframes pulse-ring {
    0%   { transform:scale(1);   opacity:.6; }
    100% { transform:scale(1.6); opacity:0; }
}
@keyframes count-up { from { opacity:0; } to { opacity:1; } }

.anim-fade-up   { opacity:0; animation:fadeUp .7s ease forwards; }
.anim-delay-1   { animation-delay:.15s; }
.anim-delay-2   { animation-delay:.3s; }
.anim-delay-3   { animation-delay:.45s; }
.anim-delay-4   { animation-delay:.6s; }

.float-icon { animation:float 3.5s ease-in-out infinite; }
.float-icon-2 { animation:float 4s ease-in-out infinite .8s; }
.float-icon-3 { animation:float 3s ease-in-out infinite 1.4s; }

.stat-card {
    background:#fff;border-radius:16px;padding:2rem 1.5rem;
    box-shadow:0 4px 24px rgba(0,0,0,.07);
    transition:transform .3s,box-shadow .3s;
    text-align:center;
}
.stat-card:hover { transform:translateY(-6px);box-shadow:0 12px 36px rgba(232,76,43,.15); }

.feature-card {
    background:#fff;border-radius:16px;padding:2rem;
    box-shadow:0 2px 16px rgba(0,0,0,.06);
    transition:transform .3s,box-shadow .3s;
    border-bottom:3px solid transparent;
}
.feature-card:hover { transform:translateY(-4px);box-shadow:0 8px 32px rgba(232,76,43,.12);border-bottom-color:#e84c2b; }

.pulse-dot {
    width:12px;height:12px;border-radius:50%;
    background:#e84c2b;display:inline-block;position:relative;
}
.pulse-dot::after {
    content:'';position:absolute;inset:-4px;border-radius:50%;
    border:2px solid #e84c2b;animation:pulse-ring 1.5s ease-out infinite;
}

.about-hero {
    background:linear-gradient(135deg,#0f1923 0%,#1e3a4a 60%,#0f1923 100%);
    min-height:480px;position:relative;overflow:hidden;
}
.about-hero::before {
    content:'';position:absolute;inset:0;
    background:radial-gradient(ellipse at 70% 50%,rgba(232,76,43,.18) 0%,transparent 60%);
}
.hero-grid-bg {
    position:absolute;inset:0;
    background-image:linear-gradient(rgba(255,255,255,.03) 1px,transparent 1px),
                     linear-gradient(90deg,rgba(255,255,255,.03) 1px,transparent 1px);
    background-size:48px 48px;
}

.timeline-item { position:relative;padding-left:2.5rem; }
.timeline-item::before {
    content:'';position:absolute;left:10px;top:28px;bottom:-32px;
    width:2px;background:linear-gradient(to bottom,#e84c2b,transparent);
}
.timeline-item:last-child::before { display:none; }
.timeline-dot {
    position:absolute;left:0;top:20px;width:22px;height:22px;
    border-radius:50%;background:#e84c2b;
    display:flex;align-items:center;justify-content:center;
}
</style>

<!-- Hero -->
<div class="about-hero d-flex align-items-center">
    <div class="hero-grid-bg"></div>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="anim-fade-up">
                    <span class="badge mb-3 px-3 py-2" style="background:rgba(232,76,43,.2);color:#fca5a5;font-size:.8rem;letter-spacing:.1em;text-transform:uppercase;">About ihomestay.my</span>
                    <h1 style="color:#fff;font-size:clamp(2rem,5vw,3.2rem);font-weight:800;line-height:1.15;">
                        Malaysia's Homestay<br>
                        <span style="color:#e84c2b;">Directory</span> —<br>
                        Direct from Owners
                    </h1>
                </div>
                <div class="anim-fade-up anim-delay-1">
                    <p style="color:#94a3b8;font-size:1.1rem;line-height:1.8;margin-top:1.2rem;max-width:480px;">
                        We connect guests directly with homestay owners across Malaysia — no middlemen, no platform fees, no surprises. Just real stays from real people.
                    </p>
                </div>
                <div class="anim-fade-up anim-delay-2 mt-4 d-flex align-items-center gap-3">
                    <span class="pulse-dot"></span>
                    <span style="color:#64748b;font-size:.95rem;">Proudly Malaysian &mdash; by <strong style="color:#cbd5e1;">Nature Biozyme Sdn Bhd</strong></span>
                </div>
            </div>
            <div class="col-lg-6 d-none d-lg-flex justify-content-center position-relative" style="height:320px;">
                <!-- Floating graphic elements -->
                <div class="float-icon position-absolute" style="top:20px;right:80px;">
                    <div style="width:80px;height:80px;border-radius:20px;background:rgba(232,76,43,.15);border:1px solid rgba(232,76,43,.3);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-house-heart-fill" style="font-size:2rem;color:#e84c2b;"></i>
                    </div>
                </div>
                <div class="float-icon-2 position-absolute" style="top:80px;right:20px;">
                    <div style="width:60px;height:60px;border-radius:14px;background:rgba(16,185,129,.1);border:1px solid rgba(16,185,129,.3);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-shield-check-fill" style="font-size:1.4rem;color:#10b981;"></i>
                    </div>
                </div>
                <div class="float-icon-3 position-absolute" style="top:160px;right:100px;">
                    <div style="width:70px;height:70px;border-radius:16px;background:rgba(245,158,11,.1);border:1px solid rgba(245,158,11,.3);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-geo-alt-fill" style="font-size:1.6rem;color:#f59e0b;"></i>
                    </div>
                </div>
                <div class="float-icon position-absolute" style="top:60px;right:180px;">
                    <div style="width:56px;height:56px;border-radius:12px;background:rgba(99,102,241,.1);border:1px solid rgba(99,102,241,.3);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-people-fill" style="font-size:1.3rem;color:#6366f1;"></i>
                    </div>
                </div>
                <!-- Central card -->
                <div class="position-absolute" style="top:50%;left:50%;transform:translate(-50%,-50%);width:220px;">
                    <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:20px;padding:1.5rem;backdrop-filter:blur(12px);text-align:center;">
                        <div style="font-size:2.2rem;font-weight:800;color:#fff;">ihomestay</div>
                        <div style="color:#e84c2b;font-size:.85rem;letter-spacing:.12em;">.my</div>
                        <div style="margin-top:.8rem;color:#64748b;font-size:.8rem;">Malaysia Homestay Directory</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div style="background:#fff;border-bottom:1px solid #f1f5f9;">
    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            <div class="col-6 col-md-3 anim-fade-up">
                <div class="stat-card">
                    <div style="font-size:2.4rem;font-weight:800;color:#e84c2b;" class="counter" data-target="16">0</div>
                    <div style="color:#64748b;font-size:.9rem;margin-top:.3rem;">States Covered</div>
                </div>
            </div>
            <div class="col-6 col-md-3 anim-fade-up anim-delay-1">
                <div class="stat-card">
                    <div style="font-size:2.4rem;font-weight:800;color:#e84c2b;" class="counter" data-target="100">0</div>
                    <div style="color:#64748b;font-size:.9rem;margin-top:.3rem;">Homestay Listings</div>
                </div>
            </div>
            <div class="col-6 col-md-3 anim-fade-up anim-delay-2">
                <div class="stat-card">
                    <div style="font-size:2.4rem;font-weight:800;color:#e84c2b;">0%</div>
                    <div style="color:#64748b;font-size:.9rem;margin-top:.3rem;">Platform Fee</div>
                </div>
            </div>
            <div class="col-6 col-md-3 anim-fade-up anim-delay-3">
                <div class="stat-card">
                    <div style="font-size:2.4rem;font-weight:800;color:#e84c2b;">24/7</div>
                    <div style="color:#64748b;font-size:.9rem;margin-top:.3rem;">Always Online</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mission & Who We Are -->
<div style="background:#f8fafc;">
    <div class="container py-6" style="padding-top:5rem;padding-bottom:5rem;">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5 anim-fade-up">
                <div style="position:relative;">
                    <div style="background:linear-gradient(135deg,#e84c2b,#c73d22);border-radius:24px;padding:3rem;color:#fff;">
                        <i class="bi bi-buildings-fill" style="font-size:3rem;opacity:.3;"></i>
                        <h3 style="font-weight:800;font-size:1.5rem;margin-top:1rem;">Nature Biozyme<br>Sdn Bhd</h3>
                        <p style="opacity:.85;line-height:1.7;margin-top:.8rem;">
                            The company behind ihomestay.my — proudly Malaysian, committed to empowering local homestay owners across the country.
                        </p>
                        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,.2);display:flex;gap:2rem;">
                            <div>
                                <div style="font-weight:700;font-size:1.1rem;">Malaysian</div>
                                <div style="opacity:.7;font-size:.85rem;">Owned &amp; Operated</div>
                            </div>
                            <div>
                                <div style="font-weight:700;font-size:1.1rem;">2024</div>
                                <div style="opacity:.7;font-size:.85rem;">Founded</div>
                            </div>
                        </div>
                    </div>
                    <!-- Decorative blob -->
                    <div style="position:absolute;bottom:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(232,76,43,.08);z-index:-1;"></div>
                    <div style="position:absolute;top:-15px;left:-15px;width:60px;height:60px;border-radius:50%;background:rgba(232,76,43,.06);z-index:-1;"></div>
                </div>
            </div>
            <div class="col-lg-7 anim-fade-up anim-delay-2">
                <span class="badge px-3 py-2 mb-3" style="background:#fef2f0;color:#e84c2b;font-size:.8rem;letter-spacing:.08em;">OUR MISSION</span>
                <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:#0f172a;line-height:1.3;">
                    Putting Homestay Owners<br>Back in Control
                </h2>
                <p style="color:#64748b;line-height:1.9;margin-top:1rem;font-size:1.05rem;">
                    ihomestay.my was built out of frustration with the status quo — large platforms taking 15–30% from every booking, leaving owners with less and guests paying more. We believe the best stays happen when owners and guests connect directly.
                </p>
                <p style="color:#64748b;line-height:1.9;font-size:1.05rem;">
                    Our platform is a <strong style="color:#0f172a;">directory</strong>, not a booking engine. Owners list their properties, guests find them, and they connect directly — via WhatsApp, phone, or email. Simple, transparent, Malaysian.
                </p>
                <div class="row g-3 mt-2">
                    <?php foreach ([
                        ['bi-check-circle-fill','#10b981','Direct WhatsApp contact'],
                        ['bi-check-circle-fill','#10b981','No booking fees ever'],
                        ['bi-check-circle-fill','#10b981','Verified host badges'],
                        ['bi-check-circle-fill','#10b981','All 16 Malaysian states'],
                    ] as $f): ?>
                    <div class="col-6">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi <?= $f[0] ?>" style="color:<?= $f[1] ?>;flex-shrink:0;"></i>
                            <span style="color:#334155;font-size:.95rem;"><?= $f[2] ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works -->
<div style="background:#fff;">
    <div class="container" style="padding-top:5rem;padding-bottom:5rem;">
        <div class="text-center mb-5 anim-fade-up">
            <span class="badge px-3 py-2 mb-3" style="background:#fef2f0;color:#e84c2b;font-size:.8rem;letter-spacing:.08em;">HOW IT WORKS</span>
            <h2 style="font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;color:#0f172a;">Simple. Direct. Malaysian.</h2>
        </div>
        <div class="row g-4">
            <?php foreach ([
                ['1','bi-search','Find a Homestay','Search by state, city, or keyword. Filter by facilities, price, and guest count.','#e84c2b'],
                ['2','bi-house-check','View Listing','See photos, location on map, facilities, and full pricing — all in one page.','#6366f1'],
                ['3','bi-whatsapp','Contact Owner','Message the owner directly on WhatsApp. No middleman, no fees, no waiting.','#10b981'],
                ['4','bi-calendar-check','Book &amp; Stay','Arrange dates and payment directly with the owner. Completely flexible.','#f59e0b'],
            ] as $step): ?>
            <div class="col-sm-6 col-lg-3 anim-fade-up">
                <div class="feature-card h-100">
                    <div style="width:52px;height:52px;border-radius:14px;background:<?= $step[4] ?>18;display:flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="bi <?= $step[1] ?>" style="font-size:1.4rem;color:<?= $step[4] ?>;"></i>
                    </div>
                    <div style="font-size:.8rem;font-weight:700;color:<?= $step[4] ?>;letter-spacing:.1em;margin-bottom:.4rem;">STEP <?= $step[0] ?></div>
                    <h5 style="font-weight:700;color:#0f172a;"><?= $step[2] ?></h5>
                    <p style="color:#64748b;font-size:.95rem;line-height:1.7;margin:0;"><?= $step[3] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- CTA -->
<div style="background:linear-gradient(135deg,#0f1923,#1e3a4a);padding:5rem 0;">
    <div class="container text-center">
        <div class="anim-fade-up">
            <h2 style="color:#fff;font-size:clamp(1.6rem,3vw,2.2rem);font-weight:800;">Ready to List Your Homestay?</h2>
            <p style="color:#64748b;margin-top:.8rem;font-size:1.05rem;">Join hundreds of Malaysian owners already on ihomestay.my</p>
            <div class="d-flex gap-3 justify-content-center flex-wrap mt-4">
                <a href="/register" class="btn px-4 py-2" style="background:#e84c2b;color:#fff;border-radius:10px;font-weight:600;">
                    <i class="bi bi-plus-circle me-2"></i>List for Free
                </a>
                <a href="/contact" class="btn px-4 py-2" style="background:rgba(255,255,255,.08);color:#fff;border:1px solid rgba(255,255,255,.15);border-radius:10px;font-weight:600;">
                    <i class="bi bi-chat me-2"></i>Contact Us
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Intersection observer for animations
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.style.animationPlayState = 'running'; } });
}, { threshold: 0.1 });
document.querySelectorAll('.anim-fade-up').forEach(el => {
    el.style.animationPlayState = 'paused';
    observer.observe(el);
});

// Counter animation
function animateCounter(el) {
    const target = parseInt(el.dataset.target);
    const duration = 1500;
    const step = target / (duration / 16);
    let current = 0;
    const timer = setInterval(() => {
        current += step;
        if (current >= target) { current = target; clearInterval(timer); }
        el.textContent = Math.floor(current) + (el.dataset.suffix || '');
    }, 16);
}
const counterObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            animateCounter(e.target);
            counterObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('.counter').forEach(el => counterObserver.observe(el));
</script>
