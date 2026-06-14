<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fdba74; border-radius: 10px; }
        .btn-orange { background-color: #f97316; color: white; border: none; }
        .btn-orange:hover { background-color: #ea580c; color: white; }
        .form-control:focus { border-color: #f97316; box-shadow: 0 0 0 0.25rem rgba(249,115,22,.25); }
        .modal-header { background: linear-gradient(to right, #f97316, #ea580c); color: white; }
        .stat-card { transition: transform .2s, box-shadow .2s; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 30px -5px rgba(249,115,22,.15); }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden"
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }"
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

<div class="flex h-screen overflow-hidden relative">

    {{-- Sidebar --}}
    <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
           class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
        <div class="w-64 h-full">@include('admin.sidebar')</div>
    </aside>

    <div x-show="sidebarOpen" @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

        {{-- Header --}}
        <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h2 class="text-lg font-bold text-gray-800 hidden sm:block">
                    Dashboard<span class="text-orange-500">Admin</span>
                </h2>
            </div>
            <div class="flex items-center gap-3 cursor-pointer group" data-bs-toggle="modal" data-bs-target="#modalEditProfile">
                <p class="text-[10px] text-orange-500 font-bold uppercase tracking-widest hidden sm:block">{{ Auth::user()->name }}</p>
                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 border border-orange-200 shadow-sm group-hover:bg-orange-200 transition-all">
                    <i class="bi bi-person-fill text-xl"></i>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto p-4 md:p-6 custom-scrollbar space-y-6">

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-2xl" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            {{-- HERO BANNER --}}
            <div class="rounded-[2rem] bg-gradient-to-br from-orange-500 to-orange-600 p-6 md:p-8 relative overflow-hidden shadow-xl shadow-orange-200">
                <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 left-20 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>

                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 relative z-10">
                    <div>
                        <p class="text-orange-200 text-xs font-bold uppercase tracking-widest mb-1">Selamat Datang Kembali</p>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">{{ Auth::user()->name }}</h1>
                        <p class="text-orange-100 mt-1 text-sm font-medium">Pantau performa toko Anda hari ini.</p>
                    </div>
                    <div class="bg-white/15 backdrop-blur p-5 rounded-2xl border border-white/20 text-white min-w-[260px]">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-orange-200">Waktu Real-Time</span>
                            <i class="bi bi-clock text-orange-200"></i>
                        </div>
                        <div class="text-3xl font-mono font-bold tracking-tighter" id="clock-time">00:00:00</div>
                        <div class="text-xs font-medium text-orange-100 mt-1" id="clock-date">Memuat...</div>
                    </div>
                </div>
            </div>

            {{-- STAT CARDS - ROW 1: Pendapatan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

                <div class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm col-span-1 sm:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pendapatan</p>
                        <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-cash-coin text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-gray-800">Rp {{ number_format($pendapatanTotal, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">Dari semua pesanan sukses</p>
                </div>

                <div class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm col-span-1 sm:col-span-2">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pendapatan Bulan Ini</p>
                        <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-graph-up text-orange-500"></i>
                        </div>
                    </div>
                    <p class="text-2xl font-black text-orange-600">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</p>
                    <p class="text-[10px] text-gray-400 mt-1 font-medium">{{ now()->translatedFormat('F Y') }}</p>
                </div>
            </div>

            {{-- STAT CARDS - ROW 2: Pesanan --}}
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                <a href="{{ route('admin.orders.index') }}" class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm block">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Pesanan</p>
                        <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-bag-fill text-blue-600"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-gray-800">{{ $totalPesanan }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">Semua transaksi</p>
                </a>

                <a href="{{ route('admin.orders.index') }}" class="stat-card bg-white rounded-[1.5rem] border border-orange-100 p-5 shadow-sm block">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Pending</p>
                        <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-hourglass-split text-orange-500"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-orange-600">{{ $pesananPending }}</p>
                    <p class="text-[10px] text-orange-400 mt-1">Menunggu konfirmasi</p>
                </a>

                <a href="{{ route('admin.orders.process') }}" class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm block">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Diproses</p>
                        <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-gear-wide-connected text-indigo-600"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-indigo-600">{{ $pesananProses }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">Sedang dikemas</p>
                </a>

                <a href="{{ route('admin.orders.success') }}" class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm block">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Sukses</p>
                        <div class="w-9 h-9 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-check2-circle text-green-600"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-black text-green-600">{{ $pesananSukses }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">Selesai diterima</p>
                </a>
            </div>

            {{-- ROW 3: Produk, Affiliator, Pengajuan --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <a href="{{ route('produk.index') }}" class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm flex items-center gap-4 block">
                    <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="bi bi-box-seam-fill text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Produk</p>
                        <p class="text-2xl font-black text-gray-800">{{ $totalProduk }}</p>
                    </div>
                </a>

                <a href="{{ route('admin.affiliator.index') }}" class="stat-card bg-white rounded-[1.5rem] border border-gray-100 p-5 shadow-sm flex items-center gap-4 block">
                    <div class="w-12 h-12 bg-teal-100 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="bi bi-people-fill text-teal-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Affiliator Aktif</p>
                        <p class="text-2xl font-black text-gray-800">{{ $affiliatorAktif }}<span class="text-sm text-gray-400 font-medium"> / {{ $totalAffiliator }}</span></p>
                    </div>
                </a>

                <a href="{{ route('admin.pengajuan-komisi.index') }}" class="stat-card bg-white rounded-[1.5rem] border {{ $pengajuanPending > 0 ? 'border-orange-200 bg-orange-50/50' : 'border-gray-100' }} p-5 shadow-sm flex items-center gap-4 block">
                    <div class="w-12 h-12 {{ $pengajuanPending > 0 ? 'bg-orange-500' : 'bg-gray-100' }} rounded-2xl flex items-center justify-center shrink-0">
                        <i class="bi bi-send-check-fill {{ $pengajuanPending > 0 ? 'text-white' : 'text-gray-400' }} text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black {{ $pengajuanPending > 0 ? 'text-orange-500' : 'text-gray-400' }} uppercase tracking-widest">Pengajuan Komisi</p>
                        <p class="text-2xl font-black {{ $pengajuanPending > 0 ? 'text-orange-600' : 'text-gray-800' }}">{{ $pengajuanPending }} <span class="text-xs font-medium text-gray-400">pending</span></p>
                    </div>
                </a>
            </div>

            {{-- ROW 4: Chart + Pesanan Terbaru --}}
            <div class="grid grid-cols-1 xl:grid-cols-5 gap-6">

                {{-- Grafik Penjualan --}}
                <div class="xl:col-span-3 bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Grafik Penjualan</p>
                            <p class="text-sm font-bold text-gray-700 mt-0.5">6 Bulan Terakhir</p>
                        </div>
                        <div class="w-9 h-9 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="bi bi-bar-chart-fill text-orange-500"></i>
                        </div>
                    </div>
                    <canvas id="salesChart" height="120"></canvas>
                </div>

                {{-- Pesanan Terbaru --}}
                <div class="xl:col-span-2 bg-white rounded-[2rem] border border-gray-100 p-6 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Pesanan Terbaru</p>
                            <p class="text-sm font-bold text-gray-700 mt-0.5">5 Transaksi Terakhir</p>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" class="text-[10px] font-black text-orange-500 hover:text-orange-600 uppercase tracking-widest">Lihat Semua →</a>
                    </div>

                    <div class="space-y-3 flex-1">
                        @forelse($pesananTerbaru as $p)
                        <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 hover:bg-orange-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-black
                                    {{ $p->status === 'success' ? 'bg-green-100 text-green-600' : 
                                       ($p->status === 'pending' ? 'bg-orange-100 text-orange-600' : 
                                       ($p->status === 'process' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500')) }}">
                                    <i class="bi {{ $p->status === 'success' ? 'bi-check-lg' : ($p->status === 'pending' ? 'bi-hourglass' : ($p->status === 'process' ? 'bi-gear' : 'bi-truck')) }}"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700 truncate max-w-[110px]">{{ $p->nomor_pesanan }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $p->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-black text-gray-700">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</p>
                                <span class="text-[8px] font-black px-2 py-0.5 rounded-full uppercase
                                    {{ $p->status === 'success' ? 'bg-green-100 text-green-600' : 
                                       ($p->status === 'pending' ? 'bg-orange-100 text-orange-600' : 
                                       ($p->status === 'process' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-500')) }}">
                                    {{ $p->status }}
                                </span>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 text-gray-300">
                            <i class="bi bi-inbox text-4xl block mb-2"></i>
                            <p class="text-xs font-medium">Belum ada pesanan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- Modal Edit Profil --}}
<div class="modal fade" id="modalEditProfile" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-2xl rounded-3xl overflow-hidden">
            <div class="modal-header border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-person-circle me-2"></i> Edit Profil Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf @method('PUT')
                <div class="modal-body p-4">
                    @if($errors->any())
                    <div class="alert alert-danger rounded-xl py-2 px-3 mb-4">
                        <ul class="mb-0 small">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-gray-600">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control form-control-lg rounded-xl text-sm" value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-gray-600">Alamat Email</label>
                        <input type="email" name="email" class="form-control form-control-lg rounded-xl text-sm" value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-2xl border border-orange-100 mt-4">
                        <p class="text-[11px] text-orange-700 font-bold uppercase mb-2 tracking-wider">Ganti Password (Opsional)</p>
                        <div class="mb-3">
                            <label class="form-label small text-gray-500">Password Baru</label>
                            <input type="password" name="password" class="form-control rounded-xl text-sm" placeholder="Isi jika ingin ganti">
                        </div>
                        <div class="mb-0">
                            <label class="form-label small text-gray-500">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control rounded-xl text-sm" placeholder="Ulangi password baru">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-xl px-4 text-sm fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-orange rounded-xl px-4 text-sm fw-bold shadow-sm">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Jam real-time
    function updateClock() {
        const now = new Date();
        document.getElementById('clock-time').innerText = now.toLocaleTimeString('id-ID', { hour:'2-digit', minute:'2-digit', second:'2-digit', hour12:false, timeZone:'Asia/Jakarta' });
        document.getElementById('clock-date').innerText  = now.toLocaleDateString('id-ID', { weekday:'long', day:'numeric', month:'long', year:'numeric', timeZone:'Asia/Jakarta' });
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Grafik Penjualan
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const grafikData = @json($grafikData);

    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: grafikData.map(d => d.bulan),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData.map(d => d.total),
                backgroundColor: grafikData.map((d, i) => i === grafikData.length - 1 ? '#f97316' : '#fed7aa'),
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw)
                    }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, weight: 'bold' } } },
                y: {
                    grid: { color: '#f3f4f6' },
                    ticks: {
                        font: { size: 10 },
                        callback: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
                    }
                }
            }
        }
    });

    // Auto buka modal jika ada error validasi
    @if($errors->any())
        new bootstrap.Modal(document.getElementById('modalEditProfile')).show();
    @endif
</script>

</body>
</html>
