@extends('layouts.admin')

@section('title', 'Karyaku - Dashboard Admin')

@section('header_title', 'Ikhtisar Panel')
@section('header_subtitle', 'Pantau statistik penjualan, verifikasi produk, dan aktivitas user.')

@section('header_right')
    @if($isMaintenance)
        <a href="{{ route('admin.maintenance') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[11px] font-bold shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> Maintenance Aktif
        </a>
    @endif
    
    @php 
        $pendingProductsCount = \App\Models\Product::where('status', 'pending')->count();
        $totalNotif = ($pendingIdentityCount ?? 0) + $pendingProductsCount + ($pendingReportsCount ?? 0); 
    @endphp

    <!-- Tampilan Ikon Notifikasi di Header -->
    <a href="{{ route('admin.notifications.index') }}" class="relative w-10 h-10 rounded-xl bg-white hover:bg-slate-50 text-slate-700 flex items-center justify-center transition border border-sky-300 shadow-sm">
        <i class="fa-solid fa-bell text-base"></i>
        @if($totalNotif > 0)
            <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-coral text-[10px] font-bold text-white shadow-sm ring-2 ring-white">
                {{ $totalNotif }}
            </span>
        @endif
    </a>
@endsection

@section('content')
<!-- TOP METRICS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

   <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-skyDeep">Total Transaksi Selesai</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-display">Rp {{ number_format($totalGrossVolume, 0, ',', '.') }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/90 text-skyDeep flex items-center justify-center text-xl shadow-lg border border-white">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-skyDeep">Akun Verifikator</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-display">{{ number_format($totalVerifikator) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/90 text-skyDeep flex items-center justify-center text-xl shadow-lg border border-white">
                <i class="fa-solid fa-user-shield"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-skyDeep">Total Pengguna</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-display">{{ number_format($totalUsers) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/90 text-skyDeep flex items-center justify-center text-xl shadow-lg border border-white">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-wider text-skyDeep">Pending Identitas KTP</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1 font-display">{{ number_format($pendingIdentityCount) }}</h3>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-white/90 text-skyDeep flex items-center justify-center text-xl shadow-lg border border-white">
                <i class="fa-solid fa-address-card"></i>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS & ACTION SECTION -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white/80 backdrop-blur-md p-6 sm:p-7 rounded-3xl border border-sky-200/80 shadow-xl space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="text-lg font-bold text-slate-900 font-display">Grafik Pertumbuhan Transaksi Selesai</h3>
                <p id="chartSubtitle" class="text-xs text-slate-700 mt-1 font-semibold">Pertumbuhan transaksi berdasarkan data order (Tahun {{ $currentYear }})</p>
            </div>
            
            <div class="dropdown">
                <input type="checkbox" id="yearDropdownToggle" class="sr-only" />
                <label for="yearDropdownToggle" class="trigger">
                    <span id="selectedYearText">Tahun {{ $currentYear }}</span>
                </label>
                <ul class="list webkit-scrollbar" id="yearList">
                </ul>
            </div>
        </div>

        <div class="h-[320px] w-full pt-4">
            <canvas id="yearlyChart"></canvas>
        </div>
    </div>

    <!-- PENDING ITEMS QUICK VIEW -->
    <div class="bg-white/80 backdrop-blur-md p-6 sm:p-7 rounded-3xl border border-sky-200/80 shadow-xl flex flex-col justify-between space-y-6">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-sky-100">
                <h3 class="text-lg font-bold text-slate-900 font-display">Antrean Pekerjaan</h3>
                <span class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-sky-100 text-skyDeep">Perlu Ditinjau</span>
            </div>

            <div class="space-y-4 mt-5">
                <div class="p-4 rounded-2xl bg-gradient-to-r from-skyPale to-sky-50 border border-sky-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-skyDeep flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-id-card"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Verifikasi Identitas</p>
                            <p class="text-[11px] font-semibold text-slate-700">{{ $pendingIdentityCount }} Pengajuan Baru</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.users.verifikator') }}" class="px-3 py-1.5 rounded-xl bg-sky text-white text-[11px] font-bold hover:bg-skyHover transition">Proses</a>
                </div>

                <div class="p-4 rounded-2xl bg-gradient-to-r from-skyPale to-sky-50 border border-sky-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-skyDeep flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-box"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Persetujuan Produk</p>
                            <p class="text-[11px] font-semibold text-slate-700">{{ $pendingProductsCount }} Produk Pending</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.products') }}" class="px-3 py-1.5 rounded-xl bg-sky text-white text-[11px] font-bold hover:bg-skyHover transition">Tinjau</a>
                </div>

                <div class="p-4 rounded-2xl bg-gradient-to-r from-skyPale to-sky-50 border border-sky-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-skyDeep flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Laporan Masuk</p>
                            <p class="text-[11px] font-semibold text-slate-700">{{ $pendingReportsCount }} Laporan Baru</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.pelanggaran') }}" class="px-3 py-1.5 rounded-xl bg-sky text-white text-[11px] font-bold hover:bg-skyHover transition">Detail</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- RECENT ACTIVITY LOG & SYSTEM INFO -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white/80 backdrop-blur-md p-6 sm:p-7 rounded-3xl border border-sky-200/80 shadow-xl space-y-6">
        <h3 class="text-lg font-bold text-slate-900 font-display">Aktivitas Sistem Terkini</h3>
        
        <div class="space-y-4">
            @forelse($recentActivities as $activity)
                <div class="flex items-start gap-4 p-3.5 rounded-2xl hover:bg-sky-50/50 transition border border-transparent hover:border-sky-100">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-skyDeep flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">
                        <i class="fa-solid {{ $activity['icon'] }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-900">{{ $activity['title'] }}</p>
                        <p class="text-[11px] font-semibold text-slate-700 mt-0.5">{{ $activity['desc'] }}</p>
                        <span class="text-[9px] font-bold text-slate-500 block mt-1">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <p class="text-xs font-semibold text-slate-700 pl-5">Belum ada aktivitas terbaru.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const initialChartData = @json($chartData);
    const initialYear = {{ $currentYear }};
    const chartDataUrl = "{{ route('admin.dashboard.chartData') }}";
    let yearlyChartInstance = null;

    document.addEventListener('DOMContentLoaded', function () {
        const startYear = 2026;
        const currentRealYear = new Date().getFullYear();
        const maxYear = Math.max(startYear, currentRealYear, initialYear);

        const yearListEl = document.getElementById('yearList');
        const selectedTextEl = document.getElementById('selectedYearText');
        const checkboxEl = document.getElementById('yearDropdownToggle');
        const subtitleEl = document.getElementById('chartSubtitle');

        if(selectedTextEl) selectedTextEl.textContent = `Tahun ${initialYear}`;

        if(yearListEl) {
            for (let y = maxYear; y >= startYear; y--) {
                const li = document.createElement('li');
                li.className = 'listitem';
                const isSelected = y === initialYear ? 'selected' : '';
                li.innerHTML = `<div class="article ${isSelected}" data-year="${y}">Tahun ${y}</div>`;

                li.querySelector('.article').addEventListener('click', function () {
                    document.querySelectorAll('#yearList .article').forEach(el => el.classList.remove('selected'));
                    this.classList.add('selected');

                    selectedTextEl.textContent = `Tahun ${y}`;
                    subtitleEl.textContent = `Pertumbuhan transaksi berdasarkan data order (Tahun ${y})`;
                    checkboxEl.checked = false;
                    fetchChartData(y);
                });
                yearListEl.appendChild(li);
            }
        }

        const canvas = document.getElementById('yearlyChart');
        if (canvas) {
            const ctx = canvas.getContext('2d');

            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.45)');
            gradient.addColorStop(1, 'rgba(14, 165, 233, 0.0)');

            yearlyChartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [
                        {
                            label: 'Jumlah Transaksi (Order)',
                            data: initialChartData,
                            borderColor: '#0EA5E9',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#0EA5E9',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F2A44',
                            titleFont: { size: 11, family: 'Plus Jakarta Sans' },
                            bodyFont: { size: 13, weight: 'bold', family: 'Plus Jakarta Sans' },
                            padding: 10,
                            displayColors: false,
                            cornerRadius: 8
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(14, 165, 233, 0.1)', drawBorder: false },
                            ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#334155' }
                        },
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { size: 11, family: 'Plus Jakarta Sans' }, color: '#334155' }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                }
            });
        }
    });

    function fetchChartData(year) {
        fetch(`${chartDataUrl}?year=${year}`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(res => res.json())
            .then(json => {
                if(yearlyChartInstance) {
                    yearlyChartInstance.data.datasets[0].data = json.data;
                    yearlyChartInstance.update();
                }
            })
            .catch(err => console.error('Gagal memuat data grafik:', err));
    }
</script>
@endpush