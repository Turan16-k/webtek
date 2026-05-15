/* ============================================================
   main.js – İletişim Formu ve Giriş Sayfası Doğrulamaları
   Öğrenci: Abdurrahman Turan Özcan | B251210043
   Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi

   İçerik:
     1) validateWithJS() : İletişim formu JavaScript doğrulama işlemleri
     2) validateLogin()  : Giriş sayfası doğrulama işlemleri
   ============================================================ */


/* ============================================================
   FONKSİYON 1: validateWithJS()
   İletişim formu için Vanilla JS ile yazılmış doğrulama mekanizması.
   ============================================================ */
function validateWithJS() {

    /* Doğrulama hatalarının toplanacağı dizi. Eleman varsa form geçersiz sayılır. */
    const errors = [];

    /* --- AD SOYAD KONTROLÜ ---
       Null referans hatalarını önlemek amacıyla boş nesne kontrolü yapılmıştır. */
    const name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) {
        /* Sadece boşluklardan oluşan girdileri engellemek için trim() kullanılmıştır. */
        errors.push('Ad Soyad boş bırakılamaz.');
    } else if (name.trim().length < 3) {
        /* Minimum 3 karakter sınırı uygulanmıştır. */
        errors.push('Ad Soyad en az 3 karakter olmalıdır.');
    }

    /* --- E-POSTA KONTROLÜ ---
       Geçerli bir e-posta adresi formatını doğrulamak için regex deseni uygulanmıştır.
       Format: kullaniciadi@domain.uzanti */
    const email = (document.getElementById('fieldEmail') || {}).value || '';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
        errors.push('E-posta adresi boş bırakılamaz.');
    } else if (!emailRegex.test(email.trim())) {
        /* Girilen değerin regex desenine uygunluğu test edilir. */
        errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');
    }

    /* --- TELEFON KONTROLÜ ---
       Kullanıcı girişindeki olası parantez ve tire işaretleri temizlenir. */
    const phone = (document.getElementById('fieldPhone') || {}).value || '';
    const phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!phoneClean) {
        errors.push('Telefon numarası boş bırakılamaz.');
    } else if (!/^\d{10,11}$/.test(phoneClean)) {
        /* Telefon numarasının sadece rakamlardan oluşması ve 10-11 hane uzunluğunda olması beklenir. */
        errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');
    }

    /* --- KONU KONTROLÜ ---
       Konu alanının boş geçilmemesi sağlanır. */
    const subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) {
        errors.push('Konu boş bırakılamaz.');
    }

    /* --- MESAJ KONTROLÜ ---
       Mesaj içeriği için minimum uzunluk kısıtlaması uygulanır. */
    const message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) {
        errors.push('Mesaj boş bırakılamaz.');
    } else if (message.trim().length < 20) {
        errors.push('Mesaj en az 20 karakter olmalıdır.');
    }

    /* --- CİNSİYET KONTROLÜ (Radio Button) ---
       Seçili olan radio butonlarını doğrular. */
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const genderChecked = Array.from(genderInputs).some(r => r.checked);
    if (!genderChecked) {
        errors.push('Cinsiyet seçiniz.');
    }

    /* --- EĞİTİM DÜZEYİ KONTROLÜ (Select/Dropdown) ---
       Geçerli bir eğitim düzeyi seçeneğinin tercih edilip edilmediğini kontrol eder. */
    const education = (document.getElementById('fieldEducation') || {}).value || '';
    if (!education) {
        errors.push('Eğitim düzeyi seçiniz.');
    }

    /* --- İLGİ ALANLARI KONTROLÜ (Checkbox Grubu) ---
       En az bir adet ilgi alanının işaretlendiğinden emin olunur. */
    const interests = document.querySelectorAll('input[name="interests[]"]:checked');
    if (interests.length === 0) {
        errors.push('En az bir ilgi alanı seçiniz.');
    }

    /* --- DOĞUM TARİHİ KONTROLÜ ---
       Tarih seçimi alanının doğrulanması (YYYY-MM-DD formatında beklenir). */
    const birthdate = (document.getElementById('fieldBirthdate') || {}).value || '';
    if (!birthdate) {
        errors.push('Doğum tarihi giriniz.');
    }

    /* --- KVKK ONAY KONTROLÜ ---
       Zorunlu tutulan KVKK onay kutusunun işaretlenip işaretlenmediğini denetler. */
    const kvkk = document.getElementById('fieldKvkk');
    if (!kvkk || !kvkk.checked) {
        errors.push('KVKK onay kutusunu işaretlemeniz zorunludur.');
    }

    /* --- SONUÇLARI EKRANA YAZDIR ---
       Hata ve başarı mesajlarının ilgili DOM elementlerinde gösterimi sağlanır. */
    const box        = document.getElementById('jsErrorBox');
    const list       = document.getElementById('jsErrorList');
    const successBox = document.getElementById('jsSuccessBox');

    if (errors.length > 0) {
        /* Hataları liste elemanları (<li>) şeklinde biçimlendirip ekrana yansıtır. */
        list.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        box.style.display = 'block';
        if (successBox) successBox.style.display = 'none';

        /* Hata kutusuna animasyonlu bir şekilde otomatik kaydırma (smooth scroll) uygulanır. */
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        /* Hata yoksa başarı kutusu gösterilir ve ilgili alana kaydırılır. */
        box.style.display = 'none';
        if (successBox) {
            successBox.style.display = 'block';
            successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}


/* ============================================================
   FONKSİYON 2: validateLogin()
   Giriş formu doğrulaması.
   true dönerse form PHP'ye yönlendirilir, false dönerse iptal edilir.
   ============================================================ */
function validateLogin() {

    /* Girdi ve hata DOM elemanlarının seçimi */
    const emailInput = document.getElementById('loginEmail');
    const passInput  = document.getElementById('loginPass');

    /* Giriş sayfası dışında tetiklenmesi durumunda hataları önlemek adına kontrol */
    if (!emailInput || !passInput) return true;

    /* Genel doğrulama durumu */
    let valid = true;

    /* --- E-POSTA KONTROLÜ ---
       E-posta formatının doğrulanması */
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailErr   = document.getElementById('emailError');

    if (!emailInput.value.trim()) {
        /* Boş alan kontrolü */
        if (emailErr) {
            emailErr.textContent = 'E-posta boş bırakılamaz.';
            emailErr.style.display = 'block';
        }
        valid = false;
    } else if (!emailRegex.test(emailInput.value.trim())) {
        /* Geçersiz format kontrolü */
        if (emailErr) {
            emailErr.textContent = 'Geçerli bir e-posta adresi giriniz.';
            emailErr.style.display = 'block';
        }
        valid = false;
    } else {
        /* Hata bulunmaması durumunda mesaj gizlenir */
        if (emailErr) emailErr.style.display = 'none';
    }

    /* --- ŞİFRE BOŞLUK KONTROLÜ ---
       Detaylı kimlik doğrulaması PHP tarafında yapılacaktır. */
    const passErr = document.getElementById('passError');
    if (!passInput.value.trim()) {
        if (passErr) {
            passErr.textContent = 'Şifre boş bırakılamaz.';
            passErr.style.display = 'block';
        }
        valid = false;
    } else {
        if (passErr) passErr.style.display = 'none';
    }

    /* true dönüşü form gönderimini onaylar, false form gönderimini engeller. */
    return valid;
}


/* ============================================================
   FONKSİYON 3: animateSkillBars()
   cv.html'deki yetenek seviyesi ilerleme çubuklarını (progress bars) canlandırır.

   Tarayıcının geçiş (transition) animasyonunu tetikleyebilmesi için
   başlangıç değeri 0'a çekilip kısa bir gecikme (setTimeout) sonrası
   hedef değere ayarlanmaktadır.
   ============================================================ */
function animateSkillBars() {
    const fills = document.querySelectorAll('.skill-fill');

    fills.forEach(function(fill) {
        /* Hedef genişlik değeri (örn: "65%") kaydedilir. */
        const targetWidth = fill.style.width;

        /* Başlangıç için genişlik sıfıra eşitlenir. */
        fill.style.width = '0';

        /* Kısa bir süre sonra hedef genişlik değerine ayarlanarak geçiş efekti uygulanır. */
        setTimeout(function() {
            fill.style.width = targetWidth;
        }, 100);
    });
}

/* Sayfa yüklendiğinde animasyonu başlatmak üzere DOMContentLoaded olayını dinler. */
document.addEventListener('DOMContentLoaded', animateSkillBars);
