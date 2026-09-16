<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karyaku - Detail Verifikasi Transaksi Pembayaran</title>
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
                    colors: { sky: '#0EA5E9', skyHover: '#0284C7', skyDeep: '#0B3D62' }
                }
            }
        }
    </script>
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }

        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-100 via-sky-100/40 to-blue-200/50 text-slate-800 font-sans antialiased min-h-screen">

    <div class="flex min-h-screen relative">
        <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

        @include('verifikator.partials.sidebar')

        <!-- MAIN CONTENT -->
        <main class="flex-1 flex flex-col min-w-0 w-full">
            <header class="bg-white/70 backdrop-blur-xl border-b border-sky-200 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
                <div class="flex items-center gap-4">
                    <button id="sidebarToggleBtn" class="lg:hidden w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-200 shadow-sm"><i class="fa-solid fa-bars text-base"></i></button>
                    <div>
                        <h2 class="text-xl sm:text-2xl font-extrabold tracking-tight font-display text-slate-900">Detail Verifikasi Transaksi</h2>
                        <p class="text-[11px] sm:text-xs text-slate-600 font-semibold mt-0.5">Periksa bukti pembayaran transfer antara Pembeli dan Penjual untuk keamanan transaksi.</p>
                    </div>
                </div>
                <a href="{{ route('verifikator.pembayaran', ['sub' => 'transaksi']) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:border-sky-300 text-slate-700 font-bold text-xs transition shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </header>

            <div class="p-6 sm:p-8 space-y-6">

                <!-- INFORMASI KODE TRANSAKSI & STATUS -->
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold uppercase text-slate-400 tracking-wider">Kode Transaksi Pesanan</span>
                        <h3 class="text-2xl font-extrabold text-slate-900 font-display mt-0.5">#{{ $order->kode_order }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Dibuat pada: {{ $order->created_at ? $order->created_at->translatedFormat('d F Y, H:i') : '-' }} WIB</p>
                    </div>
                    <div>
                        @if($order->payment_status === 'paid')
                            <span class="bg-emerald-100 text-emerald-700 border border-emerald-300 px-4 py-2 rounded-xl text-xs font-extrabold inline-flex items-center gap-2">
                                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i> Lunas (Diverifikasi Verifikator)
                            </span>
                        @elseif(in_array($order->payment_status, ['failed', 'rejected']))
                            <span class="bg-rose-100 text-rose-700 border border-rose-300 px-4 py-2 rounded-xl text-xs font-extrabold inline-flex items-center gap-2">
                                <i class="fa-solid fa-circle-xmark text-rose-600 text-sm"></i> Ditolak Verifikator
                            </span>
                        @else
                            <span class="bg-amber-100 text-amber-800 border border-amber-300 px-4 py-2 rounded-xl text-xs font-extrabold inline-flex items-center gap-2">
                                <i class="fa-solid fa-clock text-amber-600 text-sm"></i> Pending Verifikasi Transfer
                            </span>
                        @endif
                    </div>
                </div>

                <!-- METADATA PEMBELI & RINGKASAN HARGA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <h4 class="font-extrabold text-slate-900 text-sm font-display flex items-center gap-2 border-b pb-3 border-slate-100">
                            <i class="fa-solid fa-user-tag text-sky-500"></i> Informasi Pembeli (Buyer)
                        </h4>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block">Nama Lengkap</span>
                                <span class="font-extrabold text-slate-800 text-sm">{{ $order->buyer->name ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block">Alamat Email</span>
                                <span class="font-semibold text-slate-700">@safeEmail($order->buyer->email ?? '-')</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block">Waktu Submit Transfer</span>
                                <span class="font-semibold text-slate-700">{{ $order->payment_submitted_at ? $order->payment_submitted_at->translatedFormat('d F Y, H:i') : '-' }} WIB</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                        <h4 class="font-extrabold text-slate-900 text-sm font-display flex items-center gap-2 border-b pb-3 border-slate-100">
                            <i class="fa-solid fa-money-bill-transfer text-emerald-500"></i> Rincian Tagihan & Metode
                        </h4>
                        <div class="space-y-2 text-xs">
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block">Metode Pembayaran</span>
                                <span class="font-extrabold text-sky-600 bg-sky-50 border border-sky-200 px-2.5 py-0.5 rounded-lg text-xs inline-block mt-0.5">
                                    {{ $order->payment_method ?? 'Transfer Bank' }}
                                </span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-bold uppercase text-[10px] block">Total Nominal Transaksi</span>
                                <span class="font-extrabold text-emerald-600 text-xl font-display">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DAFTAR ITEM PRODUK -->
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h4 class="font-extrabold text-slate-900 text-sm font-display flex items-center gap-2 border-b pb-3 border-slate-100">
                        <i class="fa-solid fa-boxes-stacked text-indigo-500"></i> Daftar Produk / Jasa yang Dibeli
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-bold border-b border-slate-100">
                                    <th class="py-3 px-4">Produk</th>
                                    <th class="py-3 px-4">Penjual (Seller)</th>
                                    <th class="py-3 px-4 text-center">Jumlah</th>
                                    <th class="py-3 px-4 text-end">Harga Satuan</th>
                                    <th class="py-3 px-4 text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="text-xs divide-y divide-slate-100 font-medium">
                                @foreach($order->items as $item)
                                @php $product = $item->product; @endphp
                                <tr class="hover:bg-slate-50/60">
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($product && $product->thumbnail)
                                                <img src="{{ asset('storage/' . $product->thumbnail) }}" class="w-10 h-10 rounded-lg object-cover border border-slate-200 shrink-0">
                                            @else
                                                <div class="w-10 h-10 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center font-bold text-xs shrink-0"><i class="fa-solid fa-box"></i></div>
                                            @endif
                                            <div>
                                                <p class="font-bold text-slate-800 text-xs">{{ $product->title ?? 'Produk Digital' }}</p>
                                                <span class="text-[10px] text-slate-400">ID Produk: #{{ $item->product_id }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-slate-700">
                                        <span class="font-bold text-slate-800 block">{{ $product->seller->name ?? 'Penjual' }}</span>
                                        <span class="text-[10px] text-slate-400 block">@safeEmail($product->seller->email ?? '-')</span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-bold">{{ $item->quantity }}</td>
                                    <td class="py-3 px-4 text-end text-slate-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="py-3 px-4 text-end font-extrabold text-emerald-600">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BUKTI TRANSFER & RESI PEMBAYARAN -->
                <div class="bg-white border border-sky-200 rounded-2xl p-6 shadow-sm space-y-4">
                    <h4 class="font-extrabold text-slate-900 text-sm font-display flex items-center gap-2 border-b pb-3 border-slate-100">
                        <i class="fa-solid fa-file-invoice-dollar text-sky-600"></i> Pratinjau Foto Resi / Bukti Transfer
                    </h4>

                    @if($order->payment_proof)
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 max-w-lg">
                            <img src="{{ $order->payment_proof_url }}" class="w-full h-auto rounded-xl shadow-sm border border-slate-200">
                            <a href="{{ $order->payment_proof_url }}" target="_blank" class="inline-flex items-center gap-1.5 mt-3 text-xs font-bold text-sky-600 hover:text-skyHover">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Gambar Ukuran Penuh
                            </a>
                        </div>
                    @else
                        <div class="p-6 bg-slate-50 border border-dashed border-slate-300 rounded-2xl text-center text-slate-400 text-xs font-semibold">
                            <i class="fa-solid fa-image text-3xl mb-2 text-slate-300 block"></i>
                            Pembeli belum mengunggah foto bukti transfer.
                        </div>
                    @endif
                </div>

                <!-- AKSI VERIFIKATOR (APPROVE / REJECT) -->
                @if($order->payment_status === 'pending')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- Form Approve -->
                    <div class="bg-white border-l-4 border-l-emerald-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-circle-check text-emerald-500"></i> Setujui Pembayaran Transaksi
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mb-4">Setujui bukti transfer. Produk akan terbuka untuk diunduh oleh Pembeli, dan dana diteruskan ke saldo Penjual.</p>

                        <form id="approveTransaksiForm" action="{{ route('verifikator.transaksi_pembayaran.approve', $order->id_order) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmApproveTransaksi()" class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-check"></i> ✅ Setujui & Teruskan Pembayaran
                            </button>
                        </form>
                    </div>

                    <!-- Form Reject -->
                    <div class="bg-white border-l-4 border-l-red-500 border-y border-r border-slate-200 rounded-2xl p-6 shadow-sm">
                        <h3 class="font-extrabold text-slate-900 text-base font-display mb-1 flex items-center gap-2">
                            <i class="fa-solid fa-circle-xmark text-red-500"></i> Tolak Pembayaran Transaksi
                        </h3>
                        <p class="text-xs text-slate-500 font-medium mb-4">Tolak jika bukti transfer palsu atau nominal tidak sesuai.</p>

                        <form id="rejectTransaksiForm" action="{{ route('verifikator.transaksi_pembayaran.reject', $order->id_order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1">Catatan Penolakan <span class="text-red-500">*</span></label>
                                <textarea id="rejectionNoteInput" name="rejection_note" required placeholder="Tuliskan alasan penolakan bukti transfer..." class="w-full border border-slate-200 rounded-xl p-3 text-xs font-semibold focus:outline-none focus:border-red-400 bg-slate-50 min-h-[80px]"></textarea>
                            </div>

                            <button type="button" onclick="confirmRejectTransaksi()" class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                                <i class="fa-solid fa-xmark"></i> ✕ Tolak Pembayaran
                            </button>
                        </form>
                    </div>

                </div>
                @else
                <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 text-xs font-bold text-slate-700 text-center">
                    Transaksi ini telah diproses sebelumnya dengan status: <span class="uppercase text-sky-600 font-extrabold">{{ $order->payment_status }}</span>
                    @if($order->verifier)
                        &middot; Verifikator: {{ $order->verifier->name }} ({{ $order->verified_at ? $order->verified_at->translatedFormat('d F Y, H:i') : '' }})
                    @endif
                    @if($order->rejection_note)
                        <p class="text-rose-600 text-xs font-semibold mt-1">Catatan Penolakan: {{ $order->rejection_note }}</p>
                    @endif
                </div>
                @endif

            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('open'); sidebar.classList.toggle('closed');
            sidebarOverlay.classList.toggle('hidden');
        }
        if(sidebarToggleBtn) sidebarToggleBtn.addEventListener('click', toggleSidebar);
        if(sidebarCloseBtn) sidebarCloseBtn.addEventListener('click', toggleSidebar);

        function confirmApproveTransaksi() {
            Swal.fire({
                title: 'Setujui Pembayaran Transaksi?',
                text: "Berkas akan terbuka untuk Pembeli dan saldo pendapatan akan masuk ke Penjual.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Setujui!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('approveTransaksiForm').submit();
                }
            });
        }

        function confirmRejectTransaksi() {
            const note = document.getElementById('rejectionNoteInput').value;
            if (!note.trim()) {
                Swal.fire({ icon: 'warning', title: 'Catatan Wajib Diisi', text: 'Tuliskan alasan penolakan bukti pembayaran.', confirmButtonColor: '#0EA5E9' });
                return;
            }
            Swal.fire({
                title: 'Tolak Pembayaran Transaksi Ini?',
                text: "Pembeli akan menerima notifikasi penolakan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Tolak!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('rejectTransaksiForm').submit();
                }
            });
        }

        @if (session('success'))
            Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
        @endif
        @if (session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444' });
        @endif
    </script>
</body>
</html>
