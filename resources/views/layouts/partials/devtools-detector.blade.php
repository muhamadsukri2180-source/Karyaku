{{-- ============================================================
     KARYAKU DEVTOOLS DETECTOR - Partial Layout
     Dipasang di semua layout (admin, pembeli, penjual, verifikator, cs).
     Mendeteksi saat user membuka DevTools / Inspect Element
     dan melaporkannya ke server via beacon POST.
     Admin (role: admin) dikecualikan dari pelacakan ini.
============================================================ --}}

@php
    $currentUserRole = auth()->check() ? (auth()->user()->role?->role_name ?? '') : '';
@endphp

@if($currentUserRole !== 'admin')
<script>
(function() {
    'use strict';

    // Hanya laporkan sekali per session page-load, jangan spam server
    let _reported = false;

    // Kirim sinyal ke server bahwa DevTools terbuka
    function reportDevTools(method) {
        if (_reported) return;
        _reported = true;

        const url = '{{ url('/security/devtools-ping') }}';
        const payload = JSON.stringify({ _signal: 'devtools_open', method: method, ts: Date.now() });

        // Gunakan sendBeacon (non-blocking, tetap dikirim walau halaman di-unload)
        if (navigator.sendBeacon) {
            const blob = new Blob([payload], { type: 'application/json' });
            navigator.sendBeacon(url, blob);
        } else {
            // Fallback: fetch biasa
            fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: payload,
                keepalive: true
            }).catch(function() {});
        }
    }

    // ─────────────────────────────────────────────
    // METODE 1: Keyboard Shortcut DevTools
    // F12 / Ctrl+Shift+I / Ctrl+Shift+C / Ctrl+Shift+J / Ctrl+U
    // ─────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        const key = e.key;
        const ctrl = e.ctrlKey;
        const shift = e.shiftKey;
        const keyUpper = key ? key.toUpperCase() : '';

        if (key === 'F12') { reportDevTools('F12'); return; }
        if (ctrl && shift && keyUpper === 'I') { reportDevTools('Ctrl+Shift+I'); return; }
        if (ctrl && shift && keyUpper === 'C') { reportDevTools('Ctrl+Shift+C'); return; }
        if (ctrl && shift && keyUpper === 'J') { reportDevTools('Ctrl+Shift+J'); return; }
        if (ctrl && shift && keyUpper === 'K') { reportDevTools('Ctrl+Shift+K'); return; }
        if (ctrl && keyUpper === 'U')          { reportDevTools('Ctrl+U');       return; }
    }, true);

    // ─────────────────────────────────────────────
    // METODE 2: Klik Kanan (Context Menu → "Inspect")
    // ─────────────────────────────────────────────
    document.addEventListener('contextmenu', function(e) {
        reportDevTools('right-click');
    }, true);

    // ─────────────────────────────────────────────
    // METODE 3: Window Resize Detection
    // DevTools dock akan mempersempit window (threshold > 160px)
    // ─────────────────────────────────────────────
    function checkByWindowSize() {
        const wDiff = window.outerWidth - window.innerWidth;
        const hDiff = window.outerHeight - window.innerHeight;
        if (wDiff > 160 || hDiff > 160) {
            reportDevTools('window-resize');
            return true;
        }
        return false;
    }

    // ─────────────────────────────────────────────
    // METODE 4: console.log Object Getter Trick
    // DevTools terbuka => toString() dipanggil browser
    // ─────────────────────────────────────────────
    function checkByConsoleGetter() {
        if (_reported) return;
        let triggered = false;
        const probe = Object.defineProperty({}, 'id', {
            get: function() { triggered = true; }
        });
        // Panggil tanpa menampilkan output ke konsol
        const noop = function() {};
        try {
            const origLog = console.log;
            console.log = noop;
            console.log(probe);
            console.log = origLog;
        } catch(e) {}
        if (triggered) reportDevTools('console-getter');
    }

    // ─────────────────────────────────────────────
    // METODE 5: debugger Timing Trick
    // Saat DevTools terbuka, 'debugger' memakan waktu lebih lama
    // ─────────────────────────────────────────────
    function checkByDebuggerTiming() {
        if (_reported) return;
        const start = performance.now();
        // eslint-disable-next-line no-debugger
        debugger;
        const elapsed = performance.now() - start;
        if (elapsed > 100) {
            reportDevTools('debugger-timing');
        }
    }

    // ─────────────────────────────────────────────
    // Inisialisasi: jalankan cek sekali saat load
    // lalu polling berkala setiap 2 detik
    // ─────────────────────────────────────────────
    setTimeout(function() {
        checkByWindowSize();
        checkByConsoleGetter();
        checkByDebuggerTiming();
    }, 800);

    // Polling resize & console setiap 2 detik
    setInterval(function() {
        if (!_reported) {
            checkByWindowSize();
            checkByConsoleGetter();
        }
    }, 2000);

    // Juga cek saat user meresize window
    window.addEventListener('resize', function() {
        if (!_reported) checkByWindowSize();
    });

})();
</script>
@endif
