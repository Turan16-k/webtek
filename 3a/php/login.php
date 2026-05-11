<?php
// ============================================================
// login.php – Giriş Doğrulama
// Öğrenci numarası ve e-posta ile giriş kontrolü.
// ============================================================

// Yalnızca POST isteği kabul et
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.html');
    exit;
}

// =============================================
// ÖĞRENCI BİLGİLERİ — Kendi numaranızla değiştirin
// =============================================
$validEmail    = 'b251210043@sakarya.edu.tr';
$validPassword = 'B251210043';
$ogrenciNo     = 'B251210043';
// =============================================

// Girilen verileri al ve temizle
$email    = trim($_POST['email']    ?? '');
$password = trim($_POST['password'] ?? '');

// Boş alan kontrolü
if (empty($email) || empty($password)) {
    header('Location: ../login.html?hata=bos');
    exit;
}

// Kimlik doğrulama
if ($email === $validEmail && $password === $validPassword) {
    // ✅ Başarılı giriş – başarı sayfasına yönlendir
    session_start();
    $_SESSION['ogrenci_no']   = $ogrenciNo;
    $_SESSION['logged_in']    = true;
    header('Location: success.php');
    exit;
} else {
    // ❌ Hatalı giriş – login sayfasına geri yönlendir
    header('Location: ../login.html?hata=yanlis');
    exit;
}
?>
