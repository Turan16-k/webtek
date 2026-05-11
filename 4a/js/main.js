/* ============================================================
   main.js
   ============================================================ */

/* ── Aktif navbar linki otomatik işaretleme (3. GÜN) ──────── */
(function () {
    const parts = location.pathname.split('/');
    const current = parts[parts.length - 1] || 'index.html';
    const normalized = current === '' ? 'index.html' : current;
    document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
        link.classList.remove('active');
        const href = link.getAttribute('href');
        if (href && href.split('/').pop() === normalized) link.classList.add('active');
    });
})();

/* ── Scroll-to-top butonu (3. GÜN) ────────────────────────── */
window.addEventListener('scroll', function () {
    const btn = document.getElementById('scrollTopBtn');
    if (!btn) return;
    if (window.scrollY > 300) btn.classList.add('show');
    else btn.classList.remove('show');
});

document.addEventListener('DOMContentLoaded', function () {
    const btn = document.getElementById('scrollTopBtn');
    if (btn) {
        btn.setAttribute('tabindex', '0');
        btn.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });
    }
});

/* ── Native JS İletişim Formu Doğrulama ───────────────────── */
function validateWithJS() {
    const errors = [];

    const name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) errors.push('Ad Soyad boş bırakılamaz.');
    else if (name.trim().length < 3) errors.push('Ad Soyad en az 3 karakter olmalıdır.');

    const email = (document.getElementById('fieldEmail') || {}).value || '';
    if (!email.trim()) errors.push('E-posta adresi boş bırakılamaz.');
    else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.trim()))
        errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');

    const phone = ((document.getElementById('fieldPhone') || {}).value || '').replace(/[\s\-\(\)]/g, '');
    if (!phone) errors.push('Telefon numarası boş bırakılamaz.');
    else if (!/^\d{10,11}$/.test(phone))
        errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');

    const subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) errors.push('Konu boş bırakılamaz.');

    const message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) errors.push('Mesaj boş bırakılamaz.');
    else if (message.trim().length < 20) errors.push('Mesaj en az 20 karakter olmalıdır.');

    const genderChecked = Array.from(document.querySelectorAll('input[name="gender"]')).some(r => r.checked);
    if (!genderChecked) errors.push('Cinsiyet seçiniz.');

    if (!(document.getElementById('fieldEducation') || {}).value)
        errors.push('Eğitim düzeyi seçiniz.');

    if (document.querySelectorAll('input[name="interests[]"]:checked').length === 0)
        errors.push('En az bir ilgi alanı seçiniz.');

    if (!(document.getElementById('fieldBirthdate') || {}).value)
        errors.push('Doğum tarihi giriniz.');

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

/* ── Login Sayfası JS Doğrulama ───────────────────────────── */
function validateLogin() {
    const emailInput = document.getElementById('loginEmail');
    const passInput  = document.getElementById('loginPass');
    let valid = true;

    if (!emailInput || !passInput) return true;

    const emailErr = document.getElementById('emailError');
    if (!emailInput.value.trim()) {
        if (emailErr) { emailErr.textContent = 'E-posta boş bırakılamaz.'; emailErr.style.display = 'block'; }
        valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
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
