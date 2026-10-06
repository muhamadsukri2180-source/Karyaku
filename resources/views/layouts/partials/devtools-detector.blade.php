{{-- ============================================================
     KARYAKU DEVTOOLS & INSPECT ELEMENT DETECTOR
     Mendeteksi pembukaan DevTools via:
     1. Shortcut Keyboard (F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+Shift+J, Ctrl+U, Mac)
     2. Klik Kanan → Inspect Element (contextmenu)

     CATATAN: Admin, Verifikator, dan Customer Service SELALU dikecualikan
     dari deteksi ini.
============================================================ --}}

@php
    $currentUserRole = auth()->check() ? strtolower(auth()->user()->role?->role_name ?? '') : '';
    // Staff/Admin SELALU dikecualikan
    $isStaffOrAdmin = in_array($currentUserRole, ['admin', 'verifikator', 'customer_service']);
@endphp

@if(!$isStaffOrAdmin)
<script>
(function() {
    'use strict';

    if (window.__karyakuDevtoolsDetector) return;
    window.__karyakuDevtoolsDetector = true;

    // Track per-method agar bisa lapor beberapa jenis sekaligus
    const _reportedMethods = {};

    function reportDevTools(method) {
        if (_reportedMethods[method]) return;
        _reportedMethods[method] = true;

        const url = @json(route('security.devtools_ping', [], false));
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : @json(csrf_token());

        const payload = JSON.stringify({
            _signal: 'devtools_open',
            method: method,
            page: window.location.pathname,
            ts: Date.now()
        });

        // Coba fetch terlebih dahulu (support header CSRF)
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
            body: payload
        }).catch(function() {
            // Fallback navigator.sendBeacon jika fetch gagal
            if (navigator.sendBeacon) {
                try {
                    const blob = new Blob([payload], { type: 'application/json' });
                    navigator.sendBeacon(url, blob);
                } catch(e) {}
            }
        });
    }

    // 1. Deteksi Shortcut Keyboard Developer
    document.addEventListener('keydown', function(e) {
        const key  = (e.key || '').toLowerCase();
        const code = (e.code || '').toLowerCase();
        const ctrl = e.ctrlKey || e.metaKey;
        const shiftOrAlt = e.shiftKey || (e.metaKey && e.altKey);

        // F12
        if (key === 'f12' || code === 'f12') {
            reportDevTools('F12');
            return;
        }

        // Ctrl+Shift+I / Cmd+Option+I
        if (ctrl && shiftOrAlt && (key === 'i' || code === 'keyi')) {
            reportDevTools('Ctrl+Shift+I');
            return;
        }

        // Ctrl+Shift+C / Cmd+Option+C (Inspect)
        if (ctrl && shiftOrAlt && (key === 'c' || code === 'keyc')) {
            reportDevTools('Ctrl+Shift+C');
            return;
        }

        // Ctrl+Shift+J / Cmd+Option+J (Console)
        if (ctrl && shiftOrAlt && (key === 'j' || code === 'keyj')) {
            reportDevTools('Ctrl+Shift+J');
            return;
        }

        // Ctrl+Shift+K (Firefox Web Console)
        if (ctrl && e.shiftKey && (key === 'k' || code === 'keyk')) {
            reportDevTools('Ctrl+Shift+K');
            return;
        }

        // Ctrl+U (View Source)
        if (ctrl && !e.shiftKey && (key === 'u' || code === 'keyu')) {
            reportDevTools('Ctrl+U');
            return;
        }
    }, true);

    // 2. Deteksi Klik Kanan → Inspect Element
    document.addEventListener('contextmenu', function(e) {
        reportDevTools('right-click');
    }, true);

})();
</script>
@endif
