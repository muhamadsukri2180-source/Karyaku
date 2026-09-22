@extends('layouts.admin')

@section('title', 'Karyaku - Kategori Jasa')

@section('header_title', 'Kategori Jasa')
@section('header_subtitle', 'Kelola struktur kategori untuk mengelompokkan layanan/karya kreator.')

@section('content')
    <style>
        .active-menu { background: rgba(255, 255, 255, 0.2); border-left: 4px solid #ffffff; color: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(14, 165, 233, 0.3); border-radius: 10px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        #sidebar { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        @media (max-width: 1023px) { #sidebar.closed { transform: translateX(-100%); } #sidebar.open { transform: translateX(0); } }
        .submenu { max-height: 0; overflow: hidden; transition: max-height .3s ease-in-out; }
        .submenu.open { max-height: 400px; }
        .menu-chevron { transition: transform .3s ease; }
        .menu-chevron.rotated { transform: rotate(180deg); }
        .card-hover { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        .card-hover:hover { transform: scale(1.015) translateY(-3px); box-shadow: 0 15px 30px -10px rgba(14, 165, 233, 0.25); border-color: rgba(14, 165, 233, 0.5); }
        
        /* CUSTOM DROPDOWN ANIMATION CSS */
        .custom-dropdown { position: relative; width: 100%; }
        .dropdown-toggle:checked ~ .trigger i { transform: rotate(180deg); }
        .dropdown-toggle:checked ~ .trigger { border-color: #0ea5e9; background-color: #ffffff; box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15); }
        .dropdown-toggle:checked ~ .list { max-height: 150px; opacity: 1; padding: 0.5rem 0; border-width: 1px; visibility: visible; }
        .dropdown-toggle:not(:checked) ~ .list { max-height: 0; opacity: 0; border-width: 0; padding: 0; visibility: hidden; }
        .list { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>

    <div class="space-y-6">

                @if (session('success'))
                    <script>Swal.fire({icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2500, showConfirmButton: false});</script>
                @endif
                @if (session('error'))
                    <script>Swal.fire({icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#ef4444'});</script>
                @endif

                <!-- SUMMARY CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    <div class="bg-gradient-to-br from-indigo-50 via-white to-indigo-100/60 border-l-4 border-indigo-500 border-y border-r border-indigo-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-indigo-900 uppercase tracking-wider">Total Kategori</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $totalKategori ?? 0 }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-tags text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-50 via-white to-emerald-100/60 border-l-4 border-emerald-500 border-y border-r border-emerald-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Kategori Terpopuler</span><div class="text-xl font-black text-slate-900 mt-2">{{ $kategoriPopuler->name ?? '-' }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-star text-lg"></i></div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-br from-red-50 via-white to-red-100/60 border-l-4 border-red-500 border-y border-r border-red-200 p-5 rounded-2xl card-hover shadow-sm">
                        <div class="flex justify-between items-start mb-2">
                            <div><span class="text-[11px] font-bold text-red-900 uppercase tracking-wider">Kategori Nonaktif</span><div class="text-3xl font-black text-slate-900 mt-1">{{ $kategoriNonaktif ?? 0 }}</div></div>
                            <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center font-bold shadow-md"><i class="fa-solid fa-eye-slash text-lg"></i></div>
                        </div>
                    </div>
                </div>

                <!-- MAIN TABLE AREA -->
                <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="relative w-full sm:w-72">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                            <input type="text" id="categorySearch" onkeyup="filterCategories()" placeholder="Cari nama kategori..." class="pl-8 pr-4 py-2.5 w-full bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:ring-2 focus:ring-sky-500/40 focus:border-sky-500 focus:bg-white transition-all">
                        </div>
                        
                        <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_0_0_#cbd5e1] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#cbd5e1] transition-all cursor-pointer w-full sm:w-auto">
                            <i class="fa-solid fa-plus"></i> Tambah Kategori Baru
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                                    <th class="py-4 px-6">Nama Kategori</th>
                                    <th class="py-4 px-6">Deskripsi Singkat</th>
                                    <th class="py-4 px-6">Total Produk/Jasa</th>
                                    <th class="py-4 px-6">Status</th>
                                    <!-- SUDAH DIUBAH DARI 'Aksi (CRUD)' MENJADI 'Aksi' -->
                                    <th class="py-4 px-6 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody" class="text-sm divide-y divide-slate-100">
                                @forelse($categories as $category)
                                    @php $isActive = ($category->status ?? 'aktif') === 'aktif'; @endphp
                                    <tr class="category-row hover:bg-slate-50 transition-colors bg-white" data-name="{{ strtolower($category->name) }}">
                                        <td class="py-3 px-6">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center text-lg border border-sky-200 shadow-sm"><i class="fa-solid fa-tag"></i></div>
                                                <div>
                                                    <p class="font-bold text-slate-800 text-xs">{{ $category->name }}</p>
                                                    <p class="text-[10px] text-slate-500 font-medium">Slug: {{ \Illuminate\Support\Str::slug($category->name) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-6"><p class="text-[11px] text-slate-600 w-48 truncate">{{ $category->description ?: '-' }}</p></td>
                                        <td class="py-3 px-6"><p class="text-xs font-bold text-sky-700">{{ number_format($category->products_count ?? 0, 0, ',', '.') }} Layanan</p></td>
                                        <td class="py-3 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold {{ $isActive ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-600 border-slate-200' }} border">
                                                {{ $isActive ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-6">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- TOMBOL EDIT -->
                                                <button type="button" onclick='openEditModal(@json($category))' class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center" title="Edit Kategori">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                <!-- TOMBOL HAPUS -->
                                                <form action="{{ url('admin/categories/'.$category->id_category) }}" method="POST" class="inline delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm cursor-pointer flex items-center justify-center" title="Hapus">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-10 text-center text-sm text-slate-500 font-semibold">Belum ada data kategori.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
@endsection

@push('modals')
    <!-- MODAL 1: TAMBAH KATEGORI (Sesuai Gambar 2) -->
    <div id="addModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-emerald-500" id="addModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-emerald-50/50 rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="fa-solid fa-tags text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Kategori Baru</h3>
                        <p class="text-[10px] font-semibold text-emerald-600">Buat kategori layanan baru.</p>
                    </div>
                </div>
                <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="addForm" method="POST" action="{{ route('admin.categories.store') }}" class="p-5 space-y-4">
                @csrf
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">NAMA KATEGORI</label>
                    <input type="text" name="name" id="add_name" placeholder="Masukkan nama kategori" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">DESKRIPSI</label>
                    <textarea name="description" id="add_description" rows="3" placeholder="Deskripsi singkat..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/40 focus:border-emerald-500 focus:bg-white transition-all"></textarea>
                </div>
                
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">STATUS</label>
                    <div class="custom-dropdown mt-1">
                        <input type="hidden" name="status" id="add_status_hidden" value="aktif">
                        <input type="checkbox" id="addDropdownToggle" class="sr-only dropdown-toggle">
                        <label for="addDropdownToggle" class="trigger w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all flex justify-between items-center cursor-pointer">
                            <span id="addSelectedStatusText">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300"></i>
                        </label>
                        <ul class="list absolute z-50 left-0 right-0 top-full mt-2 bg-white border-slate-200 rounded-xl shadow-xl">
                            <li onclick="selectStatus('add', 'aktif', 'Aktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors border-b border-slate-100">Aktif</li>
                            <li onclick="selectStatus('add', 'nonaktif', 'Nonaktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors">Nonaktif</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitAdd()" class="w-full py-3 bg-emerald-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#065f46] hover:bg-emerald-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#065f46] transition-all cursor-pointer">
                        Simpan Kategori Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT KATEGORI (Tampilan Sesuai Gaya Gambar 2 dengan Tema Biru) -->
    <div id="editModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform scale-95 transition-transform duration-300 mx-4 border-t-4 border-blue-600" id="editModalContent">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50 rounded-t-xl">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center"><i class="fa-solid fa-pen-to-square text-sm"></i></div>
                    <div>
                        <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Kategori</h3>
                        <p class="text-[10px] font-semibold text-blue-600">Ubah detail data kategori.</p>
                    </div>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <form id="editForm" method="POST" action="" class="p-5 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">NAMA KATEGORI</label>
                    <input type="text" name="name" id="edit_name" placeholder="Masukkan nama kategori" class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition-all">
                </div>
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">DESKRIPSI</label>
                    <textarea name="description" id="edit_description" rows="3" placeholder="Deskripsi singkat..." class="mt-1 w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 focus:bg-white transition-all"></textarea>
                </div>
                
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">STATUS</label>
                    <div class="custom-dropdown mt-1">
                        <input type="hidden" name="status" id="edit_status_hidden" value="aktif">
                        <input type="checkbox" id="editDropdownToggle" class="sr-only dropdown-toggle">
                        <label for="editDropdownToggle" class="trigger w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-800 transition-all flex justify-between items-center cursor-pointer">
                            <span id="editSelectedStatusText">Aktif</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 transition-transform duration-300"></i>
                        </label>
                        <ul class="list absolute z-50 left-0 right-0 top-full mt-2 bg-white border-slate-200 rounded-xl shadow-xl">
                            <li onclick="selectStatus('edit', 'aktif', 'Aktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors border-b border-slate-100">Aktif</li>
                            <li onclick="selectStatus('edit', 'nonaktif', 'Nonaktif')" class="px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-sky-50 hover:text-sky-600 cursor-pointer transition-colors">Nonaktif</li>
                        </ul>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="button" onclick="submitEdit()" class="w-full py-3 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-[0_4px_0_0_#1e40af] hover:bg-blue-700 active:translate-y-[4px] active:shadow-[0_0_0_0_#1e40af] transition-all cursor-pointer">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endpush

@push('scripts')
<script>
    // Close custom dropdowns when clicking outside
    window.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            const toggles = document.querySelectorAll('.dropdown-toggle');
            toggles.forEach(t => t.checked = false);
        }
    });

    function filterCategories() {
        const q = document.getElementById('categorySearch').value.toLowerCase();
        document.querySelectorAll('#categoryTableBody .category-row').forEach(row => {
            row.style.display = row.dataset.name.includes(q) ? '' : 'none';
        });
    }

    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus Kategori?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) button.closest('form').submit();
        });
    }

    function selectStatus(type, value, text) {
        document.getElementById(`${type}_status_hidden`).value = value;
        document.getElementById(`${type}SelectedStatusText`).textContent = text;
        document.getElementById(`${type}DropdownToggle`).checked = false;
    }

    // --- TAMBAH KATEGORI MODAL LOGIC ---
    const addModal = document.getElementById('addModal');
    const addModalContent = document.getElementById('addModalContent');

    function openAddModal() {
        document.getElementById('addForm').reset();
        selectStatus('add', 'aktif', 'Aktif');
        if (addModal) {
            addModal.classList.remove('hidden');
            setTimeout(() => {
                addModal.classList.remove('opacity-0');
                if(addModalContent) {
                    addModalContent.classList.remove('scale-95');
                    addModalContent.classList.add('scale-100');
                }
            }, 10);
        }
    }

    function closeAddModal() {
        if (addModal) {
            addModal.classList.add('opacity-0');
            if(addModalContent) {
                addModalContent.classList.remove('scale-100');
                addModalContent.classList.add('scale-95');
            }
            setTimeout(() => { addModal.classList.add('hidden'); }, 300);
        }
    }

    function submitAdd() {
        const name = document.getElementById('add_name').value.trim();
        if (!name) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan isi nama kategori', confirmButtonColor: '#0EA5E9' });
            return;
        }
        document.getElementById('addForm').submit();
    }

    // --- EDIT KATEGORI MODAL LOGIC ---
    const editModal = document.getElementById('editModal');
    const editModalContent = document.getElementById('editModalContent');
    let originalEditData = {};

    function openEditModal(category) {
        if(category) {
            originalEditData = {
                name: String(category.name || ''),
                description: String(category.description || ''),
                status: String(category.status || 'aktif')
            };

            document.getElementById('edit_name').value = originalEditData.name;
            document.getElementById('edit_description').value = originalEditData.description;
            let statusText = originalEditData.status === 'aktif' ? 'Aktif' : 'Nonaktif';
            selectStatus('edit', originalEditData.status, statusText);

            document.getElementById('editForm').action = "/admin/categories/" + category.id_category;
        }
        
        if (editModal) {
            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.classList.remove('opacity-0');
                if(editModalContent) {
                    editModalContent.classList.remove('scale-95');
                    editModalContent.classList.add('scale-100');
                }
            }, 10);
        }
    }

    function closeEditModal() {
        if (editModal) {
            editModal.classList.add('opacity-0');
            if(editModalContent) {
                editModalContent.classList.remove('scale-100');
                editModalContent.classList.add('scale-95');
            }
            setTimeout(() => { editModal.classList.add('hidden'); }, 300);
        }
    }

    function submitEdit() {
        const currentData = {
            name: document.getElementById('edit_name').value.trim(),
            description: document.getElementById('edit_description').value.trim(),
            status: document.getElementById('edit_status_hidden').value.trim()
        };

        if (!currentData.name) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan isi nama kategori', confirmButtonColor: '#0EA5E9' });
            return;
        }
        if (currentData.name === originalEditData.name && currentData.description === originalEditData.description && currentData.status === originalEditData.status) {
            Swal.fire({ icon: 'warning', title: 'Peringatan', text: 'Silahkan ubah input yang diperlukan', confirmButtonColor: '#0EA5E9' });
            return;
        }
        document.getElementById('editForm').submit();
    }
</script>
@endpush