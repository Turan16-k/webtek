<?php
// ============================================================
// success.php – Başarılı Giriş Sayfası
// ============================================================

session_start();

// Oturum kontrolü – giriş yapılmamışsa yönlendir
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ../login.html?hata=yetkisiz');
    exit;
}

$ogrenciNo = htmlspecialchars($_SESSION['ogrenci_no'] ?? 'Öğrenci');
$loginTime = date('d.m.Y H:i:s');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoşgeldiniz <?php echo $ogrenciNo; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../index.html"><i class="bi bi-person-badge-fill me-2"></i>A.T. Özcan</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Çıkış Yap</a>
        </div>
    </div>
</nav>

<div class="login-page" style="background: linear-gradient(135deg, #0d3b6e, #198754);">
    <div class="login-card" style="max-width:520px;">

        <!-- Başarı İkonu -->
        <div class="text-center mb-4">
            <div style="width:90px;height:90px;background:linear-gradient(135deg,#198754,#20c997);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 15px;font-size:2.5rem;color:#fff;box-shadow:0 8px 25px rgba(25,135,84,0.4);">
                <i class="bi bi-check-lg"></i>
            </div>
            <h3 class="fw-bold text-success">Giriş Başarılı!</h3>
        </div>

        <!-- Hoşgeldiniz Mesajı -->
        <div class="alert alert-success text-center py-4 mb-4" style="border-radius:14px; font-size:1.3rem;">
            <i class="bi bi-person-check-fill me-2"></i>
            <strong>Hoşgeldiniz <?php echo $ogrenciNo; ?></strong>
        </div>

        <!-- Bilgiler -->
        <div class="table-responsive mb-4">
            <table class="table table-bordered mb-0 result-table">
                <tr>
                    <th><i class="bi bi-person me-2"></i>Öğrenci No</th>
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

        <!-- Butonlar -->
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
