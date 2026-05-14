/* ============================================================
   main.js – İletişim Formu ve Giriş Sayfası Doğrulamaları
   Öğrenci: Abdurrahman Turan Özcan | B251210043
   Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi

   Bu dosyada iki ayrı fonksiyon var:
     1) validateWithJS()  → İletişim formunu saf JavaScript ile kontrol eder
     2) validateLogin()   → Login sayfasını saf JavaScript ile kontrol eder
   Vue.js tarafındaki doğrulama ise doğrudan iletisim.html içinde yazıldı
   çünkü Vue'nun reaktif veri sistemi HTML'le beraber çalışıyor.
   ============================================================ */


/* ============================================================
   FONKSİYON 1: validateWithJS()
   İletişim formundaki tüm alanları Vanilla (saf) JavaScript ile kontrol eder.
   "Vanilla JS" demek framework kullanmadan yazmak demek — sadece tarayıcının
   kendi sunduğu API'leri kullanıyoruz.
   ============================================================ */
function validateWithJS() {

    /* errors dizisine hata mesajlarını push ediyoruz.
       Dizi boş kalırsa form geçerli, doluysa hata var demek. */
    const errors = [];

    /* --- AD SOYAD KONTROLÜ ---
       getElementById ile sayfadaki input elementini seçiyorum.
       .value ile içindeki metni alıyorum.
       || '' kısmı: eğer element bulunamazsa undefined yerine boş string döner,
       böylece .trim() hata vermez. */
    const name = (document.getElementById('fieldName') || {}).value || '';
    if (!name.trim()) {
        /* trim() baştaki ve sondaki boşlukları siler — sadece boşluk girilip
           girilmediğini kontrol etmek için önemli */
        errors.push('Ad Soyad boş bırakılamaz.');
    } else if (name.trim().length < 3) {
        /* length ile karakter sayısını alıyorum — 3'ten az harf olmamalı */
        errors.push('Ad Soyad en az 3 karakter olmalıdır.');
    }

    /* --- E-POSTA KONTROLÜ ---
       Regex (düzenli ifade) ile e-posta formatını kontrol ediyorum.
       /.../ içindeki ifadeyi öğrenmek başta zor geldi ama mantığını anlayınca
       çok işe yarıyor. Açıklama:
         ^        → metnin başı
         [^\s@]+  → boşluk ve @ hariç bir veya daha fazla karakter (@'dan önceki kısım)
         @        → tam olarak @ işareti
         [^\s@]+  → domain adı kısmı
         \.       → nokta (ters eğik çizgi zorunlu çünkü . regex'te "herhangi karakter" demek)
         [^\s@]+  → .com, .net gibi uzantı
         $        → metnin sonu */
    const email = (document.getElementById('fieldEmail') || {}).value || '';
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!email.trim()) {
        errors.push('E-posta adresi boş bırakılamaz.');
    } else if (!emailRegex.test(email.trim())) {
        /* .test() fonksiyonu regex'in string'e uyup uymadığını true/false döner */
        errors.push('Geçerli bir e-posta adresi giriniz. (örn: isim@domain.com)');
    }

    /* --- TELEFON KONTROLÜ ---
       Kullanıcı parantez, tire veya boşluk girebilir, önce bunları temizliyorum.
       replace() ile ilgili karakterleri siliyorum.
       Regex açıklaması: [\s\-\(\)] → boşluk, tire, açık ve kapalı parantez
       /g flag'i → tüm eşleşmeleri değiştir (sadece ilkini değil) */
    const phone = (document.getElementById('fieldPhone') || {}).value || '';
    const phoneClean = phone.replace(/[\s\-\(\)]/g, '');
    if (!phoneClean) {
        errors.push('Telefon numarası boş bırakılamaz.');
    } else if (!/^\d{10,11}$/.test(phoneClean)) {
        /* \d → rakam, {10,11} → 10 veya 11 hane olmalı
           Türk telefon numaraları 05XX ile başladığında 11 hane oluyor */
        errors.push('Telefon numarası sadece rakamlardan oluşmalı ve 10-11 haneli olmalıdır.');
    }

    /* --- KONU KONTROLÜ ---
       Basit boşluk kontrolü — konu alanı doldurulmuş mu? */
    const subject = (document.getElementById('fieldSubject') || {}).value || '';
    if (!subject.trim()) {
        errors.push('Konu boş bırakılamaz.');
    }

    /* --- MESAJ KONTROLÜ ---
       Minimum 20 karakter — çok kısa mesaj gönderimini engelliyorum */
    const message = (document.getElementById('fieldMessage') || {}).value || '';
    if (!message.trim()) {
        errors.push('Mesaj boş bırakılamaz.');
    } else if (message.trim().length < 20) {
        errors.push('Mesaj en az 20 karakter olmalıdır.');
    }

    /* --- CİNSİYET KONTROLÜ (Radio Button) ---
       Radio butonlar için querySelectorAll kullanıyorum çünkü aynı name'e
       sahip birden fazla input var. Array.from ile gerçek diziye çevirip
       .some() ile herhangi biri seçilmiş mi diye bakıyorum.
       .some() → dizizdeki herhangi bir eleman koşulu sağlıyorsa true döner */
    const genderInputs = document.querySelectorAll('input[name="gender"]');
    const genderChecked = Array.from(genderInputs).some(r => r.checked);
    if (!genderChecked) {
        errors.push('Cinsiyet seçiniz.');
    }

    /* --- EĞİTİM DÜZEYİ KONTROLÜ (Select/Dropdown) ---
       Select elementinin value'su "" ise kullanıcı seçim yapmamış demek
       (ilk boş option seçili kalıyor) */
    const education = (document.getElementById('fieldEducation') || {}).value || '';
    if (!education) {
        errors.push('Eğitim düzeyi seçiniz.');
    }

    /* --- İLGİ ALANLARI KONTROLÜ (Checkbox Grubu) ---
       Birden fazla checkbox olduğu için name="interests[]" ile hepsini seçiyorum.
       :checked pseudo-selector sadece işaretlenmiş olanları getirir.
       .length ile kaç tane seçildiğini kontrol ediyorum. */
    const interests = document.querySelectorAll('input[name="interests[]"]:checked');
    if (interests.length === 0) {
        errors.push('En az bir ilgi alanı seçiniz.');
    }

    /* --- DOĞUM TARİHİ KONTROLÜ ---
       type="date" olan input'un value'su YYYY-MM-DD formatında gelir.
       Boş mu diye bakıyorum. */
    const birthdate = (document.getElementById('fieldBirthdate') || {}).value || '';
    if (!birthdate) {
        errors.push('Doğum tarihi giriniz.');
    }

    /* --- KVKK ONAY KONTROLÜ ---
       Zorunlu checkbox — .checked ile işaretli olup olmadığını öğreniyorum.
       Kişisel verilerin işlenmesi için bu onayı almak yasal zorunluluk. */
    const kvkk = document.getElementById('fieldKvkk');
    if (!kvkk || !kvkk.checked) {
        errors.push('KVKK onay kutusunu işaretlemeniz zorunludur.');
    }

    /* --- SONUÇLARI EKRANA YAZDIR ---
       Hata kutusu ve başarı kutusunu DOM'dan alıyorum.
       Sonra hata var mı yok mu duruma göre görünürlüklerini değiştiriyorum.
       style.display = 'block' → görünür yap
       style.display = 'none'  → gizle
       innerHTML ile hata listesini dinamik olarak oluşturuyorum. */
    const box        = document.getElementById('jsErrorBox');
    const list       = document.getElementById('jsErrorList');
    const successBox = document.getElementById('jsSuccessBox');

    if (errors.length > 0) {
        /* map ile her hatayı <li> elementine dönüştürüyorum,
           join('') ile bunları tek bir string'e birleştiriyorum */
        list.innerHTML = errors.map(e => `<li>${e}</li>`).join('');
        box.style.display = 'block';
        if (successBox) successBox.style.display = 'none';

        /* scrollIntoView ile kullanıcıyı hata kutusuna götürüyorum,
           'smooth' ile animasyonlu kaydırma yapıyorum — kullanıcı deneyimi için önemli */
        box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        /* Hata yoksa başarı mesajı göster */
        box.style.display = 'none';
        if (successBox) {
            successBox.style.display = 'block';
            successBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    }
}


/* ============================================================
   FONKSİYON 2: validateLogin()
   Login sayfasındaki e-posta ve şifre alanlarını kontrol eder.
   Bu fonksiyon <form> submit'ini engellememesi için true/false döndürüyor:
     true  → form gönderilsin (onclick="return validateLogin()" sayesinde)
     false → form gönderilmesin, hata göster
   ============================================================ */
function validateLogin() {

    /* İki input alanını ve hata mesajı div'lerini seçiyorum */
    const emailInput = document.getElementById('loginEmail');
    const passInput  = document.getElementById('loginPass');

    /* Elementler yoksa (yani bu fonksiyon login sayfası dışında çağrıldıysa)
       true döndürüp devam et — hata vermemesi için */
    if (!emailInput || !passInput) return true;

    /* valid değişkeni ile genel doğrulama durumunu takip ediyorum.
       Herhangi bir hata bulununca false yapıyorum. */
    let valid = true;

    /* --- E-POSTA KONTROLÜ ---
       Aynı regex'i kullanıyorum — tutarlılık önemli */
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const emailErr   = document.getElementById('emailError');

    if (!emailInput.value.trim()) {
        /* Boş girilmişse hata göster */
        if (emailErr) {
            emailErr.textContent = 'E-posta boş bırakılamaz.';
            emailErr.style.display = 'block';
        }
        valid = false;
    } else if (!emailRegex.test(emailInput.value.trim())) {
        /* Yanlış formatta girilmişse hata göster */
        if (emailErr) {
            emailErr.textContent = 'Geçerli bir e-posta adresi giriniz.';
            emailErr.style.display = 'block';
        }
        valid = false;
    } else {
        /* Hata yoksa mesajı gizle */
        if (emailErr) emailErr.style.display = 'none';
    }

    /* --- ŞİFRE BOŞLUK KONTROLÜ ---
       Şifrenin sadece boş olup olmadığını kontrol ediyorum.
       PHP tarafı doğru/yanlış kontrolünü yapacak — burada sadece boş mu diye bakıyorum. */
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

    /* true ise form submit olur ve php/login.php'ye gider,
       false ise form submit olmaz, kullanıcı sayfada kalır */
    return valid;
}
