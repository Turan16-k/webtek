<?php
/*
 * cikis.php – Oturumu sonlandır
 * 8. Gün: session_start() eklediğimize göre çıkış sayfası da lazım
 */

session_start();
session_destroy(); // Tüm session verilerini sil

// Ana sayfaya yönlendir
header("Location: ../index.html");
exit;
?>
