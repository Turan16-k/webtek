<?php
/*
 * login-kontrol.php – Giriş doğrulama
 * 10. Gün: Şifre hashleme + CSRF koruması eklendi
 *
 * 8. GÜNDEN BU YANA DEĞİŞENLER:
 * [DÜZELTME] Şifre artık düz metin karşılaştırılmıyor.
 *            password_hash() ile üretilen hash, php/sifre.hash dosyasında
 *            saklanıyor. password_verify() ile kontrol ediliyor.
 * [DÜZELTME] CSRF token kontrolü eklendi. Token eşleşmezse giriş reddediliyor.
 *
 * KALAN SORUNLAR (ilerleyen günlerde):
 * - Veritabanı yok, kullanıcı bilgileri hâlâ hardcoded.
 * - GitHub / LinkedIn linkleri href="#" — güncellenmedi.
 */

session_start();

// ── CSRF KONTROLÜ ─────────────────────────────────────────────────────────────
// hash_equals() — timing attack'a karşı güvenli karşılaştırma
if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    // Token geçersiz: yenile ve login sayfasına yönlendir
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header("Location: ../login.html?hata=csrf");
    exit;
}
// Her kontrolden sonra token yenile (tek kullanımlık mantığı)
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

// ── KULLANICI BİLGİLERİ ───────────────────────────────────────────────────────
$gecerli_email = "b251210043@sakarya.edu.tr";

// 10. GÜN: Şifre artık hashlenmiş hâlde saklanıyor.
// İlk çalıştırmada hash otomatik üretilip sifre.hash dosyasına yazılır.
// Gerçek projede hash veritabanından gelir; burada basitlik için dosya kullanıyoruz.
$hashFile = __DIR__ . '/sifre.hash';
if (!file_exists($hashFile)) {
    file_put_contents($hashFile, password_hash('B251210043', PASSWORD_DEFAULT));
}
$gecerli_hash = trim(file_get_contents($hashFile));

// ── GELEN VERİ ────────────────────────────────────────────────────────────────
$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    header("Location: ../login.html?hata=bos");
    exit;
}

// ── KİMLİK DOĞRULAMA ──────────────────────────────────────────────────────────
// password_verify() — hash ile düz metni güvenli karşılaştırır
if ($email === $gecerli_email && password_verify($password, $gecerli_hash)) {

    $ogrenci_no = explode('@', $email)[0];
    $_SESSION['kullanici']    = $ogrenci_no;
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
            <span class="text-accent"><?= htmlspecialchars($ogrenci_no) ?></span>
        </h2>
        <p class="text-muted mt-2">Giriş başarıyla gerçekleşti.</p>
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
    header("Location: ../login.html?hata=yanlis");
    exit;
}
?>
