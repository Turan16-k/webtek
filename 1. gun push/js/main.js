/* ============================================================
   main.js – Vanilla JavaScript Contact Form Validation
   ============================================================ */

function validateWithJS() {
    const errors = [];

    // Ad Soyad
    const name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) {
        errors.push('Ad Soyad boş bırakılamaz.');
    } else if (name.trim().length < 3) {
        errors.push('Ad Soyad en az 3 karakter olmalıdır.');
    }

    // E-posta
    const email = (document.getElementById('fieldEmail') || {}).value || '';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
        errors.push('E-posta adresi boş bırakılamaz.');
    } else if (!emailRegex.test(email.trim())) {
        errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');
    }

    // Telefon
    const phone = (document.getElementById('fieldPhone') || {}).value || '';
    const phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!phoneClean) {
        errors.push('Telefon numarası boş bırakılamaz.');
    } else if (!/^\d{10,11}$/.test(phoneClean)) {
        errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');
    }

    // Konu
    const subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) {
        errors.push('Konu boş bırakılamaz.');
    }

    // Mesaj
    const message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) {
        errors.push('Mesaj boş bırakılamaz.');
    } else if (message.trim().length < 20) {
        errors.push('Mesaj en az 20 karakter olmalıdır.');
    }

    // Cinsiyet (radio)
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const genderChecked = Array.from(genderInputs).some(r => r.checked);
    if (!genderChecked) {
        errors.push('Cinsiyet seçiniz.');
    }

    // Eğitim (select)
    const education = (document.getElementById('fieldEducation') || {}).value || '';
    if (!education) {
        errors.push('Eğitim düzeyi seçiniz.');
    }

    // İlgi Alanları (checkbox)
    const interests = document.querySelectorAll('input[name="interests[]"]:checked');
    if (interests.length === 0) {
        errors.push('En az bir ilgi alanı seçiniz.');
    }

    // Doğum Tarihi
    const birthdate = (document.getElementById('fieldBirthdate') || {}).value || '';
    if (!birthdate) {
        errors.push('Doğum tarihi giriniz.');
    }

    // Sonuçları Göster
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
   Login Page – Client-side JS Validation
   ============================================================ */
function validateLogin() {
    const emailInput = document.getElementById('loginEmail');
    const passInput  = document.getElementById('loginPass');
    let valid = true;

    if (!emailInput || !passInput) return true;

    // E-posta format kontrolü
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

    // Şifre boş kontrolü
    const passErr = document.getElementById('passError');
    if (!passInput.value.trim()) {
        if (passErr) { passErr.textContent = 'Şifre boş bırakılamaz.'; passErr.style.display = 'block'; }
        valid = false;
    } else {
        if (passErr) passErr.style.display = 'none';
    }

    return valid;
}
