<?php

/*
|--------------------------------------------------------------------------
| Konfigurasi Monitoring Keamanan (IP Monitor / DevTools / SQLi / XSS)
|--------------------------------------------------------------------------
|
| exempt_staff:
|   false (default) -> SEMUA user (termasuk Admin/Verifikator/CS) tetap
|                      dideteksi & dicatat di tabel "Daftar IP Mencurigakan".
|                      Staff tetap TIDAK AKAN PERNAH diblokir.
|   true            -> Admin/Verifikator/CS tidak dideteksi sama sekali.
|
| CATATAN: Deteksi TIDAK lagi berbasis IP (127.0.0.1 / IP admin) karena
| banyak user bisa berbagi IP yang sama (WiFi kampus/kantor/localhost),
| sehingga pengecualian IP membuat log "hilang" dari tabel.
|
*/

return [
    'exempt_staff' => (bool) env('SECURITY_EXEMPT_STAFF', true),
];
