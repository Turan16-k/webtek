<?php
// ============================================================
// contact.php – İletişim Formu Veri İşleyicisi
// Öğrenci: Abdurrahman Turan Özcan | B251210043
// Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi
//
// iletisim.html formu üzerinden gelen POST verilerini işler.
// Sunucu tarafı form doğrulaması (validation) ve veri işleme işlemleri gerçekleştirilir.
// ============================================================

// ============================================================
// YALNIZCA POST İSTEKLERİNİ KABUL ET
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // İzinsiz GET isteklerinde veya doğrudan erişimde formu yeniden yükle
    header('Location: ../iletisim.html');
    exit;
}

// ============================================================
// VERİ TEMİZLEME FONKSİYONU
// Çapraz Site Betik Çalıştırma (XSS) saldırılarını engellemek ve verileri
// güvenli hale getirmek amacıyla tasarlanmıştır.
// HTML etiketleri kaldırılır ve özel karakterler zararsız hale dönüştürülür.
// ============================================================
function clean($value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

// ============================================================
// FORM VERİLERİNİ AL VE TEMİZLE
// POST dizisinden veriler çekilir ve temizleme fonksiyonundan geçirilir.
// ============================================================
$name       = clean($_POST['name']       ?? '');
$email      = clean($_POST['email']      ?? '');
$phone      = clean($_POST['phone']      ?? '');
$subject    = clean($_POST['subject']    ?? '');
$message    = clean($_POST['message']    ?? '');
$gender     = clean($_POST['gender']     ?? '');
$education  = clean($_POST['education']  ?? '');
$birthdate  = clean($_POST['birthdate']  ?? '');

// Onay kutuları (checkbox) işaretlenmediğinde POST verisinde bulunmayacağından, isset() ile kontrol edilmektedir.
$kvkk       = isset($_POST['kvkk'])       ? 'Evet' : 'Hayır';
$newsletter = isset($_POST['newsletter']) ? 'Evet' : 'Hayır';

// ============================================================
// İLGİ ALANLARI KONTROLÜ
// Birden çok seçenekli (dizi formatındaki) ilgi alanı girdileri
// tek tek temizlenerek yeniden dizilip metin formatına çevrilir.
// ============================================================
$interests = [];
if (isset($_POST['interests']) && is_array($_POST['interests'])) {
    foreach ($_POST['interests'] as $interest) {
        $interests[] = clean($interest);
    }
}
// İlgi alanı seçimi yapılmadığı takdirde varsayılan metin atanır.
$interestsStr = !empty($interests) ? implode(', ', $interests) : 'Seçilmedi';

// ============================================================
// SUNUCU TARAFI VERİ DOĞRULAMASI
// İstemci tarafındaki (JS) kontroller atlatılabileceğinden dolayı,
// güvenlik ve veri bütünlüğü adına sunucu tarafında ek doğrulama uygulanır.
// ============================================================
$validationErrors = [];

if (empty($name)) {
    $validationErrors[] = 'Ad Soyad boş bırakılamaz.';
} elseif (mb_strlen($name, 'UTF-8') < 3) {
    $validationErrors[] = 'Ad Soyad en az 3 karakter olmalıdır.';
}

if (empty($email)) {
    $validationErrors[] = 'E-posta adresi boş bırakılamaz.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $validationErrors[] = 'Geçerli bir e-posta adresi giriniz.';
}

if (empty($phone)) {
    $validationErrors[] = 'Telefon numarası boş bırakılamaz.';
}

if (empty($subject)) {
    $validationErrors[] = 'Konu boş bırakılamaz.';
}

if (empty($message)) {
    $validationErrors[] = 'Mesaj boş bırakılamaz.';
} elseif (mb_strlen($message, 'UTF-8') < 20) {
    $validationErrors[] = 'Mesaj en az 20 karakter olmalıdır.';
}

if (empty($gender)) {
    $validationErrors[] = 'Cinsiyet seçilmedi.';
}

if (empty($education)) {
    $validationErrors[] = 'Eğitim düzeyi seçilmedi.';
}

if (empty($birthdate)) {
    $validationErrors[] = 'Doğum tarihi girilmedi.';
}

if ($kvkk !== 'Evet') {
    $validationErrors[] = 'KVKK onayı zorunludur.';
}

if (empty($interests)) {
    $validationErrors[] = 'En az bir ilgi alanı seçilmelidir.';
}

// ============================================================
// DOSYA YÜKLEME KONTROLÜ
// Yüklenen dosyanın varlığı, tip uygunluğu ve boyut sınırları denetlenir.
// ============================================================
if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
    $allowedTypes = [
        'image/jpeg', 'image/png', 'image/gif',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($_FILES['file_upload']['size'] > $maxSize) {
        $validationErrors[] = 'Dosya boyutu 5MB sınırını aşıyor.';
    } elseif (!in_array($_FILES['file_upload']['type'], $allowedTypes)) {
        $validationErrors[] = 'Sadece PDF, Word veya görsel (JPG, PNG) dosyası yüklenebilir.';
    }
}

// Validasyon hatası bulunması durumunda kullanıcıya hata sayfası gösterilir ve işlem sonlandırılır.
if (!empty($validationErrors)) {
    // Uyarı: İlerleyen geliştirmelerde bu hata akışı session üzerinden yönlendirilebilir.
    echo '<!DOCTYPE html><html lang="tr"><head><meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Form Hatası</title>
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
          <link href="../css/style.css" rel="stylesheet"></head><body>
          <div style="margin-top:80px;" class="container py-5">
          <div class="alert alert-danger">
          <h5><i class="bi bi-exclamation-triangle me-2"></i>Form gönderilemedi</h5>
          <ul class="mb-0">';
    foreach ($validationErrors as $err) {
        echo '<li>' . htmlspecialchars($err, ENT_QUOTES, 'UTF-8') . '</li>';
    }
    echo '</ul></div>
          <a href="../iletisim.html" class="btn btn-primary">
          <i class="bi bi-arrow-left me-2"></i>Forma Geri Dön</a>
          </div>
          <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
          </body></html>';
    exit;
}

// ============================================================
// DOSYA YÜKLEME BİLGİSİ
// Dosya başarıyla yüklenmişse dosya boyutu (KB) ve dosya adı bilgileri hazırlanır.
// ============================================================
$fileInfo = 'Dosya yüklenmedi';
if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
    $fileInfo = clean($_FILES['file_upload']['name'])
              . ' (' . round($_FILES['file_upload']['size'] / 1024, 2) . ' KB)';
}

// ============================================================
// GÖNDERİM ZAMANI
// İşlem tarihi güncel sunucu zamanından elde edilir.
// ============================================================
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
    <!-- PHP dosyası php/ klasöründe olduğu için CSS yolu bir üstten başlıyor -->
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>

<!-- Üst Navigasyon Çubuğu -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="../index.html">
            <i class="bi bi-person-badge-fill me-2"></i>A.T. Özcan
        </a>
        <div class="navbar-nav ms-auto">
            <a class="nav-link" href="../iletisim.html">
                <i class="bi bi-arrow-left me-1"></i>Geri Dön
            </a>
        </div>
    </div>
</nav>

<!-- İçerik Alanı -->
<div style="margin-top:70px;" class="py-5">
    <div class="container">

        <!-- BAŞARI MESAJI -->
        <div class="text-center mb-5">
            <!-- Başarı İkonu -->
            <div style="width:90px; height:90px; background:#198754; border-radius:50%;
                        display:flex; align-items:center; justify-content:center;
                        margin:0 auto 20px; font-size:2.5rem; color:#fff;">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 class="fw-bold text-success">Form Başarıyla Gönderildi!</h2>
            <p class="text-muted">Aşağıda gönderilen tüm form verileri listelenmektedir.</p>
            <!-- Gönderim Zamanı -->
            <small class="text-muted">
                <i class="bi bi-clock me-1"></i>Gönderim Zamanı: <?php echo $submitTime; ?>
            </small>
        </div>

        <!-- VERİ TABLOSU
             İşlenen ve doğrulanan verilerin tablo halinde listelenmesi -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Gönderilen Form Verileri</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <!-- Tablo Stilleri -->
                    <table class="table table-bordered mb-0 result-table">
                        <tbody>
                            <tr>
                                <th><i class="bi bi-person me-2"></i>Ad Soyad</th>
                                <!-- Veri Yoksa Varsayılan İfade -->
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
                                <!-- Seçilen İlgi Alanları -->
                                <td><?php echo $interestsStr; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-paperclip me-2"></i>Yüklenen Dosya</th>
                                <td><?php echo $fileInfo; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-shield-check me-2"></i>KVKK Onayı</th>
                                <td>
                                    <!-- Duruma Göre Dinamik Rozet -->
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
                                <!-- Kullanıcı girdisindeki satır atlamalarının korunması sağlanır -->
                                <td style="white-space:pre-wrap;">
                                    <?php echo $message ?: '<em class="text-muted">Boş</em>'; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- GERİ DÖN BUTONLARI -->
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
