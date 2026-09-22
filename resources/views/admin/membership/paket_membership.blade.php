@extends('layouts.admin')

@section('title', 'Paket Membership')
@section('header_title', 'Paket Membership')
@section('header_subtitle', 'Kelola paket langganan premium kreator.')

@section('content')
<div class="p-6 sm:p-8 space-y-6">

    <!-- SUMMARY CARDS -->
    @php
        $allMemberships = $memberships ?? collect();
        $totPaketCount = $totalPaket ?? $allMemberships->count();
        $totSubscribers = $totalPenjualBerlangganan ?? $allMemberships->sum('users_count');
        $topPackageName = isset($paketTerlaris) && $paketTerlaris ? $paketTerlaris->name : '-';
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-sky-50 to-white border border-sky-200 p-5 rounded-2xl shadow-sm">
            <span class="text-[10px] font-extrabold text-sky-800 uppercase tracking-widest block">Total Membership</span>
            <div class="flex items-end justify-between mt-2">
                <div class="text-3xl font-black text-slate-900">{{ $totPaketCount }}</div>
                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center font-bold shadow-sm"><i class="fa-solid fa-crown text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-500 font-medium mt-2">Total pilihan paket yang tersedia</p>
        </div>

        <div class="bg-gradient-to-br from-emerald-50 to-white border border-emerald-200 p-5 rounded-2xl shadow-sm">
            <span class="text-[10px] font-extrabold text-emerald-800 uppercase tracking-widest block">Penjual Berlangganan</span>
            <div class="flex items-end justify-between mt-2">
                <div class="text-3xl font-black text-slate-900">{{ $totSubscribers }}</div>
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold shadow-sm"><i class="fa-solid fa-users text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-500 font-medium mt-2">Aktif menggunakan paket</p>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-white border border-amber-200 p-5 rounded-2xl shadow-sm">
            <span class="text-[10px] font-extrabold text-amber-800 uppercase tracking-widest block">Paket Terlaris</span>
            <div class="flex items-end justify-between mt-2">
                <div class="text-xl font-extrabold text-slate-900 truncate max-w-[180px]">{{ $topPackageName }}</div>
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center font-bold shadow-sm"><i class="fa-solid fa-fire text-lg"></i></div>
            </div>
            <p class="text-[10px] text-slate-500 font-medium mt-2">Paling banyak diminati kreator</p>
        </div>
    </div>

    <!-- TABEL PAKET MEMBERSHIP -->
    <div class="bg-white border border-sky-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-sky-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-extrabold text-slate-900 text-lg font-display">Daftar Paket Membership</h3>
                <p class="text-xs text-slate-500 font-medium mt-0.5">Kelola paket membership yang dapat dibeli oleh penjual.</p>
            </div>
            
            <button type="button" onclick="openAddModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all cursor-pointer">
                <i class="fa-solid fa-plus"></i> Tambah Paket Membership
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-[11px] uppercase tracking-wider font-bold">
                        <th class="py-4 px-6">Nama Paket</th>
                        <th class="py-4 px-6">Harga / Siklus</th>
                        <th class="py-4 px-6">Maksimal Upload</th>
                        <th class="py-4 px-6">Benefit & Fitur Tercentang</th>
                        <th class="py-4 px-6">Pelanggan</th>
                        <th class="py-4 px-6 text-center w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-slate-100">
                    @forelse ($memberships ?? [] as $membership)
                    <tr class="hover:bg-slate-50 transition-colors bg-white">
                        <td class="py-3.5 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-sky-500 to-blue-600 text-white flex items-center justify-center text-lg shadow-sm">
                                    @if(stripos($membership->name, 'Diamond') !== false)
                                        <i class="fa-regular fa-gem"></i>
                                    @elseif(stripos($membership->name, 'Silver') !== false)
                                        <i class="fa-solid fa-medal"></i>
                                    @else
                                        <i class="fa-solid fa-award"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-extrabold text-slate-800 text-xs">{{ $membership->name }}</p>
                                    <p class="text-[10px] text-slate-500 font-semibold">Durasi {{ $membership->duration_days }} hari</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-6 font-bold text-emerald-600 text-xs">
                            Rp {{ number_format($membership->price, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-6 font-extrabold text-slate-700 text-xs">
                            {{ $membership->max_upload >= 999 ? '999 (Unlimited)' : $membership->max_upload . ' Karya' }}
                        </td>
                        <td class="py-3.5 px-6">
                            <div class="text-[11px] text-slate-600 space-y-1">
                                @foreach(explode(' | ', $membership->benefit) as $benefitItem)
                                    @if(trim($benefitItem))
                                        <div class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check text-emerald-500 text-[10px]"></i> <span>{{ trim($benefitItem) }}</span></div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200">
                                {{ $membership->users_count ?? 0 }} Pengguna
                            </span>
                        </td>
                        <td class="py-3.5 px-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick='openEditModal(@json($membership))' class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Edit Centang Benefit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                <form action="{{ route('admin.memberships.delete', $membership->id_membership) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus Paket">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-slate-400 text-xs font-semibold">Belum ada paket membership.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- MODAL TAMBAH MEMBERSHIP -->
<div id="addModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4 my-6 overflow-hidden max-h-[90vh] flex flex-col" id="addModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm"><i class="fa-solid fa-plus text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Tambah Paket Membership</h3>
                    <p class="text-[10px] font-semibold text-slate-500">Pilih centang benefit & atur harga paket.</p>
                </div>
            </div>
            <button type="button" onclick="closeAddModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="addForm" method="POST" action="{{ route('admin.memberships.store') }}" class="p-5 space-y-4 overflow-y-auto">
            @csrf
            
            <div>
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Paket Membership</label>
                <input type="text" name="name" required placeholder="Misal: Bronze Plan" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition mt-1">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Harga (Rp)</label>
                    <input type="number" name="price" required placeholder="25000" class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition mt-1">
                </div>

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Durasi (Hari)</label>
                    <input type="number" name="duration_days" value="30" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition mt-1">
                </div>

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Max Upload Karya</label>
                    <input type="number" name="max_upload" value="5" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-emerald-500 focus:bg-white transition mt-1">
                </div>
            </div>

            <!-- CENTANG BENEFIT -->
            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Centang Fitur / Benefit Paket</label>
                <div class="space-y-2 text-xs font-semibold text-slate-700">
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Kuota Upload Produk (Sesuai Limit)" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Fitur Kuota Upload Produk (Sesuai Limit)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Fitur Promosi & Iklan Video Produk" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Fitur Promosi & Iklan Video Produk</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Lencana / Badge Kreator Terverifikasi (Centang Biru)" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Lencana / Badge Kreator Terverifikasi (Centang Biru)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Prioritas Layanan Customer Service 24/7" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Prioritas Layanan Customer Service 24/7</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Laporan Keuangan & Statistik Penjualan" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Laporan Keuangan & Statistik Penjualan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-emerald-400 transition">
                        <input type="checkbox" name="benefits[]" value="Bebas Biaya Komisi Platform" class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <span>Bebas Biaya Komisi Platform</span>
                    </label>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-emerald-600 text-white text-xs font-bold rounded-xl hover:bg-emerald-700 transition cursor-pointer shadow-md">
                    Simpan Paket Membership
                </button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDIT MEMBERSHIP -->
<div id="editModal" class="fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-sm hidden transition-opacity duration-300 opacity-0 w-screen h-screen">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg transform scale-95 transition-transform duration-300 mx-4 my-6 overflow-hidden max-h-[90vh] flex flex-col border-t-4 border-blue-500" id="editModalContent">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-blue-50/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shadow-sm"><i class="fa-solid fa-pen-to-square text-sm"></i></div>
                <div>
                    <h3 class="font-extrabold text-slate-900 text-base font-display">Edit Centang Benefit Paket</h3>
                    <p class="text-[10px] font-semibold text-blue-600">Atur centang benefit & ubah data paket membership.</p>
                </div>
            </div>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-red-500 transition-colors w-8 h-8 rounded-full hover:bg-red-50 flex items-center justify-center"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form id="editForm" method="POST" action="" class="p-5 space-y-4 overflow-y-auto">
            @csrf
            @method('PUT')
            
            <div>
                <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Nama Paket Membership</label>
                <input type="text" id="edit_name" name="name" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition mt-1">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Harga (Rp)</label>
                    <input type="number" id="edit_price" name="price" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition mt-1">
                </div>

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Durasi (Hari)</label>
                    <input type="number" id="edit_duration" name="duration_days" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition mt-1">
                </div>

                <div>
                    <label class="text-[10px] font-extrabold text-slate-700 uppercase tracking-wide">Max Upload Karya</label>
                    <input type="number" id="edit_max_upload" name="max_upload" required class="w-full border border-slate-200 bg-slate-50 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:border-blue-500 focus:bg-white transition mt-1">
                </div>
            </div>

            <!-- CENTANG BENEFIT EDIT -->
            <div class="border border-slate-200 rounded-xl p-4 bg-slate-50/50 space-y-3">
                <label class="text-[11px] font-extrabold text-slate-800 uppercase tracking-wide block">Pilih Centang Benefit Paket</label>
                <div class="space-y-2 text-xs font-semibold text-slate-700">
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Kuota Upload Produk (Sesuai Limit)" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Fitur Kuota Upload Produk (Sesuai Limit)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Fitur Promosi & Iklan Video Produk" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Fitur Promosi & Iklan Video Produk</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Lencana / Badge Kreator Terverifikasi (Centang Biru)" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Lencana / Badge Kreator Terverifikasi (Centang Biru)</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Prioritas Layanan Customer Service 24/7" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Prioritas Layanan Customer Service 24/7</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Laporan Keuangan & Statistik Penjualan" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Laporan Keuangan & Statistik Penjualan</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer bg-white p-2.5 rounded-lg border border-slate-200 hover:border-blue-400 transition">
                        <input type="checkbox" name="benefits[]" value="Bebas Biaya Komisi Platform" class="edit-benefit-cb w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                        <span>Bebas Biaya Komisi Platform</span>
                    </label>
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition cursor-pointer shadow-md">
                    Update Paket Membership
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const addModal = document.getElementById('addModal');
    const addModalContent = document.getElementById('addModalContent');

    function openAddModal() {
        document.getElementById('addForm').reset();
        addModal.classList.remove('hidden');
        setTimeout(() => {
            addModal.classList.remove('opacity-0');
            addModalContent.classList.remove('scale-95');
            addModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeAddModal() {
        addModal.classList.add('opacity-0');
        addModalContent.classList.remove('scale-100');
        addModalContent.classList.add('scale-95');
        setTimeout(() => { addModal.classList.add('hidden'); }, 300);
    }

    const editModal = document.getElementById('editModal');
    const editModalContent = document.getElementById('editModalContent');

    function openEditModal(membership) {
        if(membership) {
            document.getElementById('editForm').action = "/admin/memberships/" + (membership.id_membership || membership.id);
            document.getElementById('edit_name').value = membership.name || '';
            document.getElementById('edit_price').value = Math.round(membership.price || 0);
            document.getElementById('edit_duration').value = membership.duration_days || 30;
            document.getElementById('edit_max_upload').value = membership.max_upload || 5;

            const benefitText = membership.benefit || '';
            document.querySelectorAll('.edit-benefit-cb').forEach(cb => {
                const keyVal = cb.value.split(' ')[0];
                cb.checked = benefitText.includes(cb.value) || benefitText.toLowerCase().includes(keyVal.toLowerCase());
            });
        }
        
        editModal.classList.remove('hidden');
        setTimeout(() => {
            editModal.classList.remove('opacity-0');
            editModalContent.classList.remove('scale-95');
            editModalContent.classList.add('scale-100');
        }, 10);
    }

    function closeEditModal() {
        editModal.classList.add('opacity-0');
        editModalContent.classList.remove('scale-100');
        editModalContent.classList.add('scale-95');
        setTimeout(() => { editModal.classList.add('hidden'); }, 300);
    }

    function confirmDelete(button) {
        Swal.fire({
            title: 'Hapus Paket Membership?',
            text: "Data paket ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                button.closest('form').submit();
            }
        });
    }
</script>
@endpush