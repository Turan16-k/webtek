/* ============================================================
   main.js – Portfolio JavaScript
   ============================================================ */

/* ---- Navbar scroll effect ---- */
(function initNavbar() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    const update = () => navbar.classList.toggle('scrolled', window.scrollY > 60);
    window.addEventListener('scroll', update, { passive: true });
    update();
})();

/* ---- Back to Top button ---- */
(function initBackToTop() {
    const btn = document.getElementById('backToTop');
    if (!btn) return;
    window.addEventListener('scroll', () => {
        btn.classList.toggle('visible', window.scrollY > 420);
    }, { passive: true });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();

/* ---- Scroll reveal (Intersection Observer) ---- */
(function initScrollReveal() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
})();

/* ---- Skill bar animation ---- */
(function initSkillBars() {
    const bars = document.querySelectorAll('.skill-fill');
    if (!bars.length) return;

    bars.forEach(bar => {
        const target = bar.style.width || '0%';
        bar.dataset.targetWidth = target;
        bar.style.width = '0%';
        bar.style.transition = 'none';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                bar.style.transition = '';
            });
        });
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.width = entry.target.dataset.targetWidth;
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    bars.forEach(bar => observer.observe(bar));
})();

/* ---- Counter animation ---- */
(function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const el = entry.target;
            const target = parseInt(el.dataset.count, 10);
            const suffix = el.dataset.suffix || '';
            const duration = 1800;
            const start = performance.now();

            const update = (now) => {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(eased * target) + suffix;
                if (progress < 1) requestAnimationFrame(update);
            };
            requestAnimationFrame(update);
            observer.unobserve(el);
        });
    }, { threshold: 0.6 });

    counters.forEach(c => observer.observe(c));
})();

/* ---- Active nav auto-highlight ---- */
(function initActiveNav() {
    const current = location.pathname.split('/').pop() || 'index.html';
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        const href = link.getAttribute('href') || '';
        if (href === current) {
            document.querySelectorAll('.navbar-nav .nav-link.active').forEach(a => a.classList.remove('active'));
            link.classList.add('active');
        }
    });
})();

/* ============================================================
   Contact Form – Vanilla JS Validation
   ============================================================ */
function validateWithJS() {
    const errors = [];

    const name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) errors.push('Ad Soyad boş bırakılamaz.');
    else if (name.trim().length < 3) errors.push('Ad Soyad en az 3 karakter olmalıdır.');

    const email = (document.getElementById('fieldEmail') || {}).value || '';
    if (!email.trim()) errors.push('E-posta adresi boş bırakılamaz.');
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim())) errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');

    const phone = (document.getElementById('fieldPhone') || {}).value || '';
    const phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!phoneClean) errors.push('Telefon numarası boş bırakılamaz.');
    else if (!/^\d{10,11}$/.test(phoneClean)) errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');

    const subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) errors.push('Konu boş bırakılamaz.');

    const message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) errors.push('Mesaj boş bırakılamaz.');
    else if (message.trim().length < 20) errors.push('Mesaj en az 20 karakter olmalıdır.');

    const genderChecked = Array.from(document.querySelectorAll('input[name="gender"]')).some(r => r.checked);
    if (!genderChecked) errors.push('Cinsiyet seçiniz.');

    const education = (document.getElementById('fieldEducation') || {}).value || '';
    if (!education) errors.push('Eğitim düzeyi seçiniz.');

    const interests = document.querySelectorAll('input[name="interests[]"]:checked');
    if (interests.length === 0) errors.push('En az bir ilgi alanı seçiniz.');

    const birthdate = (document.getElementById('fieldBirthdate') || {}).value || '';
    if (!birthdate) errors.push('Doğum tarihi giriniz.');

    const box = document.getElementById('jsErrorBox');
    const list = document.getElementById('jsErrorList');
    const successBox = document.getElementById('jsSuccessBox');

    if (errors.length > 0) {
        list.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        box.style.display = 'block';
        if (successBox) successBox.style.display = 'none';
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        box.style.display = 'none';
        if (successBox) {
            successBox.style.display = 'block';
            successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}

/* ============================================================
   Login Page – Client-side Validation
   ============================================================ */
function validateLogin() {
    const emailInput = document.getElementById('loginEmail');
    const passInput  = document.getElementById('loginPass');
    let valid = true;

    if (!emailInput || !passInput) return true;

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailErr = document.getElementById('emailError');
    if (!emailInput.value.trim()) {
        if (emailErr) { emailErr.textContent = 'E-posta boş bırakılamaz.'; emailErr.style.display = 'block'; }
        valid = false;
    } else if (!emailRegex.test(emailInput.value.trim())) {
        if (emailErr) { emailErr.textContent = 'Geçerli bir e-posta adresi giriniz.'; emailErr.style.display = 'block'; }
        valid = false;
    } else {
        if (emailErr) emailErr.style.display = 'none';
    }

    const passErr = document.getElementById('passError');
    if (!passInput.value.trim()) {
        if (passErr) { passErr.textContent = 'Şifre boş bırakılamaz.'; passErr.style.display = 'block'; }
        valid = false;
    } else {
        if (passErr) passErr.style.display = 'none';
    }

    return valid;
}

/* ============================================================
   Theme Switcher
   ============================================================ */
function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('portfolio-theme', theme);
}

(function initTheme() {
    const savedTheme = localStorage.getItem('portfolio-theme');
    if (savedTheme) {
        setTheme(savedTheme);
    }
})();

/* ============================================================
   Custom Cursor Glow Effect
   ============================================================ */
(function initCursorGlow() {
    const glow = document.createElement('div');
    glow.id = 'cursor-glow';
    document.body.appendChild(glow);

    document.addEventListener('mousemove', (e) => {
        glow.style.left = e.clientX + 'px';
        glow.style.top = e.clientY + 'px';
    });

    document.addEventListener('mousedown', () => {
        glow.style.width = '300px';
        glow.style.height = '300px';
    });

    document.addEventListener('mouseup', () => {
        glow.style.width = '400px';
        glow.style.height = '400px';
    });
})();

/* ============================================================
   Typing Animation (hero #typingText)
   ============================================================ */
(function initTyping() {
    const el = document.getElementById('typingText');
    if (!el) return;

    const phrases = JSON.parse(el.dataset.phrases || '[]');
    if (!phrases.length) return;

    let phraseIdx = 0, charIdx = 0, deleting = false;

    function tick() {
        const phrase = phrases[phraseIdx];
        if (!deleting) {
            el.textContent = phrase.slice(0, ++charIdx);
            if (charIdx === phrase.length) {
                deleting = true;
                return setTimeout(tick, 1800);
            }
            setTimeout(tick, 75);
        } else {
            el.textContent = phrase.slice(0, --charIdx);
            if (charIdx === 0) {
                deleting = false;
                phraseIdx = (phraseIdx + 1) % phrases.length;
            }
            setTimeout(tick, 40);
        }
    }
    setTimeout(tick, 500);
})();

/* ============================================================
   Toast Notification
   ============================================================ */
function showToast(message, type) {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const colors = {
        success: 'rgba(16,185,129,0.9)',
        error:   'rgba(239,68,68,0.9)',
        info:    'rgba(0,229,255,0.9)'
    };

    const toast = document.createElement('div');
    toast.innerHTML = message;
    toast.style.cssText = `
        background:${colors[type] || colors.info};
        color:#fff;
        padding:12px 20px;
        border-radius:12px;
        margin-top:10px;
        font-size:0.9rem;
        font-weight:500;
        box-shadow:0 8px 24px rgba(0,0,0,0.4);
        opacity:0;
        transform:translateX(40px);
        transition:all 0.35s cubic-bezier(0.25,0.8,0.25,1);
        backdrop-filter:blur(10px);
        pointer-events:none;
    `;
    container.appendChild(toast);

    requestAnimationFrame(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    });

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(40px)';
        setTimeout(() => toast.remove(), 350);
    }, 3000);
}
