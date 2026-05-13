<?php
/*
 * form-islem.php – İletişim formu verilerini işle
 * 8. Gün: Sunucu tarafı validasyon eklendi
 * 10. Gün: CSRF token kontrolü eklendi
 */

session_start();

// ── CSRF KONTROLÜ ─────────────────────────────────────────────────────────────
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    http_response_code(403);
    die('<p style="font-family:sans-serif;padding:2rem;">403 – CSRF token geçersiz. <a href="../iletisim.html">Geri dön</a></p>');
}
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// Gelen POST verilerini al ve temizle
$name      = trim(htmlspecialchars($_POST['name']      ?? ''));
$email     = trim(htmlspecialchars($_POST['email']     ?? ''));
$phone     = trim(htmlspecialchars($_POST['phone']     ?? ''));
$birthdate = trim(htmlspecialchars($_POST['birthdate'] ?? ''));
$gender    = trim(htmlspecialchars($_POST['gender']    ?? ''));
$education = trim(htmlspecialchars($_POST['education'] ?? ''));
$subject   = trim(htmlspecialchars($_POST['subject']   ?? ''));
$message   = trim(htmlspecialchars($_POST['message']   ?? ''));
// Dizi olduğu için ayrı işliyoruz
$interests = isset($_POST['interests']) ? array_map('htmlspecialchars', $_POST['interests']) : [];

// ---- Sunucu Tarafı Validasyon ----
// JS validasyonu geçilse bile burası da kontrol ediyor — çift güvenlik
$hatalar = [];

if (empty($name) || mb_strlen($name) < 3) {
    $hatalar[] = 'Ad Soyad en az 3 karakter olmalıdır.';
}

// filter_var email formatını kontrol eder — regex yazmaktan daha güvenli
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $hatalar[] = 'Geçerli bir e-posta adresi giriniz.';
}

// Telefon: sadece rakam, 10-11 hane
$phoneClean = preg_replace('/[\s\-\(\)]/', '', $phone);
if (empty($phoneClean) || !preg_match('/^\d{10,11}$/', $phoneClean)) {
    $hatalar[] = 'Telefon numarası 10-11 haneli olmalıdır.';
}

if (empty($subject)) {
    $hatalar[] = 'Konu boş bırakılamaz.';
}

if (empty($message) || mb_strlen($message) < 20) {
    $hatalar[] = 'Mesaj en az 20 karakter olmalıdır.';
}

// Cinsiyet sadece belirli değerleri kabul etsin — başka değer gelmesin
$gecerli_cinsiyet = ['erkek', 'kadin', 'diger'];
if (empty($gender) || !in_array($gender, $gecerli_cinsiyet)) {
    $hatalar[] = 'Geçerli bir cinsiyet seçiniz.';
}

// Eğitim alanı boş olmamalı
$gecerli_egitim = ['ilkokul', 'ortaokul', 'lise', 'lisans', 'yukseklisans', 'doktora'];
if (empty($education) || !in_array($education, $gecerli_egitim)) {
    $hatalar[] = 'Geçerli bir eğitim düzeyi seçiniz.';
}

// En az bir ilgi alanı seçili olmalı
if (empty($interests)) {
    $hatalar[] = 'En az bir ilgi alanı seçiniz.';
}

if (empty($birthdate)) {
    $hatalar[] = 'Doğum tarihi giriniz.';
}

// Hata varsa kullanıcıya göster, yoksa formu göster
if (!empty($hatalar)) {
    // Sunucu tarafı validasyon başarısız — hataları listele
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Hatası</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa; padding-top:80px;">
    <div class="container" style="max-width:700px;">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-x-circle-fill text-danger" style="font-size:3rem;"></i>
                <h2 class="fw-bold mt-3">Sunucu Doğrulama Hatası</h2>
                <!-- Bu mesaj normalde kullanıcı JS'i kapatırsa çıkar -->
                <p class="text-muted">Lütfen aşağıdaki hataları düzeltin.</p>
            </div>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($hatalar as $hata): ?>
                        <li><?= $hata ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="text-center mt-4">
                <a href="../iletisim.html" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    exit; // Hata varsa devam etme
}

// Validasyon geçildi — formu göster
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sonucu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa; padding-top:80px;">

    <div class="container" style="max-width:700px;">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                <h2 class="fw-bold mt-3 text-primary">Form Alındı!</h2>
                <p class="text-muted">Gönderilen veriler aşağıda listelenmiştir.</p>
            </div>

            <table class="table table-bordered result-table">
                <tbody>
                    <!-- Boş gelirse "Boş" yaz — null coalescing operatörü ?> -->
                    <tr><th>Ad Soyad</th><td><?= $name ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>E-posta</th><td><?= $email ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Telefon</th><td><?= $phone ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Doğum Tarihi</th><td><?= $birthdate ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Cinsiyet</th><td><?= $gender ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Eğitim</th><td><?= $education ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>İlgi Alanları</th><td><?= $interests ? implode(', ', $interests) : '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Konu</th><td><?= $subject ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <!-- nl2br: mesajdaki satır sonlarını <br> yapar -->
                    <tr><th>Mesaj</th><td><?= nl2br($message) ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="../iletisim.html" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>
        </div>
    </div>

</body>
</html>
