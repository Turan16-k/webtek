<?php
// ============================================================
// contact.php – İletişim Formu Veri İşleyicisi
// Öğrenci: Abdurrahman Turan Özcan | B251210043
// Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi
//
// Bu sayfa iletisim.html'deki formun action hedefi.
// Form POST metoduyla veri gönderince bu dosya çalışıyor.
// PHP sunucu tarafında çalışır — yani tarayıcıda değil,
// sunucu bilgisayarda işlenir, sonuç HTML olarak tarayıcıya gelir.
// ============================================================

// ============================================================
// YALNIZCA POST İSTEKLERİNİ KABUL ET
// $_SERVER['REQUEST_METHOD'] → o anki HTTP istek metodunu veriyor.
// Birisi direkt URL'yi tarayıcıya yazarsa GET isteği olur,
// biz sadece form gönderimlerini (POST) işlemek istiyoruz.
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // GET isteği gelirse kullanıcıyı forma geri gönder
    header('Location: ../iletisim.html');
    exit; // header'dan sonra exit zorunlu, yoksa kod çalışmaya devam eder
}

// ============================================================
// VERİ TEMİZLEME FONKSİYONU
// Kullanıcıdan gelen veriler güvenilir değil — temizlemek lazım.
// Neden?
//   - htmlspecialchars() → HTML karakterlerini (< > " &) zararsız hale getirir.
//     Örnek: <script>alert()</script> gibi XSS saldırılarını engeller.
//   - strip_tags() → HTML etiketlerini tamamen kaldırır.
//   - trim() → baştaki ve sondaki boşlukları kaldırır.
// ENT_QUOTES → hem " hem ' karakterini dönüştürür.
// UTF-8 → Türkçe karakterlerin bozulmaması için karakter seti.
// ============================================================
function clean($value) {
    return htmlspecialchars(strip_tags(trim($value)), ENT_QUOTES, 'UTF-8');
}

// ============================================================
// FORM VERİLERİNİ AL VE TEMİZLE
// $_POST dizisi form alanlarından gelen verileri tutuyor.
// Alan isimleri HTML'deki name="..." ile eşleşiyor.
// ?? '' → null birleştirme operatörü: alan gelmemişse boş string kullan.
// ============================================================
$name       = clean($_POST['name']       ?? '');
$email      = clean($_POST['email']      ?? '');
$phone      = clean($_POST['phone']      ?? '');
$subject    = clean($_POST['subject']    ?? '');
$message    = clean($_POST['message']    ?? '');
$gender     = clean($_POST['gender']     ?? '');
$education  = clean($_POST['education']  ?? '');
$birthdate  = clean($_POST['birthdate']  ?? '');

// isset() → değişken tanımlı mı ve null değil mi kontrol eder.
// Checkbox işaretlenmemişse $_POST'ta hiç gelmez, isset false döner.
$kvkk       = isset($_POST['kvkk'])       ? 'Evet' : 'Hayır';
$newsletter = isset($_POST['newsletter']) ? 'Evet' : 'Hayır';

// ============================================================
// İLGİ ALANLARI — DİZİ VERİSİ
// HTML'de name="interests[]" kullandım — [] sonunda olunca
// PHP birden fazla checkbox değerini otomatik dizi olarak alıyor.
// is_array() ile gerçekten dizi mi diye kontrol ediyorum.
// Her elemanı temizleyip $interests dizisine atıyorum.
// implode() ile diziyi virgülle ayrılmış string'e çeviriyorum.
// ============================================================
$interests = [];
if (isset($_POST['interests']) && is_array($_POST['interests'])) {
    foreach ($_POST['interests'] as $interest) {
        $interests[] = clean($interest);
    }
}
// Hiç seçilmemişse "Seçilmedi" yazsın
$interestsStr = !empty($interests) ? implode(', ', $interests) : 'Seçilmedi';

// ============================================================
// DOSYA YÜKLEME BİLGİSİ
// $_FILES — dosya yükleme verileri buraya geliyor.
// enctype="multipart/form-data" form özelliği olmadan
// bu dizi boş gelirdi! (iletisim.html'de eklendi)
// error === 0 → yükleme başarılı demek (0 = UPLOAD_ERR_OK)
// filesize → byte cinsinden, round() ile KB'a çeviriyorum.
// ============================================================
$fileInfo = 'Dosya yüklenmedi';
if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] === 0) {
    // Dosya adını temizleyip boyutuyla birlikte gösteriyorum
    $fileInfo = clean($_FILES['file_upload']['name'])
              . ' (' . round($_FILES['file_upload']['size'] / 1024, 2) . ' KB)';
}

// ============================================================
// GÖNDERIM ZAMANI
// date() → belirtilen formatta güncel tarihi verir.
// 'd.m.Y H:i:s' → 15.01.2025 14:32:05 gibi
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

<!-- Basit navbar — sadece geri dön butonu var -->
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

<!-- margin-top ile fixed navbar'ın altına içeriği yerleştiriyorum -->
<div style="margin-top:70px;" class="py-5">
    <div class="container">

        <!-- BAŞARI MESAJI -->
        <div class="text-center mb-5">
            <!-- Yeşil yuvarlak içinde tik işareti — inline style kullandım,
                 tek seferlik stil olduğundan ayrı CSS sınıfı yazmadım -->
            <div style="width:90px; height:90px; background:#198754; border-radius:50%;
                        display:flex; align-items:center; justify-content:center;
                        margin:0 auto 20px; font-size:2.5rem; color:#fff;">
                <i class="bi bi-check-lg"></i>
            </div>
            <h2 class="fw-bold text-success">Form Başarıyla Gönderildi!</h2>
            <p class="text-muted">Aşağıda gönderilen tüm form verileri listelenmektedir.</p>
            <!-- PHP ile hesaplanan zamanı ekrana yazdırıyorum -->
            <small class="text-muted">
                <i class="bi bi-clock me-1"></i>Gönderim Zamanı: <?php echo $submitTime; ?>
            </small>
        </div>

        <!-- VERİ TABLOSU
             PHP'den gelen değişkenleri tablo içinde gösteriyorum.
             echo → PHP'yi HTML içine yazdırmak için.
             ?: operatörü → değer varsa yazdır, yoksa "Boş" yaz.
             Boş gelme ihtimaline karşı her satırda kontrol var. -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table me-2"></i>Gönderilen Form Verileri</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <!-- result-table sınıfı style.css'te tanımlı — th ve td'ye özel stiller var -->
                    <table class="table table-bordered mb-0 result-table">
                        <tbody>
                            <tr>
                                <th><i class="bi bi-person me-2"></i>Ad Soyad</th>
                                <!-- em etiketi italik ve anlamsal vurgu — içerik yoksa "Boş" göster -->
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
                                <!-- implode ile birleştirilen string burada geliyor -->
                                <td><?php echo $interestsStr; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-paperclip me-2"></i>Yüklenen Dosya</th>
                                <td><?php echo $fileInfo; ?></td>
                            </tr>
                            <tr>
                                <th><i class="bi bi-shield-check me-2"></i>KVKK Onayı</th>
                                <td>
                                    <!-- PHP if-else ile rozet rengini dinamik seçiyorum -->
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
                                <!-- white-space:pre-wrap → kullanıcının satır atlamalarını korur -->
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
