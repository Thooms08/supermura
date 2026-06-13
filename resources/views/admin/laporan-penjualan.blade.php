<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@hotwired/turbo@7.3.0/dist/turbo.es2017-umd.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { bg: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ed8936; border-radius: 10px; }
        .turbo-progress-bar {
                background-color: #ea580c !important; /* Warna Oranye ThomsFashion */
                height: 3px !important;
            }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

    <div class="flex h-screen overflow-hidden relative">
        <aside 
            :class="{
                'translate-x-0 w-64': sidebarOpen,
                '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen
            }"
            class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('admin.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight">
                        Laporan <span class="text-orange-500 uppercase text-xs ml-1">Penjualan</span>
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.laporan.index') }}" class="text-xs font-bold text-orange-600 bg-orange-50 px-4 py-2 rounded-xl hover:bg-orange-100 transition-all">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto space-y-6">
                    
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-2xl font-black text-gray-800 uppercase italic tracking-tighter">Data Laporan Penjualan</h3>
                            <p class="text-sm text-gray-400 font-medium">Menampilkan transaksi sukses berdasarkan filter tanggal.</p>
                        </div>
                        <div class="bg-white border border-orange-200 px-5 py-3 rounded-2xl shadow-sm flex items-center gap-3">
                            <i class="bi bi-calendar3 text-orange-500"></i>
                            <span class="text-xs font-black text-gray-600 uppercase">{{ \Carbon\Carbon::parse($start)->format('d M Y') }} - {{ \Carbon\Carbon::parse($end)->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-orange-600 text-white">
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Pengunjung</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Lokasi</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Detail Produk</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Harga x Qty</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest text-center">Total</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Kurir</th>
                                        <th class="p-5 text-[10px] font-black uppercase tracking-widest">Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($data as $row)
                                    <tr class="hover:bg-orange-50/30 transition-colors">
                                        <td class="p-5">
                                            <p class="font-black text-gray-800 leading-tight">{{ $row->pengunjung->nama_lengkap ?? 'Guest' }}</p>
                                            <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">{{ $row->nomor_pesanan }}</p>
                                        </td>
                                        <td class="p-5">
                                            <span class="text-xs font-medium text-gray-600">{{ $row->pengunjung->kota_kabupaten ?? '-' }}</span>
                                        </td>
                                        <td class="p-5">
                                            @foreach($row->items as $item)
                                                <div class="text-[11px] font-bold text-gray-700 flex items-center gap-1">
                                                    <i class="bi bi-dot text-orange-500"></i> {{ $item->nama_produk }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="p-5">
                                            @foreach($row->items as $item)
                                                <div class="text-[10px] text-gray-400 font-medium">
                                                    Rp{{ number_format($item->harga, 0, ',', '.') }} x {{ $item->qty }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td class="p-5 text-center">
                                            <span class="inline-block px-3 py-1 bg-orange-100 text-orange-600 rounded-lg font-black text-xs">
                                                Rp{{ number_format($row->total_harga, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="p-5">
                                            <span class="bg-gray-100 text-gray-500 px-2 py-1 rounded text-[10px] font-black uppercase tracking-tighter">
                                                {{ $row->shippingMethod->nama_metode ?? $row->metode_pengiriman }}
                                            </span>
                                        </td>
                                        <td class="p-5">
                                            <p class="text-[10px] font-bold text-gray-400 uppercase leading-none">{{ $row->created_at->format('d M Y') }}</p>
                                            <p class="text-[9px] text-gray-300 mt-1">{{ $row->created_at->format('H:i') }} WIB</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="p-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="bi bi-file-earmark-x text-6xl text-gray-100 mb-4"></i>
                                                <p class="text-gray-400 font-medium italic">Tidak ada data penjualan pada periode ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(!request()->has('all') && $data->count() >= 30)
                    <div class="flex justify-center pt-4 pb-10">
                        <a href="{{ request()->fullUrlWithQuery(['all' => 1]) }}" class="bg-white border-2 border-orange-600 text-orange-600 px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-orange-600 hover:text-white transition-all shadow-xl shadow-orange-100">
                            Tampilkan Semua Data
                        </a>
                    </div>
                    @endif

                </div>
            </main>
        </div>
    </div>
    <script>
    // Pastikan fungsi ini dipanggil ulang setiap kali Turbo memuat halaman baru
    document.addEventListener("turbo:load", () => {
        // Jika Anda memiliki inisialisasi script manual (seperti jam/clock), taruh di sini
        if(document.getElementById('clock-time')) {
            updateClock();
        }
    });

    function updateClock() {
        const now = new Date();
        const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
        const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
        
        const timeEl = document.getElementById('clock-time');
        const dateEl = document.getElementById('clock-date');
        
        if(timeEl) timeEl.innerText = now.toLocaleTimeString('id-ID', timeOptions);
        if(dateEl) dateEl.innerText = now.toLocaleDateString('id-ID', dateOptions);
    }
    
    setInterval(updateClock, 1000);
</script>

</body>
</html>