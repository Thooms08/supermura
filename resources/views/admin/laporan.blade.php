<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Laporan | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    <style>
    [x-cloak] { display: none !important; }

    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-0'" 
               class="transition-all duration-300 bg-white border-r fixed inset-y-0 z-50 lg:relative shadow-2xl lg:shadow-none">
            <div class="w-64">@include('admin.sidebar')</div>
        </aside>

        <div class="flex-1 flex flex-col h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-orange-50 text-orange-600"><i class="bi bi-list text-2xl"></i></button>
                    <h2 class="text-lg font-bold">Laporan <span class="text-orange-600">Analitik</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 lg:p-10 space-y-8">
                
                <form action="{{ route('admin.laporan.index') }}" method="GET" class="bg-white p-6 rounded-3xl shadow-sm border border-orange-100 flex flex-wrap items-end gap-4">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Dari Tanggal</label>
                        <input type="date" name="start" value="{{ $start }}" class="block w-full border-gray-200 rounded-xl focus:ring-orange-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Sampai Tanggal</label>
                        <input type="date" name="end" value="{{ $end }}" class="block w-full border-gray-200 rounded-xl focus:ring-orange-500">
                    </div>
                    <button type="submit" class="bg-orange-600 text-white px-8 h-11 rounded-xl font-bold shadow-lg shadow-orange-100">Terapkan Filter</button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-8 bg-gradient-to-br from-orange-500 to-orange-600 rounded-[2.5rem] text-white shadow-xl shadow-orange-100 relative overflow-hidden">
                        <i class="bi bi-currency-dollar absolute -right-4 -bottom-4 text-9xl opacity-10"></i>
                        <p class="text-xs font-bold uppercase tracking-widest opacity-80">Summary Omzet</p>
                        <h3 class="text-4xl font-black mt-2">Rp {{ number_format($omzet, 0, ',', '.') }}</h3>
                    </div>
                    <div class="p-8 bg-white border-2 border-orange-600 rounded-[2.5rem] text-orange-600 shadow-sm relative overflow-hidden">
                        <p class="text-xs font-bold uppercase tracking-widest opacity-60">Total Pesanan Berhasil</p>
                        <h3 class="text-4xl font-black mt-2">{{ $totalPenjualan }} Transaksi</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-[3rem] border shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="font-black uppercase text-xs tracking-widest text-gray-400">Grafik Omzet</h4>
                            <button onclick="downloadChart('salesChart')" class="text-orange-600 font-bold text-xs hover:underline"><i class="bi bi-download me-1"></i> PNG</button>
                        </div>
                        <canvas id="salesChart" height="200"></canvas>
                        <div class="flex gap-2 pt-4">
                            <a href="?start={{ date('Y-m-d') }}&end={{ date('Y-m-d') }}" class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-bold">1H</a>
                            <a href="?start={{ date('Y-m-d', strtotime('-1 week')) }}&end={{ date('Y-m-d') }}" class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-bold">1W</a>
                            <a href="?start={{ date('Y-m-d', strtotime('-1 month')) }}&end={{ date('Y-m-d') }}" class="px-3 py-1 bg-gray-100 rounded-lg text-[10px] font-bold">1M</a>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[3rem] border shadow-sm space-y-4">
                        <div class="flex justify-between items-center">
                            <h4 class="font-black uppercase text-xs tracking-widest text-gray-400">Grafik Refund</h4>
                            <button onclick="downloadChart('refundChart')" class="text-red-600 font-bold text-xs hover:underline"><i class="bi bi-download me-1"></i> PNG</button>
                        </div>
                        <canvas id="refundChart" height="200"></canvas>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-6">
                    <a href="{{ route('admin.laporan.refund', ['start' => $start, 'end' => $end]) }}" class="flex-1 p-10 bg-gray-900 rounded-[3rem] text-white flex items-center justify-between hover:bg-orange-600 transition-all group">
                        <span class="text-2xl font-black">LAPORAN REFUND</span>
                        <i class="bi bi-arrow-right-circle text-4xl opacity-20 group-hover:opacity-100"></i>
                    </a>
                    <a href="{{ route('admin.laporan.penjualan', ['start' => $start, 'end' => $end]) }}" class="flex-1 p-10 bg-white border-2 border-orange-600 rounded-[3rem] text-orange-600 flex items-center justify-between hover:bg-orange-50 transition-all group">
                        <span class="text-2xl font-black">LAPORAN PENJUALAN</span>
                        <i class="bi bi-arrow-right-circle text-4xl opacity-20 group-hover:opacity-100"></i>
                    </a>
                </div>

            </main>
        </div>
    </div>
    

    <script>
        const chartData = @json($chartData);
        const labels = chartData.map(d => d.date);

        // Chart Penjualan
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: chartData.map(d => d.sales),
                    backgroundColor: '#ea580c',
                    borderRadius: 10
                }]
            }
        });

        // Chart Refund
        new Chart(document.getElementById('refundChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Refund',
                    data: chartData.map(d => d.refunds),
                    backgroundColor: '#ef4444',
                    borderRadius: 10
                }]
            }
        });

        function downloadChart(id) {
            const canvas = document.getElementById(id);
            const link = document.createElement('a');
            link.download = id + '.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        }
    </script>
</body>
</html>