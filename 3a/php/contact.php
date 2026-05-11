<?php
// ============================================================
// contact.php – İletişim Formu Veri İşleyicisi
// Gelen POST verilerini temizleyerek ekrana yazdırır.
// ============================================================

// Yalnızca POST ile gelen istekleri kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../iletisim.html');
    exit;
}

// Veri temizleme fonksiyonu
function clean($value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

// Verileri al ve temizle
$name       = clean($_POST['name']       ?? '');
$email      = clean($_POST['email']      ?? '');
$phone      = clean($_POST['phone']      ?? '');
$subject    = clean($_POST['subject']    ?? '');
$message    = clean($_POST['message']    ?? '');
$gender     = clean($_POST['gender']     ?? '');
$education  = clean($_POST['education']  ?? '');
$birthdate  = clean($_POST['birthdate']  ?? '');
$kvkk       = isset($_POST['kvkk'])      ? 'Evet' : 'Hayır';
$newsletter = isset($_POST['newsletter']) ? 'Evet' : 'Hayır';

// İlgi alanları dizisi
$interests = [];
if (isset($_POST['interests']) && is_array($_POST['interests'])) {
    foreach ($_POST['interests'] as $interest) {
        $interests[] = clean($interest);
    }
}
$interestsStr = !empty($interests) ? implode(', ', $interests) : 'Seçilmedi';

// Dosya yükleme bilgisi
$fileInfo = 'Dosya yüklenmedi';
if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
    $fileInfo = clean($_FILES['file_upload']['name']) . ' (' . round($_FILES['file_upload']['size'] / 1024, 2) . ' KB)';
}

// Gönderim zamanı
$submitTime = date('d.m.Y H:i:s');
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Gönderildi – İletişim</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../index.html"><i class="bi bi-person-badge-fill me-2"></i>A.T. Özcan</a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="../iletisim.html"><i class="bi bi-arrow-left me-1"></i>Geri Dön</a>
        </div>
    </div>
</nav>

<div class="php-page-wrapper py-5">
    <div class="container">

        <!-- Başarı Başlığı -->
        <div class="text-center mb-5">
            <div class="result-icon result-icon-success">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 class="fw-bold text-success">Form Başarıyla Gönderildi!</h2>
            <p class="text-muted">Aşağıda gönderilen tüm form verileri listelenmektedir.</p>
            <small class="text-muted"><i class="bi bi-clock me-1"></i>Gönderim Zamanı: <?php echo $submitTime; ?></small>
        </div>

        <!-- Veri Tablosu -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Gönderilen Form Verileri</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 result-table">
                        <tbody>
                            <tr>
                                <th><i class="bi bi-person me-2"></i>Ad Soyad</th>
                                <td><?php echo $name ?: '<em class="text-muted">Boş</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-envelope me-2"></i>E-Posta</th>
                                <td><?php echo $email ?: '<em class="text-muted">Boş</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-telephone me-2"></i>Telefon</th>
                                <td><?php echo $phone ?: '<em class="text-muted">Boş</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-chat-text me-2"></i>Konu</th>
                                <td><?php echo $subject ?: '<em class="text-muted">Boş</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-gender-ambiguous me-2"></i>Cinsiyet</th>
                                <td><?php echo $gender ?: '<em class="text-muted">Seçilmedi</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-mortarboard me-2"></i>Eğitim Düzeyi</th>
                                <td><?php echo $education ?: '<em class="text-muted">Seçilmedi</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-calendar3 me-2"></i>Doğum Tarihi</th>
                                <td><?php echo $birthdate ?: '<em class="text-muted">Girilmedi</em>'; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-tags me-2"></i>İlgi Alanları</th>
                                <td><?php echo $interestsStr; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-paperclip me-2"></i>Yüklenen Dosya</th>
                                <td><?php echo $fileInfo; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-shield-check me-2"></i>KVKK Onayı</th>
                                <td>
                                    <?php if ($kvkk === 'Evet'): ?>
                                        <span class="badge bg-success px-3 py-2">Evet – Onaylandı</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger px-3 py-2">Hayır – Onaylanmadı</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-bell me-2"></i>Bülten Aboneliği</th>
                                <td>
                                    <?php if ($newsletter === 'Evet'): ?>
                                        <span class="badge bg-success px-3 py-2">Evet – Abone</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary px-3 py-2">Hayır</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-chat-dots me-2"></i>Mesaj</th>
                                <td style="white-space:pre-wrap;"><?php echo $message ?: '<em class="text-muted">Boş</em>'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Geri Dön Butonları -->
        <div class="d-flex gap-3 justify-content-center mt-5">
            <a href="../iletisim.html" class="btn btn-primary btn-lg">
                <i class="bi bi-arrow-left me-2"></i>Forma Geri Dön
            </a>
            <a href="../index.html" class="btn btn-outline-secondary btn-lg">
                <i class="bi bi-house me-2"></i>Ana Sayfa
            </a>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
