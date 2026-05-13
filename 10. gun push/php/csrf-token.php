<?php
/*
 * csrf-token.php – CSRF token üretici (10. Gün)
 *
 * Statik .html dosyaları PHP kodu çalıştıramaz. Bu yüzden
 * login.html ve iletisim.html, form gönderilmeden önce bu
 * endpoint'ten AJAX ile token alıp forma hidden input olarak ekliyor.
 *
 * Nasıl çalışır:
 * 1. Sayfa yüklendiğinde JS bu dosyaya fetch() atar
 * 2. Sunucu session'a token kaydeder, JSON olarak döner
 * 3. JS token'ı formun hidden alanına yazar
 * 4. Form submit edildiğinde token PHP'ye POST ile gelir
 * 5. login-kontrol.php ve form-islem.php token'ı session'dakiyle karşılaştırır
 */

session_start();

// Token yoksa veya 30 dakika geçtiyse yenile
if (empty($_SESSION['csrf_token']) ||
    (isset($_SESSION['csrf_time']) && time() - $_SESSION['csrf_time'] > 1800)) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    $_SESSION['csrf_time']  = time();
}

header('Content-Type: application/json');
header('Cache-Control: no-store');
echo json_encode(['token' => $_SESSION['csrf_token']]);
?>
