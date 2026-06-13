<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Diproses | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #fb923c; }
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

        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity"
            x-transition:enter="transition opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors focus:outline-none"
                    >
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Pesanan <span class="text-orange-500">Diproses</span>
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-orange-500 font-bold uppercase mt-1 tracking-widest">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 border border-orange-200 shadow-sm">
                        <i class="bi bi-person-fill text-xl"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-800 uppercase tracking-tighter">Sedang Diproses</h1>
                            <p class="text-gray-500 text-sm">Monitor pesanan yang sedang dalam tahap pengemasan atau pengiriman.</p>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-2xl border shadow-sm text-right">
                            <span class="text-[10px] font-bold text-gray-400 block uppercase">Waktu Asia/Jakarta</span>
                            <span class="font-mono font-bold text-orange-600">{{ now()->setTimezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold rounded-xl shadow-sm">
                            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 tracking-widest">Order & User</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 tracking-widest">Detail Item</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-center tracking-widest">Total Harga</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-center tracking-widest">Status</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-right tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($orders as $order)
                                    <tr class="hover:bg-orange-50/30 transition-colors">
                                        <td class="p-6">
                                            <div class="font-black text-gray-800 text-sm mb-1 uppercase tracking-tighter">#{{ $order->nomor_pesanan }}</div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-bold text-gray-600">{{ $order->pengunjung->nama_lengkap ?? 'Guest' }}</span>
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-2 flex items-center gap-1">
                                                <i class="bi bi-calendar3"></i> {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            </div>
                                        </td>

                                        <td class="p-6">
                                            <div class="space-y-3">
                                                @foreach($order->items as $item)
                                                <div class="flex flex-col border-l-2 border-orange-100 pl-3">
                                                    <span class="text-xs font-bold text-gray-700">{{ $item->nama_produk }}</span>
                                                    <div class="flex items-center gap-2 text-[10px]">
                                                        <span class="text-gray-400">Qty: {{ $item->qty }}</span>
                                                        <span class="text-orange-500 font-bold">@ Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td class="p-6 text-center">
                                            <div class="font-black text-gray-900 text-base">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                            <div class="inline-flex items-center mt-2 px-3 py-1 bg-gray-100 rounded-full text-[9px] font-black uppercase text-gray-500 tracking-tighter">
                                                <i class="bi bi-truck mr-1"></i> {{ $order->shippingMethod->nama_metode ?? $order->metode_pengiriman }}
                                            </div>
                                        </td>

                                        <td class="p-6 text-center text-xs">
                                            <span class="px-4 py-1.5 rounded-full border bg-blue-100 text-blue-700 border-blue-200 font-black uppercase tracking-widest text-[8px]">
                                                {{ $order->status }}
                                            </span>
                                        </td>

                                        <td class="p-6 text-right" x-data="{ openDetail: false, openSuccess: false }">
                                            <div class="flex justify-end gap-2">
                                                <button @click="openDetail = true" class="w-9 h-9 flex items-center justify-center bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all shadow-sm" title="Lihat Alamat">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>

                                                <form action="{{ route('admin.orders.markAsFail', $order->id) }}" method="POST" onsubmit="return confirm('Gagalkan pesanan ini?')">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Batalkan Pesanan">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>

                                                <button @click="openSuccess = true" class="w-9 h-9 flex items-center justify-center bg-green-100 text-green-600 rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm" title="Konfirmasi Pengiriman">
                                                    <i class="bi bi-check-circle-fill"></i>
                                                </button>
                                            </div>

                                            <div x-show="openDetail" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                                                <div @click.away="openDetail = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl text-left">
                                                    <div class="bg-orange-500 p-8 text-white relative">
                                                        <h3 class="text-xl font-black uppercase tracking-tighter">Data Pelanggan</h3>
                                                        <p class="text-orange-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Informasi Pengiriman</p>
                                                    </div>
                                                    <div class="p-8 space-y-5">
                                                        <div class="flex items-start gap-4">
                                                            <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500 flex-shrink-0"><i class="bi bi-person-circle text-xl"></i></div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Nama Lengkap</label>
                                                                <p class="font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start gap-4 border-t pt-5">
                                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 flex-shrink-0"><i class="bi bi-geo-alt-fill text-xl"></i></div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Lokasi & Alamat</label>
                                                                <p class="text-[11px] font-bold text-gray-800 mb-1">{{ $order->pengunjung->provinsi ?? '-' }}, {{ $order->pengunjung->kota_kabupaten ?? '-' }}</p>
                                                                <p class="text-xs text-gray-500 italic">{{ $order->pengunjung->alamat_lengkap ?? 'Alamat tidak diatur.' }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-6 bg-gray-50 flex justify-end">
                                                        <button @click="openDetail = false" class="px-8 py-2.5 bg-gray-900 text-white font-black rounded-xl uppercase text-[10px] tracking-widest">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div x-show="openSuccess" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                                                <div @click.away="openSuccess = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl text-left">
                                                    <form action="{{ route('admin.orders.markAsSuccess', $order->id) }}" method="POST">
                                                        @csrf @method('PATCH')
                                                        <div class="bg-green-600 p-8 text-white relative">
                                                            <h3 class="text-xl font-black uppercase tracking-tighter">Konfirmasi Pengiriman</h3>
                                                            <p class="text-blue-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Input Resi & Kirim Paket #{{ $order->nomor_pesanan }}</p>
                                                        </div>
                                                        <div class="p-8 space-y-5">
                                                            <div class="p-3 bg-blue-50 border border-green-100 rounded-xl text-xs text-green-700 font-semibold flex items-start gap-2">
                                                                <i class="bi bi-info-circle-fill shrink-0 mt-0.5"></i>
                                                                Status menjadi <strong>"Dikirim"</strong>. Konfirmasi "Paket Tiba" dilakukan setelah pengunjung menerima paket.
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-2">Metode Pengiriman</label>
                                                                <select name="shipping_id" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold focus:ring-2 focus:ring-orange-500 outline-none">
                                                                    @foreach($shippingMethods as $sm)
                                                                        <option value="{{ $sm->id }}" {{ $order->shipping_id == $sm->id ? 'selected' : '' }}>
                                                                            {{ $sm->nama_metode }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-2">Nomor Resi / AWB</label>
                                                                <input type="text" name="no_resi" required placeholder="Masukkan nomor resi..."
                                                                       class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 text-sm font-bold focus:ring-2 focus:ring-orange-500 outline-none uppercase">
                                                            </div>
                                                        </div>
                                                        <div class="p-6 bg-gray-50 flex gap-3">
                                                            <button type="button" @click="openSuccess = false" class="flex-1 py-3 bg-gray-200 text-gray-700 font-black rounded-xl uppercase text-[10px] tracking-widest">Batal</button>
                                                            <button type="submit" class="flex-1 py-3 bg-green-600 text-white font-black rounded-xl uppercase text-[10px] tracking-widest shadow-lg shadow-blue-200">
                                                                <i class="bi bi-send-fill mr-1"></i> Kirim Paket
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="p-24 text-center">
                                            <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200 text-4xl"><i class="bi bi-truck"></i></div>
                                            <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-xs">Tidak Ada Pesanan Diproses</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

</body>
</html>