<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Notifikasi - Admin</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        skyDeep: '#0284c7',
                        skyHover: '#0369a1',
                        sky: '#0e7490'
                    }
                }
            }
        }
    </script>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .active-menu {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-weight: 700;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex min-h-screen">
        <!-- SIDEBAR FULL -->
        <aside id="sidebar" class="w-[260px] bg-gradient-to-b from-skyDeep via-skyHover to-sky text-white flex flex-col shrink-0 border-r border-sky-400/20 shadow-2xl fixed lg:sticky top-0 h-screen z-50 closed lg:translate-x-0">
            <div class="p-6 border-b border-white/15 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white text-sky flex items-center justify-center text-lg font-bold shadow-lg"><i class="fa-solid fa-layer-group"></i></div>
                    <div>
                        <h1 class="font-display font-extrabold text-[17px] leading-none tracking-wide text-white">Karyaku</h1>
                        <span class="text-[9px] text-sky-200 font-bold uppercase tracking-[0.2em] mt-1 block">Admin Panel</span>
                    </div>
                </div>
                <button id="sidebarCloseBtn" class="lg:hidden text-white/80 hover:text-white p-2"><i class="fa-solid fa-xmark text-lg"></i></button>
            </div>

            <div class="p-4 mx-4 my-5 rounded-2xl bg-white/10 border border-white/20 flex items-center gap-3 backdrop-blur-md shadow-inner">
                <div class="w-10 h-10 rounded-full bg-white text-sky flex items-center justify-center font-bold text-sm shadow shrink-0">{{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}</div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                        <p class="text-[10px] text-sky-100 truncate">Online</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 space-y-1.5 text-[13px] font-semibold text-sky-100 overflow-y-auto pb-4">
                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-4">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all duration-200">
                    <i class="fa-solid fa-chart-pie w-4 text-center"></i><span>Dashboard</span>
                </a>

                <div>
                    <button type="button" data-menu="pengguna" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-users w-4 text-center group-hover:text-white transition-colors"></i><span>Manajemen Pengguna</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="pengguna"></i>
                    </button>
                    <div class="submenu hidden pl-4 mt-1 space-y-1" data-submenu="pengguna">
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-user text-[10px] text-sky-200 w-3 text-center"></i> Akun Pengguna
                        </a>
                        <a href="{{ route('admin.users.verifikator') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-id-card text-[10px] text-sky-200 w-3 text-center"></i> Akun Verifikator
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="katalog" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-box-open w-4 text-center group-hover:text-white transition-colors"></i><span>Katalog & Kategori</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="katalog"></i>
                    </button>
                    <div class="submenu hidden pl-4 mt-1 space-y-1" data-submenu="katalog">
                        <a href="{{ route('admin.products') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-list-check text-[10px] text-sky-200 w-3 text-center"></i> Daftar Jasa
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-tags text-[10px] text-sky-200 w-3 text-center"></i> Kategori Jasa
                        </a>
                    </div>
                </div>

                <div>
                    <button type="button" data-menu="transaksi" class="menu-toggle w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                        <i class="fa-solid fa-receipt w-4 text-center group-hover:text-white transition-colors"></i><span>Keuangan</span>
                        <i class="fa-solid fa-chevron-down text-[10px] ml-auto menu-chevron" data-chevron="transaksi"></i>
                    </button>
                    <div class="submenu hidden pl-4 mt-1 space-y-1" data-submenu="transaksi">
                        <a href="{{ route('admin.transactions') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-clock-rotate-left text-[10px] text-sky-200 w-3 text-center"></i> Riwayat Pesanan
                        </a>
                        <a href="{{ route('admin.withdrawals') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-lg hover:bg-white/10 hover:text-white transition-all text-xs">
                            <i class="fa-solid fa-wallet text-[10px] text-sky-200 w-3 text-center"></i> Penarikan Saldo
                        </a>
                    </div>
                </div>

                <a href="{{ route('admin.memberships') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-crown w-4 text-center text-amber-300"></i><span>Paket Membership</span>
                </a>

                <p class="px-3.5 text-[10px] font-bold uppercase tracking-wider text-sky-200/70 mb-2 mt-6">Sistem</p>
                <a href="{{ route('admin.maintenance') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group">
                    <i class="fa-solid fa-server w-4 text-center text-white"></i><span>Maintenance & Backup</span>
                </a>
                <a href="{{ route('admin.pelanggaran') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-white/10 hover:text-white transition-all group mt-1">
                    <i class="fa-solid fa-triangle-exclamation w-4 text-center text-white"></i><span>Pelanggaran</span>
                </a>
                <a href="{{ route('admin.notifications.index') }}" class="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl active-menu transition-all group mt-1">
                    <i class="fa-solid fa-bell w-4 text-center text-white"></i><span>Notifikasi</span>
                </a>
            </nav>
            <div class="p-4 border-t border-white/15">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-2 w-full py-2.5 rounded-xl bg-red-600/80 text-white hover:bg-red-700 text-xs font-bold transition-all duration-300 shadow-md">
                        <i class="fa-solid fa-power-off"></i><span>Keluar Sistem</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Manajemen Notifikasi</h1>
                <button onclick="openModal('addNotificationModal')" class="px-4 py-2 bg-skyDeep hover:bg-skyHover text-white rounded-xl text-sm font-semibold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Tambah Notifikasi
                </button>
            </div>

            {{-- Alert Success / Error --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tabel Notifikasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                            <th class="p-4">Nama Notifikasi</th>
                            <th class="p-4">Deskripsi</th>
                            <th class="p-4">Tanggal Dibuat</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">
                        @forelse ($notifications as $notification)
                            <tr class="hover:bg-gray-50/50">
                                <td class="p-4 font-medium">{{ $notification->name }}</td>
                                <td class="p-4 text-gray-600 max-w-xs truncate">{{ $notification->description }}</td>
                                <td class="p-4 text-gray-500">{{ $notification->created_at ? $notification->created_at->format('d M Y, H:i') : '-' }}</td>
                                <td class="p-4 text-center space-x-2">
                                    <button 
                                        type="button" 
                                        class="btn-edit-notification px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold rounded-lg transition"
                                        data-id="{{ $notification->id }}"
                                        data-name="{{ $notification->name }}"
                                        data-description="{{ $notification->description }}">
                                        Edit
                                    </button>
                                    <button 
                                        type="button" 
                                        class="btn-delete-notification px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded-lg transition"
                                        data-id="{{ $notification->id }}"
                                        data-name="{{ $notification->name }}">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-6 text-center text-gray-400">Belum ada data notifikasi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="p-4">
                    {{ $notifications->links() }}
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL TAMBAH NOTIFIKASI -->
    <div id="addNotificationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Notifikasi Baru</h3>
            <form action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Notifikasi</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-skyDeep text-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-skyDeep text-sm" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('addNotificationModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-skyDeep hover:bg-skyHover shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL EDIT NOTIFIKASI -->
    <div id="editNotificationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Notifikasi</h3>
            <form id="editNotificationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Nama Notifikasi</label>
                    <input type="text" id="editNotificationName" name="name" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-skyDeep text-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Deskripsi</label>
                    <textarea id="editNotificationDescription" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-skyDeep text-sm" required></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeModal('editNotificationModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-md">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL HAPUS NOTIFIKASI -->
    <div id="deleteNotificationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-xl text-center">
            <h3 class="text-lg font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-xs text-gray-600 mb-6">Apakah Anda yakin ingin menghapus notifikasi <span id="deleteNotificationName" class="font-bold text-gray-900"></span>?</p>
            <form id="deleteNotificationForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex justify-center gap-2">
                    <button type="button" onclick="closeModal('deleteNotificationModal')" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100">Batal</button>
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-red-600 hover:bg-red-700 shadow-md">Ya, Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT JS HANDLER MODAL & SUBMENU TOGGLE -->
    <script>
        function openModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const menuToggles = document.querySelectorAll('.menu-toggle');
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const menuKey = this.getAttribute('data-menu');
                    const submenu = document.querySelector(`[data-submenu="${menuKey}"]`);
                    const chevron = document.querySelector(`[data-chevron="${menuKey}"]`);

                    if (submenu) {
                        submenu.classList.toggle('hidden');
                    }
                    if (chevron) {
                        chevron.classList.toggle('rotate-180');
                    }
                });
            });

            const editButtons = document.querySelectorAll('.btn-edit-notification');
            const editForm = document.getElementById('editNotificationForm');

            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const description = this.getAttribute('data-description');

                    editForm.action = `/admin/notifications/${id}`;
                    document.getElementById('editNotificationName').value = name || '';
                    document.getElementById('editNotificationDescription').value = description || '';

                    openModal('editNotificationModal');
                });
            });

            const deleteButtons = document.querySelectorAll('.btn-delete-notification');
            const deleteForm = document.getElementById('deleteNotificationForm');
            const deleteNameSpan = document.getElementById('deleteNotificationName');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    deleteForm.action = `/admin/notifications/${id}`;
                    if (deleteNameSpan) deleteNameSpan.textContent = name;

                    openModal('deleteNotificationModal');
                });
            });
        });
    </script>
</body>
</html>