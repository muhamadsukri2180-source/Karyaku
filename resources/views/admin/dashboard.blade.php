@extends('layouts.admin')

@section('title', 'Karyaku - Dashboard Admin')
@section('header_title', 'Ikhtisar Panel')
@section('header_subtitle', 'Pantau statistik penjualan, verifikasi produk, dan aktivitas user.')

@section('header_right')
    @php
        $isMaintenance = \App\Models\AllowedIp::where('ip_address', 'MAINTENANCE_MODE')->exists() ?? false;
        $pendingProductsCount = \App\Models\Product::where('status', 'pending')->count();
        $totalNotif = ($pendingIdentityCount ?? 0) + $pendingProductsCount + ($pendingReportsCount ?? 0);
    @endphp

    @if($isMaintenance)
        <a href="{{ route('admin.maintenance') }}" class="hidden sm:flex items-center gap-2 px-3 py-2 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[11px] font-bold shadow-sm">
            <i class="fa-solid fa-triangle-exclamation"></i> Maintenance Aktif
        </a>
    @endif

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
@php
    $pendingProductsCount = \App\Models\Product::where('status', 'pending')->count();
@endphp

<!-- TOP METRICS CARDS -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    <!-- Card 1: Total Pesanan -->
    <div class="bg-gradient-to-br from-sky-100 via-sky-200 to-blue-300/70 border-l-4 border-sky-500 border border-sky-300 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="blob-live absolute top-0 right-0 -mr-4 -mt-4 w-28 h-28 rounded-full bg-sky-400 group-hover:scale-[1.8] group-hover:opacity-40 transition-all duration-700"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-sky-900 uppercase tracking-wider group-hover:text-sky-600 transition-colors">Total Pesanan</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalOrders, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-sky-600 text-white flex items-center justify-center font-bold shadow-md shadow-sky-500/40">
                <i class="fa-solid fa-bag-shopping text-lg group-hover:scale-110 transition-transform duration-300"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <span class="bg-emerald-100 text-emerald-900 text-[10px] font-extrabold px-2 py-0.5 rounded-md flex items-center gap-1 shadow-sm">
                <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($monthlySales, 0, ',', '.') }}
            </span>
            <span class="text-[10px] text-slate-600 font-medium">Pesanan bulan ini</span>
        </div>
    </div>

    <!-- Card 2: Komisi Platform -->
    <div class="bg-gradient-to-br from-emerald-50 via-emerald-100/60 to-teal-200/50 border-l-4 border-emerald-500 border border-emerald-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-emerald-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Komisi Platform</span>
                <div class="text-2xl sm:text-3xl font-black text-slate-900 mt-1">Rp {{ number_format($platformCommission, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-md shadow-emerald-500/40">
                <i class="fa-solid fa-wallet text-lg group-hover:scale-110 transition-transform duration-300"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-emerald-200 shadow-sm px-2 py-0.5 rounded-md">
                5% dari Rp {{ number_format($totalRevenue, 0, ',', '.') }}
            </span>
        </div>
    </div>

    <!-- Card 3: Produk -->
    <div class="bg-gradient-to-br from-amber-50 via-amber-100/60 to-orange-200/50 border-l-4 border-amber-500 border border-amber-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-amber-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider group-hover:text-amber-600 transition-colors">Total Produk</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalProducts, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-amber-600 text-white flex items-center justify-center font-bold shadow-md shadow-amber-500/40">
                <i class="fa-solid fa-swatchbook text-lg group-hover:scale-110 transition-transform duration-300"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <span class="bg-amber-200 text-amber-900 border border-amber-300 text-[10px] font-bold px-2 py-0.5 rounded-md flex items-center gap-1 shadow-sm">
                <i class="fa-regular fa-clock"></i> {{ $pendingProductsCount }} Antrean
            </span>
            <span class="text-[10px] text-slate-600 font-medium">Verifikasi</span>
        </div>
    </div>

    <!-- Card 4: Pengguna -->
    <div class="bg-gradient-to-br from-purple-50 via-purple-100/60 to-indigo-200/50 border-l-4 border-purple-500 border border-purple-200 p-5 rounded-2xl card-hover relative overflow-hidden group shadow-md">
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 rounded-full bg-purple-400 opacity-20 group-hover:scale-[1.8] group-hover:opacity-30 transition-all duration-700"></div>
        <div class="flex justify-between items-start mb-4 relative z-10">
            <div>
                <span class="text-[11px] font-bold text-purple-900 uppercase tracking-wider group-hover:text-purple-600 transition-colors">Pengguna Aktif</span>
                <div class="text-3xl font-black text-slate-900 mt-1">{{ number_format($totalUsers, 0, ',', '.') }}</div>
            </div>
            <div class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center font-bold shadow-md shadow-purple-500/40">
                <i class="fa-solid fa-users text-lg group-hover:scale-110 transition-transform duration-300"></i>
            </div>
        </div>
        <div class="flex items-center gap-2 relative z-10">
            <span class="text-[10px] text-slate-700 font-medium bg-white/80 border border-purple-200 shadow-sm px-2 py-0.5 rounded-md">
                Penjual & Pembeli
            </span>
        </div>
    </div>

</div>

<!-- SECTION 1: GRAFIK FULL WIDTH -->
<div class="bg-gradient-to-br from-white via-sky-50/70 to-blue-100/50 border border-sky-300/80 p-6 rounded-2xl card-hover shadow-md">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h3 class="font-extrabold text-slate-900 text-lg font-display">Statistik Pemesanan Jasa</h3>
            <p id="chartSubtitle" class="text-[11px] text-slate-600 mt-1">Pertumbuhan transaksi berdasarkan data order (Tahun {{ $year }})</p>
        </div>

        <!-- Custom Real-time Dynamic Year Dropdown (Starts from 2026 onwards) -->
        <div class="dropdown">
            <input type="checkbox" id="yearDropdownToggle" class="sr-only">
            <label for="yearDropdownToggle" class="trigger">
                <span id="selectedYearText">Tahun {{ $year }}</span>
            </label>
            <ul id="yearList" class="list webkit-scrollbar">
                <!-- Populated dynamically via JS starting from 2026 -->
            </ul>
        </div>
    </div>

    <div class="h-64 w-full">
        <canvas id="yearlyChart"></canvas>
    </div>
</div>

<!-- SECTION 2: AKTIVITAS TERKINI FULL WIDTH -->
<div class="bg-gradient-to-br from-white via-emerald-50/50 to-teal-100/50 border border-emerald-200/80 p-6 rounded-2xl card-hover shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h3 class="font-extrabold text-slate-900 text-lg font-display flex items-center gap-2">
            <i class="fa-solid fa-bolt text-emerald-600"></i> Aktivitas Terkini
        </h3>
    </div>

    <div class="relative border-l-2 border-emerald-300 ml-3 space-y-6">
        @php
            $dotBg = ['emerald' => 'bg-emerald-200', 'sky' => 'bg-sky-200', 'amber' => 'bg-amber-200'];
            $dotInner = ['emerald' => 'bg-emerald-600', 'sky' => 'bg-sky-600', 'amber' => 'bg-amber-600'];
        @endphp
        @forelse($recentActivities as $activity)
            <div class="relative pl-5 hover:translate-x-1 transition-transform cursor-default">
                <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full {{ $dotBg[$activity['color']] ?? 'bg-slate-200' }} border-2 border-white flex items-center justify-center shadow">
                    <div class="w-1.5 h-1.5 rounded-full {{ $dotInner[$activity['color']] ?? 'bg-slate-600' }}"></div>
                </div>
                <p class="text-xs font-bold text-slate-900">{{ $activity['title'] }}</p>
                <p class="text-[11px] text-slate-600 mt-0.5">{{ $activity['desc'] }}</p>
                <span class="text-[9px] font-bold text-slate-400 block mt-1">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</span>
            </div>
        @empty
            <p class="text-xs text-slate-500 pl-5">Belum ada aktivitas terbaru.</p>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script>
    let yearlyChartInstance;

    const initialYear = {{ (int) $year }};
    const initialChartData = @json($chartData);
    const chartDataUrl = "{{ route('admin.dashboard.chartData') }}";

    document.addEventListener('DOMContentLoaded', function () {
        const startYear = 2026;
        const currentRealYear = new Date().getFullYear();
        const maxYear = Math.max(startYear, currentRealYear, initialYear);

        const yearListEl = document.getElementById('yearList');
        const selectedTextEl = document.getElementById('selectedYearText');
        const checkboxEl = document.getElementById('yearDropdownToggle');
        const subtitleEl = document.getElementById('chartSubtitle');

        if (selectedTextEl) selectedTextEl.textContent = `Tahun ${initialYear}`;

        if (yearListEl) {
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