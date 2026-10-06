@php
    $info = session('suspended_info') ?? ($info ?? [
        'user_id'          => old('user_id') ?? session('suspended_user_id'),
        'username'         => 'Pengguna',
        'email'            => '',
        'reason'           => 'Pelanggaran syarat dan ketentuan komunitas Karyaku',
        'duration_text'    => 'Permanen (Tanpa batas waktu)',
        'is_permanent'     => true,
        'is_expired'       => false,
        'target_timestamp' => null,
        'appeal_status'    => null,
        'appeal_date'      => null,
        'appeal_admin_note'=> null,
    ]);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Ditangguhkan - Karyaku</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        sky: '#0EA5E9',
                        skyHover: '#0284C7',
                        skyDeep: '#0B3D62',
                        skyPale: '#EFF8FF',
                        ink: '#0F2A44'
                    },
                    fontFamily: {
                        display: ['"Sora"', 'sans-serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif']
                    },
                    boxShadow: {
                        card: '0 10px 40px -10px rgba(11,61,98,0.35)'
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards'
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-attachment: fixed;
        }
        .font-display { font-family: 'Sora', sans-serif; }
        .animation-delay-2000 { animation-delay: 2s; }
        .grain-overlay {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            opacity: 0.05; mix-blend-mode: overlay;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='140' height='140'><filter id='n'><feTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/></filter><rect width='100%25' height='100%25' filter='url(%23n)'/></svg>");
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-600 via-blue-500 to-yellow-400 text-ink antialiased min-h-screen w-full py-10 px-4">

    <!-- Background Animasi -->
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="grain-overlay"></div>
        <div class="absolute -top-20 -left-20 w-80 h-80 bg-blue-300/40 rounded-full blur-[80px] animate-blob"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 bg-yellow-300/40 rounded-full blur-[80px] animate-blob animation-delay-2000"></div>
    </div>

    <!-- Card Utama -->
    <div class="w-full max-w-4xl mx-auto bg-white/95 backdrop-blur-xl p-6 sm:p-8 rounded-[1.8rem] shadow-card border border-white/40 relative z-10 opacity-0 animate-fade-in-up">

        <!-- Top accent bar -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-sky-400 to-blue-600 rounded-t-[1.8rem]"></div>

        <!-- Header Banner -->
        <div class="flex items-center justify-between pb-5 mb-5 border-b border-slate-100">
            <div class="flex items-center gap-3.5">
                <div class="w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center shadow-md border border-slate-100 bg-white shrink-0">
                    <img src="{{ asset('image/logo.png') }}" alt="Karyaku" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="font-display font-extrabold text-xl text-slate-900 leading-tight">Akun Ditangguhkan</h1>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Akses fitur platform Karyaku dibatasi sementara</p>
                </div>
            </div>
            <span class="px-3.5 py-1.5 bg-red-50 text-red-600 text-[10px] font-extrabold rounded-full uppercase tracking-wider border border-red-100 shrink-0">
                Nonaktif
            </span>
        </div>

        <!-- Flash Messages -->
        @if(session('success_appeal'))
            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold p-4 flex items-start gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                <div>
                    <strong class="font-bold block text-sm">Banding Terkirim!</strong>
                    {{ session('success_appeal') }}
                </div>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="mb-6 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold p-4">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Grid 2 Sisi -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

            <!-- SISI KIRI: Detail Penangguhan -->
            <div class="space-y-4">
                <div class="bg-skyPale/70 border border-sky-100 rounded-2xl p-5 space-y-4">
                    <div class="flex justify-between items-center border-b border-sky-200/50 pb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengguna</span>
                        <span class="font-bold text-slate-800 text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-user text-sky"></i> {{ $info['username'] }}
                        </span>
                    </div>

                    @if(!empty($info['email']))
                    <div class="flex justify-between items-center border-b border-sky-200/50 pb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Email</span>
                        <span class="font-medium text-slate-600 text-xs flex items-center gap-1.5">
                            <i class="fa-solid fa-envelope text-sky"></i> {{ $info['email'] }}
                        </span>
                    </div>
                    @endif

                    <div class="border-b border-sky-200/50 pb-3">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-1.5">Alasan Penangguhan</span>
                        <div class="bg-white p-3 rounded-xl border border-sky-100 text-xs text-slate-700 font-medium leading-relaxed">
                            {{ $info['reason'] }}
                        </div>
                    </div>

                    <div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Sisa Waktu Penangguhan</span>

                        @if(!empty($info['target_timestamp']))
                            <div id="countdown-box" class="grid grid-cols-4 gap-2 text-center">
                                <div class="bg-white p-2.5 rounded-xl border border-sky-100 shadow-sm">
                                    <span id="cd-days" class="block text-xl font-extrabold text-skyDeep font-display">00</span>
                                    <span class="text-[9px] uppercase font-bold text-slate-400">Hari</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-sky-100 shadow-sm">
                                    <span id="cd-hours" class="block text-xl font-extrabold text-skyDeep font-display">00</span>
                                    <span class="text-[9px] uppercase font-bold text-slate-400">Jam</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-sky-100 shadow-sm">
                                    <span id="cd-minutes" class="block text-xl font-extrabold text-skyDeep font-display">00</span>
                                    <span class="text-[9px] uppercase font-bold text-slate-400">Menit</span>
                                </div>
                                <div class="bg-white p-2.5 rounded-xl border border-sky-100 shadow-sm">
                                    <span id="cd-seconds" class="block text-xl font-extrabold text-skyDeep font-display">00</span>
                                    <span class="text-[9px] uppercase font-bold text-slate-400">Detik</span>
                                </div>
                            </div>
                            <div id="expired-notice" class="hidden bg-emerald-50 border border-emerald-200 p-3 rounded-xl text-center mt-2">
                                <p class="text-xs font-bold text-emerald-600">
                                    <i class="fa-solid fa-circle-check mr-1"></i> Masa sanksi telah berakhir! Silakan login kembali.
                                </p>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-100 p-3 rounded-xl text-center">
                                <span class="text-xs font-bold text-red-600 flex items-center justify-center gap-1.5">
                                    <i class="fa-solid fa-infinity"></i> {{ $info['duration_text'] }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Info Hubungi via Email -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-3.5 flex items-start gap-2.5">
                    <i class="fa-solid fa-envelope text-blue-400 mt-0.5 shrink-0"></i>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        Untuk pertanyaan, hubungi tim support kami melalui email resmi:
                        <a href="mailto:support@karyaku.com" class="text-blue-600 font-bold hover:underline">support@karyaku.com</a>
                    </p>
                </div>

                <!-- Tombol Kembali -->
                <div class="pt-1">
                    <a href="{{ route('auth.login') }}" class="w-full py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2 border border-slate-200">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
                    </a>
                </div>
            </div>

            <!-- SISI KANAN: Form Pengajuan Banding -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-extrabold text-slate-900 font-display flex items-center gap-2">
                        <i class="fa-solid fa-gavel text-sky"></i> Pengajuan Banding Pemblokiran
                    </h2>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Kirimkan pembelaan kamu untuk ditinjau oleh Admin &amp; CS Karyaku</p>
                </div>

                @if($info['appeal_status'] === 'pending')
                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-xl text-amber-800 text-xs space-y-1.5">
                        <div class="flex items-center gap-2 font-extrabold text-amber-900">
                            <i class="fa-solid fa-clock-rotate-left text-amber-600"></i> Banding Dalam Peninjauan
                        </div>
                        <p class="text-[11px] text-amber-700 leading-relaxed">
                            Pengajuan Anda pada tanggal <strong>{{ $info['appeal_date'] }}</strong> sedang diverifikasi. Hasil keputusan akan diproses secepatnya.
                        </p>
                    </div>

                @else
                    @if($info['appeal_status'] === 'rejected')
                        <div class="bg-rose-50 border border-rose-200 p-3.5 rounded-xl text-rose-700 text-xs">
                            <div class="flex items-center gap-2 font-extrabold mb-1 text-rose-800">
                                <i class="fa-solid fa-circle-xmark text-rose-600"></i> Banding Sebelumnya Ditolak
                            </div>
                            @if($info['appeal_admin_note'])
                                <p class="text-[11px] text-rose-700 bg-white p-2 rounded-lg border border-rose-200 mt-1">
                                    <strong>Catatan Admin:</strong> {{ $info['appeal_admin_note'] }}
                                </p>
                            @endif
                        </div>
                    @endif

                    <form action="{{ route('appeal.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @if(!empty($info['user_id']))
                            <input type="hidden" name="user_id" value="{{ $info['user_id'] }}">
                        @else
                            <div>
                                <label for="account_identifier" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                    Email atau Username Akun Anda <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="account_identifier" id="account_identifier" required placeholder="Masukkan email atau username akun yang dibekukan" class="w-full bg-skyPale/50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition">
                            </div>
                        @endif

                        <div>
                            <label for="reason" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Alasan Pembelaan / Permohonan <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="reason"
                                id="reason"
                                rows="3"
                                required
                                placeholder="Jelaskan alasan detail mengapa penangguhan akun Anda layak dibatalkan..."
                                class="w-full bg-skyPale/50 border border-slate-200 rounded-xl p-3 text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:border-sky focus:ring-2 focus:ring-sky/20 transition"
                            >{{ old('reason') }}</textarea>
                        </div>

                        <!-- Drag and Drop Upload Area -->
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wide mb-1.5">
                                Bukti Pendukung / Screenshot (Opsional)
                            </label>

                            <div id="dropzone" class="border-2 border-dashed border-slate-200 hover:border-sky bg-skyPale/30 hover:bg-skyPale/60 p-4 rounded-xl text-center cursor-pointer transition flex flex-col items-center justify-center gap-1">

                                <!-- Default -->
                                <div id="default-dropzone-content" class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-cloud-arrow-up text-sky text-2xl mb-1"></i>
                                    <p class="text-xs font-bold text-slate-700">Tarik &amp; lepas gambar di sini, atau <span class="text-sky underline">pilih file</span></p>
                                    <span class="text-[10px] text-slate-400">Format: JPG, PNG, WEBP. Maksimal 5MB.</span>
                                </div>

                                <!-- Preview -->
                                <div id="preview-container" class="hidden flex flex-col items-center justify-center gap-1.5 w-full">
                                    <img id="image-preview" src="" alt="Preview Bukti" class="w-16 h-16 object-cover rounded-xl border border-sky-300 shadow-sm">
                                    <p id="file-name-display" class="text-xs font-extrabold text-sky-700 truncate max-w-[220px]"></p>
                                    <span class="text-[10px] text-slate-400 underline">Klik untuk ganti gambar</span>
                                </div>

                                <input type="file" name="proof_image" id="proof_image" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden">
                            </div>
                        </div>

                        <!-- Kirim via Email Info -->
                        <div class="flex items-start gap-2 bg-blue-50 border border-blue-100 rounded-xl p-3">
                            <i class="fa-solid fa-envelope text-blue-400 text-xs mt-0.5 shrink-0"></i>
                            <p class="text-[10px] text-slate-500 leading-relaxed">
                                Pengajuan banding akan dikirimkan ke tim Admin &amp; CS Karyaku dan hasilnya dikonfirmasi melalui <span class="font-bold text-blue-600">email</span> terdaftar Anda.
                            </p>
                        </div>

                        <button type="submit" class="w-full py-3 bg-gradient-to-r from-blue-600 to-sky-500 hover:from-blue-500 hover:to-sky-400 text-white text-xs font-extrabold rounded-xl transition shadow-md shadow-blue-900/20 flex items-center justify-center gap-2 cursor-pointer">
                            <i class="fa-solid fa-paper-plane"></i> Kirim Pengajuan Banding
                        </button>
                    </form>
                @endif
            </div>

        </div>

        <!-- Footer -->
        <div class="mt-6 pt-5 border-t border-slate-100 text-center">
            <div class="flex items-center justify-center gap-1.5 text-[11px] text-slate-400 font-semibold">
                <i class="fa-solid fa-shield-halved text-slate-300"></i>
                <span>Karyaku Security System &bull; {{ date('Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Script Drag and Drop, Image Preview, & Countdown -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('dropzone');
            const fileInput = document.getElementById('proof_image');
            const defaultContent = document.getElementById('default-dropzone-content');
            const previewContainer = document.getElementById('preview-container');
            const imagePreview = document.getElementById('image-preview');
            const fileNameDisplay = document.getElementById('file-name-display');

            if (dropzone && fileInput) {
                dropzone.addEventListener('click', () => fileInput.click());

                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropzone.classList.add('border-sky', 'bg-skyPale');
                });

                ['dragleave', 'dragend'].forEach(type => {
                    dropzone.addEventListener(type, () => {
                        dropzone.classList.remove('border-sky', 'bg-skyPale');
                    });
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('border-sky', 'bg-skyPale');
                    if (e.dataTransfer.files.length) {
                        fileInput.files = e.dataTransfer.files;
                        handleFile(e.dataTransfer.files[0]);
                    }
                });

                fileInput.addEventListener('change', () => {
                    if (fileInput.files.length) {
                        handleFile(fileInput.files[0]);
                    }
                });

                function handleFile(file) {
                    if (!file.type.startsWith('image/')) {
                        alert('Harap unggah file gambar yang valid (JPG, PNG, WEBP).');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        imagePreview.src = e.target.result;
                        defaultContent.classList.add('hidden');
                        previewContainer.classList.remove('hidden');
                        fileNameDisplay.textContent = file.name;
                    }
                    reader.readAsDataURL(file);
                }
            }

            // Countdown Timer
            @if(!empty($info['target_timestamp']))
                const rawTimestamp = {{ $info['target_timestamp'] }};
                const targetTime = rawTimestamp < 10000000000 ? rawTimestamp * 1000 : rawTimestamp;
                const daysEl = document.getElementById('cd-days');
                const hoursEl = document.getElementById('cd-hours');
                const minutesEl = document.getElementById('cd-minutes');
                const secondsEl = document.getElementById('cd-seconds');
                const countdownBox = document.getElementById('countdown-box');
                const expiredNotice = document.getElementById('expired-notice');

                function updateTimer() {
                    const now = new Date().getTime();
                    const difference = targetTime - now;

                    if (difference <= 0) {
                        clearInterval(timerInterval);
                        if (countdownBox) countdownBox.classList.add('hidden');
                        if (expiredNotice) expiredNotice.classList.remove('hidden');
                        return;
                    }

                    const days = Math.floor(difference / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((difference % (1000 * 60)) / 1000);

                    if (daysEl) daysEl.innerText = String(days).padStart(2, '0');
                    if (hoursEl) hoursEl.innerText = String(hours).padStart(2, '0');
                    if (minutesEl) minutesEl.innerText = String(minutes).padStart(2, '0');
                    if (secondsEl) secondsEl.innerText = String(seconds).padStart(2, '0');
                }

                updateTimer();
                const timerInterval = setInterval(updateTimer, 1000);
            @endif
        });
    </script>

</body>
</html>