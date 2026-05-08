<?php
// Öğrenci numarası ve e-posta ile giriş kontrolü
// Kullanıcı adı: b251210043@sakarya.edu.tr
// Şifre       : B251210043

$gecerli_email = "b251210043@sakarya.edu.tr";
$gecerli_sifre = "B251210043";

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

// Boş alan kontrolü
if (empty($email) || empty($password)) {
    header("Location: ../login.html?hata=bos");
    exit;
}

// Kimlik doğrulama
if ($email === $gecerli_email && $password === $gecerli_sifre) {
    // Başarılı giriş
    $ogrenci_no = explode('@', $email)[0];
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
        <a href="../index.html" class="btn btn-primary btn-lg mt-4 w-100">
            <i class="bi bi-house me-2"></i>Ana Sayfaya Git
        </a>
    </div>
</body>
</html>
<?php
} else {
    // Hatalı giriş — login sayfasına yönlendir
    header("Location: ../login.html?hata=yanlis");
    exit;
}
?>
