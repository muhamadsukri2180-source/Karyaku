{{-- ============================================================
     KARYAKU DEVTOOLS DETECTOR - Partial Layout
     Dipasang di semua layout (admin, pembeli, penjual, verifikator, cs,
     landing, login, register).
     Mendeteksi HANYA shortcut tombol developer (F12 / Inspect / View Source)
     PENTING:
     1. Staff (Admin/Verifikator/CS) hanya dikecualikan jika
        SECURITY_EXEMPT_STAFF=true di .env (default: tetap dideteksi).
     2. Window resize dan Right-click dinonaktifkan agar TIDAK
        menimbulkan false-positive (salah deteksi) pada pengguna biasa.
============================================================ --}}

@php
    $currentUserRole = auth()->check() ? strtolower(auth()->user()->role?->role_name ?? '') : '';
    $isStaffOrAdmin  = in_array($currentUserRole, ['admin', 'verifikator', 'customer_service']);
    $skipDetector    = $isStaffOrAdmin && config('security_monitor.exempt_staff', false);
@endphp

@if(!$skipDetector)
<script>
(function() {
    'use strict';

    // Cegah script terpasang dua kali di halaman yang sama
    if (window.__karyakuDevtoolsDetector) return;
    window.__karyakuDevtoolsDetector = true;

    // Laporkan maksimal 1x per metode per halaman agar tidak membebani server
    const _reported = {};

    function reportDevTools(method) {
        if (_reported[method]) return;
        _reported[method] = true;

        // URL relatif -> selalu mengarah ke host/port yang sedang dibuka
        // (tidak bergantung APP_URL di .env, aman untuk lokal maupun hosting)
        const url = @json(route('security.devtools_ping', [], false));
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : @json(csrf_token());

        fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            keepalive: true,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                _signal: 'devtools_open',
                method: method,
                page: window.location.pathname,
                ts: Date.now()
            })
        }).catch(function() {});
    }

    // ─────────────────────────────────────────────
    // Deteksi HANYA shortcut keyboard spesifik DevTools:
    // F12 / Ctrl+Shift+I / Ctrl+Shift+C / Ctrl+Shift+J / Ctrl+Shift+K / Ctrl+U
    // (Mac: Cmd+Opt+I / Cmd+Opt+C / Cmd+Opt+J / Cmd+Opt+U)
    // ─────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        const key  = e.key || '';
        const code = e.code || '';
        const ctrl = e.ctrlKey || e.metaKey;
        const shiftOrAlt = e.shiftKey || (e.metaKey && e.altKey);
        // e.code tidak terpengaruh Shift/Alt/layout keyboard -> lebih akurat
        const letter = code.startsWith('Key') ? code.slice(3) : key.toUpperCase();

        if (key === 'F12' || code === 'F12')        { reportDevTools('F12');          return; }
        if (ctrl && shiftOrAlt && letter === 'I')   { reportDevTools('Ctrl+Shift+I'); return; }
        if (ctrl && shiftOrAlt && letter === 'C')   { reportDevTools('Ctrl+Shift+C'); return; }
        if (ctrl && shiftOrAlt && letter === 'J')   { reportDevTools('Ctrl+Shift+J'); return; }
        if (ctrl && e.shiftKey && letter === 'K')   { reportDevTools('Ctrl+Shift+K'); return; }
        if (ctrl && !e.shiftKey && letter === 'U')  { reportDevTools('Ctrl+U');       return; }
    }, true);

})();
</script>
@endif
