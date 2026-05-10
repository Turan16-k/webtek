# Portfolyo Web Sitesi – 8. Gün Push

**Öğrenci:** Abdurrahman Turan Özcan  
**No:** B251210043  
**Tarih:** 10 Mayıs 2026

---

## Bu Günkü Değişiklikler

### Bulunan ve Düzeltilen Hatalar

| # | Dosya | Hata | Düzeltme |
|---|-------|------|----------|
| 1 | `login.html` | `.html` dosyasında `<?php ?>` çalışmaz — hata mesajı hiç gösterilmiyordu | JS ile URL parametresi okunacak şekilde değiştirildi (`initLoginError()`) |
| 2 | `iletisim.html` | "Gönder" butonu validasyon yapılmadan formu submit ediyordu | `form submit` event'ine listener eklendi (`initContactFormSubmit()`) |
| 3 | `php/login-kontrol.php` | `session_start()` yoktu, giriş sonrası kullanıcı tanınamıyordu | `session_start()` ve `$_SESSION` kaydı eklendi |
| 4 | `php/form-islem.php` | Sunucu tarafı validasyon yoktu, boş/hatalı form kabul ediliyordu | PHP ile tam sunucu doğrulaması eklendi |
| 5 | `js/main.js` | Cursor glow her `mousemove`'da `style` değiştiriyordu, performans sorunu | `requestAnimationFrame` ile throttle edildi |
| 6 | `iletisim.html` navbar | "Projelerim" linki yoktu | Eklendi |

### Yeni Dosyalar
- `php/cikis.php` — Session'ı temizleyip çıkış yapan sayfa

---

## Bilinen Açık Sorunlar (Sonraki Günlere Kaldı)

- Şifre hâlâ düz metin karşılaştırması — `password_hash()` / `password_verify()` uygulanmalı
- Veritabanı yok, kullanıcı bilgileri hardcoded
- Vue.js yüklü ama gerçek reactive sistem kullanılmıyor
- GitHub / LinkedIn butonları hâlâ `href="#"` — gerçek URL'ler eklenecek
- CSRF token yok

---

## Proje Yapısı

```
8. gun push/
├── index.html
├── cv.html
├── projeler.html
├── sehrim.html
├── takimimiz.html
├── ilgi-alanlari.html
├── iletisim.html       ← 8. gün düzeltildi
├── login.html          ← 8. gün düzeltildi
├── css/
│   └── style.css
├── js/
│   └── main.js         ← 8. gün düzeltildi
└── php/
    ├── form-islem.php  ← 8. gün düzeltildi
    ├── login-kontrol.php ← 8. gün düzeltildi
    └── cikis.php       ← 8. gün yeni eklendi
```
