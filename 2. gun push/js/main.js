/* ============================================================
   main.js – Utilities & Form Validation
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {

    /* ---- Back to Top ---- */
    var backBtn = document.getElementById('backToTop');
    if (backBtn) {
        window.addEventListener('scroll', function () {
            backBtn.style.display = window.scrollY > 300 ? 'flex' : 'none';
        });
        backBtn.addEventListener('click', function () {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ---- Mesaj Karakter Sayacı ---- */
    var msgInput = document.getElementById('fieldMessage');
    var counter  = document.getElementById('msgCounter');
    if (msgInput && counter) {
        msgInput.addEventListener('input', function () {
            var len = this.value.length;
            counter.textContent = len + ' karakter (en az 20)';
            counter.className = 'form-text text-end fw-semibold ' +
                                (len >= 20 ? 'text-success' : 'text-danger');
        });
    }

    /* ---- Skill Bar Scroll Animasyonu (CV sayfası) ---- */
    var fills = document.querySelectorAll('.skill-fill');
    if (fills.length > 0) {
        fills.forEach(function (el) {
            el.dataset.width = el.style.width;
            el.style.width   = '0';
        });
        var skillObs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.width = entry.target.dataset.width;
                    skillObs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        fills.forEach(function (el) { skillObs.observe(el); });
    }

});

/* ============================================================
   İletişim Formu – Vanilla JS Validasyonu
   ============================================================ */
function validateWithJS() {
    var errors = [];

    var name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) {
        errors.push('Ad Soyad boş bırakılamaz.');
    } else if (name.trim().length < 3) {
        errors.push('Ad Soyad en az 3 karakter olmalıdır.');
    }

    var email      = (document.getElementById('fieldEmail') || {}).value || '';
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
        errors.push('E-posta adresi boş bırakılamaz.');
    } else if (!emailRegex.test(email.trim())) {
        errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');
    }

    var phone      = (document.getElementById('fieldPhone') || {}).value || '';
    var phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!phoneClean) {
        errors.push('Telefon numarası boş bırakılamaz.');
    } else if (!/^\d{10,11}$/.test(phoneClean)) {
        errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');
    }

    var subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) {
        errors.push('Konu boş bırakılamaz.');
    }

    var message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) {
        errors.push('Mesaj boş bırakılamaz.');
    } else if (message.trim().length < 20) {
        errors.push('Mesaj en az 20 karakter olmalıdır.');
    }

    var genderChecked = Array.from(
        document.querySelectorAll('input[name="gender"]')
    ).some(function (r) { return r.checked; });
    if (!genderChecked) {
        errors.push('Cinsiyet seçiniz.');
    }

    var education = (document.getElementById('fieldEducation') || {}).value || '';
    if (!education) {
        errors.push('Eğitim düzeyi seçiniz.');
    }

    if (document.querySelectorAll('input[name="interests[]"]:checked').length === 0) {
        errors.push('En az bir ilgi alanı seçiniz.');
    }

    var birthdate = (document.getElementById('fieldBirthdate') || {}).value || '';
    if (!birthdate) {
        errors.push('Doğum tarihi giriniz.');
    }

    var kvkk = document.getElementById('fieldKvkk');
    if (kvkk && !kvkk.checked) {
        errors.push('KVKK onay kutusunu işaretlemeniz zorunludur.');
    }

    var box        = document.getElementById('jsErrorBox');
    var list       = document.getElementById('jsErrorList');
    var successBox = document.getElementById('jsSuccessBox');

    if (errors.length > 0) {
        list.innerHTML = errors.map(function (e) { return '<li>' + e + '</li>'; }).join('');
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
   Login Sayfası – Client-side JS Validasyonu
   ============================================================ */
function validateLogin() {
    var emailInput = document.getElementById('loginEmail');
    var passInput  = document.getElementById('loginPass');
    if (!emailInput || !passInput) return true;

    var valid      = true;
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var emailErr   = document.getElementById('emailError');
    var passErr    = document.getElementById('passError');

    if (!emailInput.value.trim()) {
        if (emailErr) { emailErr.textContent = 'E-posta boş bırakılamaz.'; emailErr.style.display = 'block'; }
        valid = false;
    } else if (!emailRegex.test(emailInput.value.trim())) {
        if (emailErr) { emailErr.textContent = 'Geçerli bir e-posta adresi giriniz.'; emailErr.style.display = 'block'; }
        valid = false;
    } else {
        if (emailErr) emailErr.style.display = 'none';
    }

    if (!passInput.value.trim()) {
        if (passErr) { passErr.textContent = 'Şifre boş bırakılamaz.'; passErr.style.display = 'block'; }
        valid = false;
    } else {
        if (passErr) passErr.style.display = 'none';
    }

    return valid;
}
