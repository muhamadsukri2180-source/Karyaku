<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Pusat Bantuan & Customer Service</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = { 
            theme: { 
                extend: { 
                    fontFamily: { sans: ['Plus Jakarta Sans', 'sans-serif'], display: ['Sora', 'sans-serif'] },
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62', skyPale: '#EFF8FF' }
                } 
            } 
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen p-4 sm:p-6">
    <div class="max-w-6xl mx-auto space-y-6">
        
        <!-- Header Nav -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 bg-white/80 backdrop-blur-md p-6 rounded-2xl shadow-sm border border-sky-100">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 font-display">Pusat Layanan Customer Service</h1>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Sampaikan kendala, masalah, atau masukan Anda terkait layanan Karyaku.</p>
            </div>
            <a href="{{ route('pembeli.dashboard') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-circle-check"></i></div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Selesai</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['selesai'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-spinner animate-spin"></i></div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Sedang Proses</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['proses'] ?? 0 }}</h3>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-rose-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg"><i class="fa-solid fa-clock"></i></div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wide">Belum Diproses</p>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-0.5">{{ $stats['belum'] ?? 0 }}</h3>
                </div>
            </div>
        </div>

        <!-- GRID FORM & TABLE -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Form Input Keluhan -->
            <div class="bg-white p-6 rounded-2xl border border-sky-100 shadow-sm h-fit space-y-4">
                <div class="flex items-center gap-3 border-b border-slate-100 pb-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky flex items-center justify-center font-bold"><i class="fa-solid fa-headset"></i></div>
                    <h3 class="font-display font-extrabold text-base text-slate-900">Buat Tiket Bantuan</h3>
                </div>

                <form action="{{ route('pembeli.service.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Subjek Kendala / Masalah</label>
                        <input type="text" name="subject" required placeholder="Contoh: Kendala Unduh File Jasa" class="w-full border border-slate-200 bg-skyPale/40 rounded-xl px-4 py-2.5 text-xs font-semibold focus:bg-white focus:outline-none focus:border-sky transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Detail Pesan / Masukan</label>
                        <textarea name="message" rows="4" required placeholder="Jelaskan masalah atau masukkan Anda secara lengkap..." class="w-full border border-slate-200 bg-skyPale/40 rounded-xl p-4 text-xs font-semibold focus:bg-white focus:outline-none focus:border-sky transition"></textarea>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-md transition flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Laporan
                    </button>
                </form>
            </div>

            <!-- Table Riwayat Tiket User -->
            <div class="lg:col-span-2 bg-white rounded-2xl border border-sky-100 shadow-sm overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="font-display font-extrabold text-base text-slate-900">Riwayat & Status Keluhan Anda</h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                <th class="py-3.5 px-5">Subjek</th>
                                <th class="py-3.5 px-5">Pesan</th>
                                <th class="py-3.5 px-5">Status</th>
                                <th class="py-3.5 px-5">Tanggapan Admin / CS</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs divide-y divide-slate-100">
                            @forelse($tickets ?? [] as $t)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-5 font-bold text-slate-800">{{ $t->subject }}</td>
                                <td class="py-3 px-5 text-slate-600 max-w-xs truncate">{{ $t->message }}</td>
                                <td class="py-3.5 px-5">
                                    @if($t->status == 'selesai')
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg font-bold border border-emerald-200">Selesai</span>
                                    @elseif($t->status == 'proses')
                                        <span class="px-2.5 py-1 bg-amber-50 text-amber-600 rounded-lg font-bold border border-amber-200">Proses</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg font-bold border border-rose-200">Belum</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 italic">{{ $t->admin_note ?? 'Belum ada tanggapan' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-400 font-semibold">Belum ada keluhan atau masukan yang Anda kirim.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
    </script>
</body>
</html>