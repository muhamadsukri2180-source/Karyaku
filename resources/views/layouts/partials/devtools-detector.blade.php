{{-- ============================================================
     KARYAKU DEVTOOLS & INSPECT ELEMENT DETECTOR
     Mendeteksi pembukaan DevTools via:
     1. Shortcut Keyboard (F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+Shift+J, Ctrl+U, Mac)
     2. Perubahan ukuran window yang signifikan (DevTools docked)
     3. Konsol DevTools dibuka (Undocked DevTools via Getter Probe)
     4. Klik Kanan -> Inspect Element

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

    let _reported = false;

    function reportDevTools(method) {
        if (_reported) return;
        _reported = true;

        const url = @json(route('security.devtools_ping', [], false));
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : @json(csrf_token());

        const payload = JSON.stringify({
            _signal: 'devtools_open',
            method: method || 'devtools-open',
            page: window.location.pathname,
            ts: Date.now()
        });

        // Coba navigator.sendBeacon terlebih dahulu
        let sent = false;
        if (navigator.sendBeacon) {
            try {
                const blob = new Blob([payload], { type: 'application/json' });
                sent = navigator.sendBeacon(url, blob);
            } catch(e) {}
        }

        // Fallback fetch jika sendBeacon tidak didukung / gagal
        if (!sent) {
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
            }).catch(function() {});
        }
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

    // 2. Deteksi Docked DevTools via ukuran window
    let _windowStable = false;
    setTimeout(function() { _windowStable = true; }, 1200);

    function checkDockedDevTools() {
        if (_reported || !_windowStable) return;

        const widthDiff  = window.outerWidth  - window.innerWidth;
        const heightDiff = window.outerHeight - window.innerHeight;

        // DevTools docked panel umumnya > 170px
        if (widthDiff > 170 || heightDiff > 170) {
            reportDevTools('devtools-open');
        }
    }

    window.addEventListener('resize', function() {
        setTimeout(checkDockedDevTools, 200);
    });
    setTimeout(checkDockedDevTools, 1800);

    // Deteksi saat Klik Kanan -> Inspect Element
    document.addEventListener('contextmenu', function() {
        setTimeout(checkDockedDevTools, 800);
        setTimeout(checkDockedDevTools, 2000);
    }, { passive: true });

    // 3. Deteksi Undocked DevTools / Console via Getter Probe
    try {
        const element = document.createElement('div');
        Object.defineProperty(element, 'id', {
            get: function() {
                reportDevTools('devtools-open');
                return 'karyaku-inspect';
            }
        });
        setInterval(function() {
            if (_reported) return;
            console.log(element);
        }, 2000);
    } catch(e) {}

})();
</script>
@endif
