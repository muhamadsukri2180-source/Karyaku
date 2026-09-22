@extends('layouts.admin')

@section('title', 'Karyaku - Daftar Jasa')

@section('header_title', 'Daftar Jasa (Produk)')
@section('header_subtitle', 'Tinjau, setujui, dan kelola semua layanan/karya yang ditawarkan kreator.')

@section('content')

                @if(session('success'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm mb-6">
                        <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 text-sm font-semibold px-4 py-3 rounded-xl shadow-sm mb-6">
                        <i class="fa-solid fa-circle-xmark mr-1"></i> {{ session('error') }}
                    </div>
                @endif

                <!-- SUMMARY CARDS (rata penuh - 2 kolom sejajar mengisi lebar halaman) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6">
                    <div class="bg-gradient-to-br from-amber-50 via-white to-amber-100/60 border-l-4 border-amber-500 border-y border-r border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Menunggu Tinjauan</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($pendingCount, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/30"><i class="fa-solid fa-clock text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-amber-200/50 pt-2 mt-2">Jasa baru yang perlu disetujui admin</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-sm">
                        <div class="flex justify-between items-start mb-2 relative z-10">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Jasa Aktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($activeCount, 0, ',', '.') }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/30"><i class="fa-solid fa-box-open text-lg"></i></div>
                        </div>
                        <p class="text-[10px] text-slate-600 font-medium border-t border-emerald-200/50 pt-2 mt-2">Sudah tayang di katalog Karyaku</p>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-gradient-to-b from-white to-sky-50/30 border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <!-- SUB-TABS NAVIGATION (Gaya Verifikator) -->
                    <div class="flex border-b border-sky-200 gap-4 px-5 pt-4 bg-white/50 backdrop-blur-sm">
                        <a href="{{ route('admin.products', ['tab' => 'pending']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? 'pending') === 'pending' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-clock mr-1.5"></i> Antrean Pending ({{ $pendingCount }})
                        </a>
                        <a href="{{ route('admin.products', ['tab' => 'active']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? '') === 'active' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-box-open mr-1.5"></i> Katalog Aktif ({{ $activeCount }})
                        </a>
                        <a href="{{ route('admin.products', ['tab' => 'all']) }}" class="pb-3 px-2 text-xs sm:text-sm font-bold border-b-2 transition-all {{ ($tab ?? '') === 'all' ? 'border-sky text-sky-600' : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                            <i class="fa-solid fa-list-check mr-1.5"></i> Semua Jasa ({{ $allCount ?? ($pendingCount + $activeCount) }})
                        </a>
                    </div>

                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white/50 backdrop-blur-sm">
                        <form method="GET" action="{{ route('admin.products') }}" class="relative w-full sm:w-72">
                            <input type="hidden" name="tab" value="{{ $tab ?? 'pending' }}">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama jasa..." class="pl-8 pr-4 py-2 w-full bg-white border border-sky-200 rounded-xl text-xs font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/30 focus:border-sky-500 transition-all shadow-sm">
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-sky-50/80 border-b border-sky-100 text-sky-900 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Informasi Jasa</th>
                                    <th class="py-4 px-6">Kreator</th>
                                    <th class="py-4 px-6">Harga Mulai</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm divide-y divide-sky-100/70">
                                @forelse($products as $product)
                                    @php
                                        $statusColor = match($product->status) {
                                            'pending' => 'text-amber-700 bg-amber-100 border-amber-200',
                                            'active' => 'text-emerald-700 bg-emerald-100 border-emerald-200',
                                            'inactive' => 'text-slate-600 bg-slate-100 border-slate-200',
                                            default => 'text-slate-600 bg-slate-100 border-slate-200',
                                        };
                                        $statusLabel = match($product->status) {
                                            'pending' => 'Menunggu',
                                            'active' => 'Aktif',
                                            'inactive' => 'Nonaktif',
                                            default => ucfirst($product->status),
                                        };
                                    @endphp
                                    <tr class="hover:bg-sky-50/50 transition-colors bg-white">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                @php
                                                    $thumb = $product->thumbnail ?? (is_array($product->images) ? ($product->images[0] ?? null) : null);
                                                @endphp
                                                <div class="w-12 h-10 rounded-lg bg-slate-200 flex items-center justify-center overflow-hidden border border-slate-300 shrink-0">
                                                    @if(!empty($thumb) && \Illuminate\Support\Facades\Storage::disk('public')->exists($thumb))
                                                        <img src="{{ asset('storage/' . $thumb) }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full bg-gradient-to-tr from-sky-400 to-blue-600 text-white flex items-center justify-center text-xs font-bold"><i class="fa-solid fa-box-open"></i></div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs line-clamp-1">{{ $product->title }}</p>
                                                    <p class="text-[10px] text-sky-600 font-bold mt-0.5">{{ $product->category->name ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-xs font-bold text-slate-700">{{ $product->seller->name ?? '-' }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <p class="text-xs font-bold text-emerald-600">Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}</p>
                                        </td>
                                        <td class="py-3 px-6">
                                            <span class="text-[10px] font-bold {{ $statusColor }} px-2.5 py-1 rounded-md border">{{ $statusLabel }}</span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    onclick="openReviewModal('{{ $product->id_product }}', '{{ addslashes($product->title) }}', '{{ addslashes($product->category->name ?? '-') }}', '{{ addslashes($product->seller->name ?? '-') }}', 'Rp {{ number_format($product->price ?? 0, 0, ',', '.') }}', '{{ addslashes(str_replace(["\r", "\n"], ' ', $product->description ?? '-')) }}', '{{ !empty($thumb) && \Illuminate\Support\Facades\Storage::disk('public')->exists($thumb) ? asset('storage/' . $thumb) : '' }}', '{{ $product->status }}')"
                                                    class="px-2.5 py-1.5 rounded-lg bg-sky-50 text-sky-600 border border-sky-200 hover:bg-sky-600 hover:text-white transition-all text-xs font-bold shadow-sm flex items-center gap-1" title="Tinjau Detail Produk">
                                                    <i class="fa-solid fa-eye text-xs"></i> <span>Tinjau</span>
                                                </button>
                                                @if($product->status === 'pending')
                                                    <button type="button" onclick="confirmApproveProduct('{{ route('admin.products.approve', $product->id_product) }}', '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Setujui"><i class="fa-solid fa-check text-xs"></i></button>
                                                    <button type="button" onclick="confirmTakedown('{{ route('admin.products.takedown', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Tolak / takedown produk ini?')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Tolak"><i class="fa-solid fa-xmark text-xs"></i></button>
                                                @elseif($product->status === 'active')
                                                    <button type="button" onclick="confirmTakedown('{{ route('admin.products.takedown', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Takedown produk ini dari katalog?')" class="px-2.5 py-1.5 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 hover:bg-amber-600 hover:text-white transition-all text-[11px] font-bold shadow-sm"><i class="fa-solid fa-ban"></i> Takedown</button>
                                                @else
                                                    <button type="button" onclick="confirmApproveProduct('{{ route('admin.products.approve', $product->id_product) }}', '{{ addslashes($product->title) }}', 'Aktifkan kembali produk ini?')" class="px-2.5 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-all text-[11px] font-bold shadow-sm"><i class="fa-solid fa-rotate-left"></i> Aktifkan</button>
                                                @endif
                                                <button type="button" onclick="confirmDeleteProduct('{{ route('admin.products.delete', $product->id_product) }}', '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm flex items-center justify-center" title="Hapus Permanen">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500">Belum ada data produk/jasa pada tab ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($products->hasPages())
                        <div class="p-5 border-t border-sky-100 bg-white/50">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>

            </div>
        </main>
    </div>

    <!-- MODAL: TINJAU PRODUK -->
    <div id="reviewProductModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
        <div class="modal-overlay absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeModal('reviewProductModal')"></div>
        <div class="modal-box relative bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 overflow-hidden">
            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                <h3 class="font-display font-extrabold text-base text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-eye text-sky"></i> Tinjau Jasa / Produk
                </h3>
                <button type="button" onclick="closeModal('reviewProductModal')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Gambar Produk / Jasa</span>
                        <button type="button" id="reviewImgZoomBtn" onclick="const img=document.getElementById('reviewImg'); if(img && img.src) window.open(img.src, '_blank')" class="text-[11px] text-sky-600 hover:text-sky-700 font-semibold hidden flex items-center gap-1">
                            <i class="fa-solid fa-up-right-from-square"></i> Buka Gambar Penuh
                        </button>
                    </div>
                    <div id="reviewImgContainer" class="w-full min-h-[160px] max-h-72 rounded-xl bg-slate-900/5 overflow-hidden border border-slate-200 flex items-center justify-center p-2 relative group">
                        <img id="reviewImg" src="" alt="" class="max-h-64 w-full object-contain hidden cursor-pointer transition-transform hover:scale-[1.01]" onclick="if(this.src) window.open(this.src, '_blank')" title="Klik untuk melihat gambar ukuran penuh">
                        <div id="reviewImgPlaceholder" class="flex flex-col items-center text-slate-400">
                            <i class="fa-solid fa-image text-3xl mb-1"></i>
                            <span class="text-xs font-semibold">Tidak Ada Gambar</span>
                        </div>
                    </div>
                </div>

                <div>
                    <span id="reviewCategory" class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-sky-100 text-sky-700">Kategori</span>
                    <h4 id="reviewTitle" class="text-lg font-bold text-slate-900 mt-1">Judul Jasa</h4>
                    <p class="text-xs font-bold text-emerald-600 mt-0.5" id="reviewPrice">Rp 0</p>
                </div>

                <div class="grid grid-cols-2 gap-3 text-xs bg-slate-50 p-3 rounded-xl border border-slate-200/80">
                    <div>
                        <span class="text-slate-400 font-semibold text-[10px] block">KREATOR</span>
                        <span id="reviewSeller" class="font-bold text-slate-800">Kreator Name</span>
                    </div>
                    <div>
                        <span class="text-slate-400 font-semibold text-[10px] block">STATUS</span>
                        <span id="reviewStatus" class="font-bold text-slate-800">Status</span>
                    </div>
                </div>

                <div>
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Deskripsi</label>
                    <div id="reviewDescription" class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200/80 max-h-36 overflow-y-auto leading-relaxed">
                        -
                    </div>
                </div>
            </div>

            <div id="reviewModalActions" class="mt-6 flex items-center justify-end gap-2 border-t border-slate-100 pt-4">
                <button type="button" id="reviewApproveBtn" class="hidden px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Setujui & Terbitkan
                </button>
                <button type="button" id="reviewRejectBtn" class="hidden px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm flex items-center gap-1.5">
                    <i class="fa-solid fa-xmark"></i> Tolak / Takedown
                </button>
                <button type="button" onclick="closeModal('reviewProductModal')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition-all">
                    Tutup
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    function openModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.remove('hidden');
            el.classList.add('flex');
        }
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.add('hidden');
            el.classList.remove('flex');
        }
    }

    function confirmTakedown(actionUrl, title, customMsg) {
        Swal.fire({
            title: 'Takedown / Tolak Produk?',
            text: customMsg || `Apakah Anda yakin ingin menolak / takedown jasa "${title}" dari katalog?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Takedown!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmApproveProduct(actionUrl, title, customMsg) {
        Swal.fire({
            title: 'Setujui Produk?',
            text: customMsg || `Apakah Anda yakin ingin menyetujui dan menerbitkan jasa "${title}" ke katalog?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;
                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmDeleteProduct(actionUrl, title) {
        Swal.fire({
            title: 'Hapus Produk Permanen?',
            text: `Anda akan menghapus jasa "${title}" secara permanen. Tindakan ini tidak dapat dibatalkan.`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = actionUrl;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                const method = document.createElement('input');
                method.type = 'hidden';
                method.name = '_method';
                method.value = 'DELETE';
                form.appendChild(method);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function openReviewModal(id, title, category, seller, price, description, imgUrl, status) {
        document.getElementById('reviewTitle').textContent = title;
        document.getElementById('reviewCategory').textContent = category;
        document.getElementById('reviewSeller').textContent = seller;
        document.getElementById('reviewPrice').textContent = price;
        document.getElementById('reviewDescription').textContent = description || 'Tidak ada deskripsi.';
        document.getElementById('reviewStatus').textContent = status === 'active' ? 'Aktif' : (status === 'pending' ? 'Menunggu Approval' : 'Nonaktif');

        const approveBtn = document.getElementById('reviewApproveBtn');
        const rejectBtn = document.getElementById('reviewRejectBtn');

        if (status === 'pending') {
            const approveUrl = `{{ url('admin/products/approve') }}/${id}`;
            const rejectUrl = `{{ url('admin/products/takedown') }}/${id}`;
            approveBtn.onclick = function() {
                closeModal('reviewProductModal');
                confirmApproveProduct(approveUrl, title);
            };
            rejectBtn.onclick = function() {
                closeModal('reviewProductModal');
                confirmTakedown(rejectUrl, title, 'Tolak / takedown produk ini?');
            };
            approveBtn.classList.remove('hidden');
            rejectBtn.classList.remove('hidden');
        } else {
            approveBtn.classList.add('hidden');
            rejectBtn.classList.add('hidden');
        }

        const imgEl = document.getElementById('reviewImg');
        const placeholderEl = document.getElementById('reviewImgPlaceholder');
        const zoomBtn = document.getElementById('reviewImgZoomBtn');
        if (imgUrl && imgUrl.trim() !== '') {
            imgEl.src = imgUrl;
            imgEl.classList.remove('hidden');
            placeholderEl.classList.add('hidden');
            if (zoomBtn) zoomBtn.classList.remove('hidden');
        } else {
            imgEl.src = '';
            imgEl.classList.add('hidden');
            placeholderEl.classList.remove('hidden');
            if (zoomBtn) zoomBtn.classList.add('hidden');
        }

        openModal('reviewProductModal');
    }
</script>
@endpush