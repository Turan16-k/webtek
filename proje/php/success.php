<?php
// ============================================================
// success.php – Başarılı Giriş Sonrası Hoşgeldiniz Sayfası
// Öğrenci: Abdurrahman Turan Özcan | B251210043
// Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi
//
// Bu sayfa sadece login.php'nin yönlendirmesiyle açılmalı.
// Direkt URL yazarak bu sayfaya gelinirse oturum yoktur,
// login.html'e geri yönlendiriyorum.
// ============================================================

// session_start() sayfanın en başında olmalı — başka bir şey yazdırılmadan önce.
// Aksi halde "headers already sent" hatası alınır.
session_start();

// ============================================================
// OTURUM KONTROLÜ
// $_SESSION['logged_in'] değişkeni login.php tarafından true yapılmıştı.
// isset() → değişken tanımlı ve null değil mi kontrol eder.
// !== → katı eşitsizlik — hem tip hem değer karşılaştırır.
// Bu kontrol olmazsa doğrudan success.php URL'sine giren herkes sayfayı görebilir.
// ============================================================
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Yetkisiz erişim — login sayfasına hata mesajıyla gönder
    header('Location: ../login.html?hata=yetkisiz');
    exit;
}

// Oturum doğrulandı — öğrenci numarasını güvenli şekilde al
// htmlspecialchars() → SESSION'dan gelen veriyi de XSS'e karşı temizliyorum
$ogrenciNo = htmlspecialchars($_SESSION['ogrenci_no'] ?? 'Öğrenci');

// Giriş zamanını anlık olarak hesaplıyorum
$loginTime = date('d.m.Y H:i:s');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Sayfa başlığına öğrenci numarasını PHP ile dinamik yazıyorum -->
    <title>Hoşgeldiniz <?php echo $ogrenciNo; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- PHP dosyaları php/ klasöründe — CSS yolu bir üst dizinden başlıyor -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Üst navbar — çıkış butonu içeriyor -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../index.html">
            <i class="bi bi-person-badge-fill me-2"></i>A.T. Özcan
        </a>
        <div class="navbar-nav ms-auto">
            <!-- logout.php oturumu kapatıp login'e yönlendirecek -->
            <a class="nav-link" href="logout.php">
                <i class="bi bi-box-arrow-right me-1"></i>Çıkış Yap
            </a>
        </div>
    </div>
</nav>

<!-- login-page sınıfı tam ekran, ortalanmış yapı — style.css'te tanımlı.
     İnline style ile arka planı yeşilimsi gradient yapıyorum (başarı teması). -->
<div class="login-page" style="background: linear-gradient(135deg, #0d3b6e, #198754);">
    <div class="login-card" style="max-width:520px;">

        <!-- BAŞARI İKONU — yuvarlak yeşil çember içinde tik -->
        <div class="text-center mb-4">
            <div style="width:90px; height:90px;
                        background:linear-gradient(135deg,#198754,#20c997);
                        border-radius:50%; display:flex; align-items:center;
                        justify-content:center; margin:0 auto 15px;
                        font-size:2.5rem; color:#fff;
                        box-shadow:0 8px 25px rgba(25,135,84,0.4);">
                <i class="bi bi-check-lg"></i>
            </div>
            <h3 class="fw-bold text-success">Giriş Başarılı!</h3>
        </div>

        <!-- HOŞGELDİNİZ MESAJI
             Ödev gereksinimi: "Hoşgeldiniz [Öğrenci No]" mesajı gösterilmeli.
             $ogrenciNo SESSION'dan geliyor — login.php'de set edilmişti. -->
        <div class="alert alert-success text-center py-4 mb-4"
             style="border-radius:14px; font-size:1.3rem;">
            <i class="bi bi-person-check-fill me-2"></i>
            <strong>Hoşgeldiniz <?php echo $ogrenciNo; ?></strong>
        </div>

        <!-- OTURUM BİLGİLERİ TABLOSU -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered mb-0 result-table">
                <tr>
                    <th><i class="bi bi-person me-2"></i>Öğrenci No</th>
                    <!-- PHP değişkenini echo ile HTML içine yazıyorum -->
                    <td><?php echo $ogrenciNo; ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-clock me-2"></i>Giriş Zamanı</th>
                    <td><?php echo $loginTime; ?></td>
                </tr>
                <tr>
                    <th><i class="bi bi-shield-check me-2"></i>Durum</th>
                    <td><span class="badge bg-success px-3 py-2">Oturum Açık</span></td>
                </tr>
            </table>
        </div>

        <!-- ANA SAYFA / ÇIKIŞ BUTONLARI
             d-flex gap-3 → butonlar yan yana, aralarında boşluk.
             flex-fill → her buton eşit genişlik kaplıyor. -->
        <div class="d-flex gap-3">
            <a href="../index.html" class="btn btn-primary flex-fill">
                <i class="bi bi-house me-2"></i>Ana Sayfa
            </a>
            <a href="logout.php" class="btn btn-outline-danger flex-fill">
                <i class="bi bi-box-arrow-right me-2"></i>Çıkış Yap
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
