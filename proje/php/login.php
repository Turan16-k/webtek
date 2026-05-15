<?php
// ============================================================
// login.php – Giriş Formu İşleyicisi
// Öğrenci: Abdurrahman Turan Özcan | B251210043
// Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi
//
// login.html üzerinden gelen POST isteklerini karşılar.
// Proje gereksinimleri doğrultusunda veritabanı yerine sabit değişkenler ile kimlik doğrulaması yapılmaktadır.
// ============================================================

// Sadece POST isteklerine izin verilir. Harici erişimlerde giriş sayfasına yönlendirilir.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html');
    exit;
}

// ============================================================
// ÖĞRENCİ GİRİŞ BİLGİLERİ
// Sabit e-posta ve şifre tanımlamaları.
// ============================================================
$validEmail    = 'b251210043@sakarya.edu.tr';   // e-posta formatı: bXXXXXXXXX@sakarya.edu.tr
$validPassword = 'B251210043';                   // şifre: büyük B ile başlıyor
$ogrenciNo     = 'B251210043';                   // başarı sayfasında gösterilecek öğrenci no

// ============================================================
// FORM VERİLERİNİ AL
// Girdi verilerindeki olası gereksiz boşluklar trim() ile temizlenir.
// ============================================================
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// ============================================================
// BOŞ ALAN KONTROLÜ
// Gerekli alanların doldurulup doldurulmadığı denetlenir.
// Eksik bilgi varsa URL üzerinden hata parametresi ile geri dönüş yapılır.
// ============================================================
if (empty($email) || empty($password)) {
    header('Location: ../login.html?hata=bos');
    exit;
}

// ============================================================
// KİMLİK DOĞRULAMA
// Katı eşitlik (===) kullanılarak tip uyumu dahil kontrol edilir.
// ============================================================
if ($email === $validEmail && $password === $validPassword) {

    // ✅ GİRİŞ BAŞARILI
    // Başarılı giriş sonrasında oturum (session) başlatılır ve veriler saklanır.
    session_start();
    $_SESSION['ogrenci_no'] = $ogrenciNo;
    $_SESSION['logged_in']  = true;

    // Başarı sayfasına yönlendirme yapılır.
    header('Location: success.php');
    exit;

} else {

    // ❌ BAŞARISIZ GİRİŞ
    // Yanlış kimlik bilgileri durumunda hata parametresiyle giriş sayfasına geri dönülür.
    header('Location: ../login.html?hata=yanlis');
    exit;
}
?>
