<?php
// ============================================================
// login.php – Kullanıcı Giriş Doğrulaması
// Öğrenci: Abdurrahman Turan Özcan | B251210043
// Ders: Web Teknolojileri – 2025/2026 Bahar Dönemi
//
// Bu dosya login.html'deki formun action hedefi.
// Kullanıcının girdiği e-posta ve şifreyi,
// sabit tanımlı değişkenlerle karşılaştırıyor.
// Gerçek bir projede şifreler veritabanında şifreli saklanır
// ama bu ödevde öğrenci numarasını sabit tutmak yeterli.
// ============================================================

// Sadece POST isteği kabul et — direkt URL erişimini engelle
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html');
    exit;
}

// ============================================================
// ÖĞRENCI GİRİŞ BİLGİLERİ
// Ödev gereksinimi: kullanıcı adı öğrenci no'lu mail,
// şifre ise öğrenci numarasının kendisi.
// ============================================================
$validEmail    = 'b251210043@sakarya.edu.tr';   // e-posta formatı: bXXXXXXXXX@sakarya.edu.tr
$validPassword = 'B251210043';                   // şifre: büyük B ile başlıyor
$ogrenciNo     = 'B251210043';                   // başarı sayfasında gösterilecek öğrenci no

// ============================================================
// FORM VERİLERİNİ AL
// trim() — boşluk karakterlerini temizler.
// Kullanıcı yanlışlıkla başına/sonuna boşluk girmiş olabilir.
// ?? '' — alan POST'ta yoksa boş string kullan (PHP 7+ null birleştirme).
// ============================================================
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// ============================================================
// BOŞ ALAN KONTROLÜ
// empty() → değişken boş mu? (boş string, null, 0, false için true döner)
// E-posta veya şifre boşsa 'hata=bos' parametresiyle login'e geri dön.
// Bu parametre login.html'deki JavaScript tarafından okunup mesaj gösterilir.
// ============================================================
if (empty($email) || empty($password)) {
    header('Location: ../login.html?hata=bos');
    exit;
}

// ============================================================
// KİMLİK DOĞRULAMA
// === (üç eşittir) → tip ve değer karşılaştırması yapıyor.
// == (iki eşittir) kullanmak tip dönüşümüne yol açabilir — === daha güvenli.
// ============================================================
if ($email === $validEmail && $password === $validPassword) {

    // ✅ GİRİŞ BAŞARILI
    // session_start() → PHP oturum sistemini başlatır.
    // Oturum bilgileri sunucuda saklanır, tarayıcıda sadece oturum kimliği (session ID) tutulur.
    // $_SESSION[] → oturum boyunca saklanacak veriler buraya yazılır.
    // Bu veriler farklı PHP sayfaları arasında paylaşılabilir (success.php okuyacak).
    session_start();
    $_SESSION['ogrenci_no'] = $ogrenciNo;
    $_SESSION['logged_in']  = true;

    // Başarı sayfasına yönlendir — header() HTTP yönlendirmesi gönderir
    header('Location: success.php');
    exit;

} else {

    // ❌ GİRİŞ HATALI — yanlış e-posta veya şifre
    // Kullanıcıyı tekrar login sayfasına gönder, hata tipi bilgisi URL'de
    header('Location: ../login.html?hata=yanlis');
    exit;
}
?>
