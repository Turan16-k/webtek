<?php
// İletişim formu verilerini alır ve ekrana düzenli biçimde yazar

$name      = htmlspecialchars($_POST['name']      ?? '');
$email     = htmlspecialchars($_POST['email']     ?? '');
$phone     = htmlspecialchars($_POST['phone']     ?? '');
$birthdate = htmlspecialchars($_POST['birthdate'] ?? '');
$gender    = htmlspecialchars($_POST['gender']    ?? '');
$education = htmlspecialchars($_POST['education'] ?? '');
$subject   = htmlspecialchars($_POST['subject']   ?? '');
$message   = htmlspecialchars($_POST['message']   ?? '');
$interests = isset($_POST['interests']) ? array_map('htmlspecialchars', $_POST['interests']) : [];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Sonucu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body style="background:#f8f9fa; padding-top:80px;">

    <div class="container" style="max-width:700px;">
        <div class="card p-4 p-md-5">
            <div class="text-center mb-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem;"></i>
                <h2 class="fw-bold mt-3 text-primary">Form Alındı!</h2>
                <p class="text-muted">Gönderilen veriler aşağıda listelenmiştir.</p>
            </div>

            <table class="table table-bordered result-table">
                <tbody>
                    <tr><th>Ad Soyad</th><td><?= $name ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>E-posta</th><td><?= $email ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Telefon</th><td><?= $phone ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Doğum Tarihi</th><td><?= $birthdate ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Cinsiyet</th><td><?= $gender ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Eğitim</th><td><?= $education ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>İlgi Alanları</th><td><?= $interests ? implode(', ', $interests) : '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Konu</th><td><?= $subject ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                    <tr><th>Mesaj</th><td><?= nl2br($message) ?: '<em class="text-muted">Boş</em>' ?></td></tr>
                </tbody>
            </table>

            <div class="text-center mt-4">
                <a href="../iletisim.html" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>Geri Dön
                </a>
            </div>
        </div>
    </div>

</body>
</html>
