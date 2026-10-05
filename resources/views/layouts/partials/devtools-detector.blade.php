{{-- ============================================================
     KARYAKU DEVTOOLS & INSPECT ELEMENT DETECTOR
     Mendeteksi pembukaan DevTools via:
     1. Shortcut Keyboard (F12, Ctrl+Shift+I, Ctrl+Shift+C, Ctrl+Shift+J, Ctrl+U, Mac)
     2. Klik Kanan -> Inspect Element / Browser Menu (Docked & Undocked DevTools)
     3. Halaman yang dibuka saat DevTools sudah dalam keadaan terbuka
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

    if (window.__karyakuDevtoolsDetector) return;
    window.__karyakuDevtoolsDetector = true;

    let _reported = false;

    function reportDevTools(method) {
        if (_reported) return;
        _reported = true;

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
                method: method || 'devtools-open',
                page: window.location.pathname,
                ts: Date.now()
            })
        }).catch(function() {});
    }

    // 1. Deteksi Shortcut Keyboard Developer
    document.addEventListener('keydown', function(e) {
        const key  = e.key || '';
        const code = e.code || '';
        const ctrl = e.ctrlKey || e.metaKey;
        const shiftOrAlt = e.shiftKey || (e.metaKey && e.altKey);
        const letter = code.startsWith('Key') ? code.slice(3) : key.toUpperCase();

        if (key === 'F12' || code === 'F12')        { reportDevTools('F12');          return; }
        if (ctrl && shiftOrAlt && letter === 'I')   { reportDevTools('Ctrl+Shift+I'); return; }
        if (ctrl && shiftOrAlt && letter === 'C')   { reportDevTools('Ctrl+Shift+C'); return; }
        if (ctrl && shiftOrAlt && letter === 'J')   { reportDevTools('Ctrl+Shift+J'); return; }
        if (ctrl && e.shiftKey && letter === 'K')   { reportDevTools('Ctrl+Shift+K'); return; }
        if (ctrl && !e.shiftKey && letter === 'U')  { reportDevTools('Ctrl+U');       return; }
    }, true);

    // 2. Deteksi Docked DevTools (Panel samping atau bawah terbuka)
    function checkDockedDevTools() {
        if (_reported) return;
        const widthDiff = window.outerWidth - window.innerWidth;
        const heightDiff = window.outerHeight - window.innerHeight;
        // Pada desktop, window border < 25px. DevTools yang terbuka docked selalu > 160px
        if (widthDiff > 160 || heightDiff > 160) {
            reportDevTools('devtools-open');
        }
    }

    window.addEventListener('resize', checkDockedDevTools);
    setTimeout(checkDockedDevTools, 800);
    setInterval(checkDockedDevTools, 2500);

    // 3. Deteksi saat Klik Kanan -> Inspect
    document.addEventListener('contextmenu', function() {
        setTimeout(checkDockedDevTools, 600);
        setTimeout(checkDockedDevTools, 1500);
    }, true);

    // 4. Deteksi Console / Undocked DevTools via Console Getter
    try {
        const probe = document.createElement('div');
        Object.defineProperty(probe, 'id', {
            get: function() {
                reportDevTools('devtools-open');
                return 'karyaku-shield';
            }
        });
        setInterval(function() {
            if (_reported) return;
            console.log(probe);
            console.clear();
        }, 2000);
    } catch(e) {}

})();
</script>
@endif
