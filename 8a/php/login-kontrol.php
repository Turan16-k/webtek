<?php
/*
 * login-kontrol.php – Giriş doğrulama
 * 8. Gün: session_start() eklendi, hata yorumları yazıldı
 *
 * BİLİNEN SORUNLAR (henüz düzeltilmedi, ilerleyen günlerde yapılacak):
 * - Şifre düz metin olarak karşılaştırılıyor. Gerçek bir sistemde
 *   password_hash() ile hashlenmiş şifre veritabanında saklanmalı,
 *   password_verify() ile kontrol edilmeli. Derste öğrendik ama
 *   burada basit tutmak için şimdilik böyle bıraktım.
 * - Veritabanı yok, kullanıcı bilgileri hardcoded. Gerçek projede
 *   PDO + MySQL kullanılacak.
 * - CSRF token yok. Form başkasının sitesinden submit edilebilir.
 *   Bunu ileride öğreneceğiz herhalde...
 */

// 8. GÜN EKLEMESİ: session_start() olmadan oturum açılamaz!
// Bunu unutmuştum, giriş yapılsa bile sonraki sayfalarda kullanıcı
// kim bilmiyordu. Şimdi session'a öğrenci no kaydediyoruz.
session_start();

$gecerli_email = "b251210043@sakarya.edu.tr";
$gecerli_sifre = "B251210043";

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

// Boş alan kontrolü
if (empty($email) || empty($password)) {
    header("Location: ../login.html?hata=bos");
    exit;
}

// Kimlik doğrulama
// NOT: Normalde password_verify($password, $hash_from_db) kullanılmalı
// Şimdilik öğrenme aşamasında olduğumuz için düz karşılaştırma yapıyoruz
if ($email === $gecerli_email && $password === $gecerli_sifre) {

    // Başarılı giriş — session'a kullanıcı bilgilerini kaydet
    $ogrenci_no = explode('@', $email)[0];
    $_SESSION['kullanici'] = $ogrenci_no;
    $_SESSION['giris_zamani'] = time();

?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoşgeldiniz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body class="login-page">
    <div class="login-card text-center">
        <i class="bi bi-patch-check-fill text-success" style="font-size:4rem;"></i>
        <h2 class="fw-bold mt-3 text-primary">
            Hoşgeldiniz <br>
            <!-- htmlspecialchars XSS saldırısını önler — bunu derste öğrendik -->
            <span class="text-accent"><?= htmlspecialchars($ogrenci_no) ?></span>
        </h2>
        <p class="text-muted mt-2">Giriş başarıyla gerçekleşti.</p>
        <!-- 8. Gün: session var, kullanıcı artık tanınıyor -->
        <p class="text-muted small">Oturum ID: <?= htmlspecialchars(session_id()) ?></p>
        <a href="../index.html" class="btn btn-primary btn-lg mt-4 w-100">
            <i class="bi bi-house me-2"></i>Ana Sayfaya Git
        </a>
        <a href="../php/cikis.php" class="btn btn-outline-secondary mt-2 w-100">
            <i class="bi bi-box-arrow-right me-2"></i>Çıkış Yap
        </a>
    </div>
</body>
</html>
<?php
} else {
    // Hatalı giriş — login sayfasına yönlendir
    // JS orada URL parametresini okuyup hata mesajı gösterecek
    header("Location: ../login.html?hata=yanlis");
    exit;
}
?>
