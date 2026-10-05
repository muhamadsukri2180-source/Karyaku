{{-- ============================================================
     KARYAKU DEVTOOLS DETECTOR - Partial Layout
     Dipasang di semua layout (admin, pembeli, penjual, verifikator, cs).
     Mendeteksi HANYA shortcut tombol developer (F12 / Inspect)
     PENTING:
     1. Admin, Verifikator, dan CS DIKECUALIKAN sepenuhnya.
     2. Window resize dan Right-click dinonaktifkan agar TIDAK
        menimbulkan false-positive (salah deteksi) pada pengguna biasa.
============================================================ --}}

@php
    $currentUserRole = auth()->check() ? strtolower(auth()->user()->role?->role_name ?? '') : '';
    $isStaffOrAdmin  = in_array($currentUserRole, ['admin', 'verifikator', 'customer_service']);
@endphp

@if(!$isStaffOrAdmin)
<script>
(function() {
    'use strict';

    // Hanya laporkan sekali per halaman agar tidak membebani server
    let _reported = false;

    function reportDevTools(method) {
        if (_reported) return;
        _reported = true;

        const url = '{{ url('/security/devtools-ping') }}';
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
        const payload = JSON.stringify({
            _signal: 'devtools_open',
            method: method,
            ts: Date.now(),
            _token: csrfToken
        });

        fetch(url, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: payload,
            keepalive: true
        }).catch(function() {});
    }

    // ─────────────────────────────────────────────
    // Deteksi HANYA shortcut keyboard spesifik DevTools:
    // F12 / Ctrl+Shift+I / Ctrl+Shift+C / Ctrl+Shift+J / Ctrl+U
    // (Bebas dari kesalahan resize layar atau klik kanan)
    // ─────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        const key = e.key;
        const ctrl = e.ctrlKey || e.metaKey;
        const shift = e.shiftKey;
        const keyUpper = key ? key.toUpperCase() : '';

        if (key === 'F12') { reportDevTools('F12'); return; }
        if (ctrl && shift && keyUpper === 'I') { reportDevTools('Ctrl+Shift+I'); return; }
        if (ctrl && shift && keyUpper === 'C') { reportDevTools('Ctrl+Shift+C'); return; }
        if (ctrl && shift && keyUpper === 'J') { reportDevTools('Ctrl+Shift+J'); return; }
        if (ctrl && shift && keyUpper === 'K') { reportDevTools('Ctrl+Shift+K'); return; }
        if (ctrl && keyUpper === 'U')          { reportDevTools('Ctrl+U');       return; }
    }, true);

})();
</script>
@endif
