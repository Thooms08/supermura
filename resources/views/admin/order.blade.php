<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Baru | Admin</title>
    @include('layouts.favicon')
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

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Pesanan <span class="text-orange-500">Baru</span>
                    </h2>
                </div>

            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-800 uppercase tracking-tighter">Order Masuk</h1>
                            <p class="text-gray-500 text-sm">Segera proses pesanan baru agar pelanggan senang.</p>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-2xl border shadow-sm text-right">
                            <span class="text-[10px] font-bold text-gray-400 block uppercase tracking-widest">Waktu Server</span>
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
                                            <div class="text-sm font-bold text-gray-600">{{ $order->pengunjung->nama_lengkap ?? 'Guest' }}</div>
                                            <div class="text-[10px] text-gray-400 mt-2 italic">
                                                <i class="bi bi-clock"></i> {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            </div>
                                        </td>

                                        <td class="p-6">
                                            <div class="space-y-2">
                                                @foreach($order->items as $item)
                                                <div class="flex flex-col border-l-2 border-orange-200 pl-3">
                                                    <span class="text-xs font-bold text-gray-700">{{ $item->nama_produk }}</span>
                                                    <span class="text-[10px] text-orange-500 font-bold">x{{ $item->qty }} - Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        <td class="p-6 text-center">
                                            <div class="font-black text-gray-900 text-base">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
                                            <div class="inline-flex items-center mt-2 px-3 py-1 bg-gray-100 rounded-full text-[9px] font-black uppercase text-gray-500">
                                                <i class="bi bi-truck mr-1"></i> {{ $order->metode_pengiriman }}
                                            </div>
                                        </td>

                                        <td class="p-6 text-center text-xs">
                                            <span class="px-4 py-1.5 rounded-full border bg-yellow-100 text-yellow-700 border-yellow-200 font-black uppercase tracking-widest text-[8px]">
                                                {{ $order->status }}
                                            </span>
                                        </td>

                                        <td class="p-6 text-right" x-data="{ openDetail: false }">
                                            <div class="flex justify-end gap-2">
                                                <button @click="openDetail = true" class="w-9 h-9 flex items-center justify-center bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all shadow-sm">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>

                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="process">
                                                    <button type="submit" class="w-9 h-9 flex items-center justify-center bg-orange-100 text-orange-600 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm" title="Proses Pesanan">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="fail">
                                                    <button type="submit" class="w-9 h-9 flex items-center justify-center bg-red-100 text-red-600 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Gagalkan">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <div x-show="openDetail" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                                                <div @click.away="openDetail = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl text-left">
                                                    <div class="bg-orange-500 p-8 text-white relative">
                                                        <h3 class="text-xl font-black uppercase tracking-tighter">Profil Pembeli</h3>
                                                    </div>
                                                    <div class="p-8 space-y-4">
                                                        <div>
                                                            <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest">Nama Lengkap</label>
                                                            <p class="font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest">WhatsApp</label>
                                                            <p class="font-bold text-gray-800">{{ $order->pengunjung->no_whatsapp ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest">Alamat Lengkap</label>
                                                            <p class="text-xs text-gray-500 italic">{{ $order->pengunjung->alamat_lengkap ?? 'Alamat tidak diatur.' }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="p-6 bg-gray-50 text-right">
                                                        <button @click="openDetail = false" class="px-8 py-2 bg-gray-900 text-white font-black rounded-xl uppercase text-[10px] tracking-widest">Tutup</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="p-24 text-center">
                                            <div class="bg-gray-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-200 text-4xl"><i class="bi bi-inboxes"></i></div>
                                            <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-xs">Tidak Ada Pesanan Baru</p>
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