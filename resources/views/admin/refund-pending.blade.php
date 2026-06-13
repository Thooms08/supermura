<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Pending | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50" x-data="{ sidebarOpen: false }">

    <div class="flex min-h-screen">
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
               class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0">
            @include('admin.sidebar')
        </aside>

        <main class="flex-1">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b lg:hidden">
                <span class="text-xl font-bold text-orange-500">Refund Panel</span>
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none">
                    <i class="bi bi-list text-2xl"></i>
                </button>
            </header>

            <div class="p-6 md:p-10">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800">Pengajuan <span class="text-orange-500">Refund</span></h1>
                        <p class="text-sm text-gray-500">Kelola permintaan pengembalian dana pengunjung (Status: Pending)</p>
                    </div>
                    <div class="bg-orange-100 px-4 py-2 rounded-xl border border-orange-200">
                        <span class="text-orange-600 font-bold text-sm">Total: {{ $refunds->count() }} Pengajuan</span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 shadow-sm">
                        <i class="bi bi-check-circle-fill"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-6">
                    @forelse($refunds as $refund)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4 bg-gray-50/50">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-orange-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-orange-100">
                                        <i class="bi bi-person-fill text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pengirim</p>
                                        <h3 class="font-bold text-gray-800">{{ $refund->pesanan->pengunjung->nama_lengkap ?? 'User' }}</h3>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Nomor Pesanan</p>
                                    <span class="text-sm font-black text-gray-700">#{{ $refund->pesanan->nomor_pesanan }}</span>
                                </div>
                            </div>

                            <div class="p-6 grid md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <i class="bi bi-box-seam"></i> Produk dalam Pesanan
                                    </h4>
                                    @foreach($refund->pesanan->items as $item)
                                        <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-2xl">
                                            <div class="w-14 h-14 rounded-xl overflow-hidden bg-white border flex-shrink-0">
                                                @if($item->produk && $item->produk->fotos->first())
                                                    <img src="{{ asset('storage/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="bi bi-image"></i></div>
                                                @endif
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                                                <p class="text-xs text-gray-500">{{ $item->qty }} unit x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="space-y-4">
                                    <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                        <i class="bi bi-chat-left-text"></i> Alasan & Rekening
                                    </h4>
                                    <div class="bg-orange-50 border border-orange-100 p-4 rounded-2xl">
                                        <p class="text-xs text-orange-800 font-medium leading-relaxed italic mb-3">"{{ $refund->alasan }}"</p>
                                        <div class="pt-3 border-t border-orange-200">
                                            <p class="text-[10px] text-orange-400 font-bold uppercase">Tujuan Transfer</p>
                                            <p class="text-sm font-black text-orange-700">{{ $refund->nomor_pengembalian }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 py-5 bg-gray-50/50 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                                <div>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Dana Kembali</p>
                                    <p class="text-2xl font-black text-orange-600 tracking-tighter">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</p>
                                </div>
                                
                                <div class="flex items-center gap-3 w-full md:w-auto">
                                    <form action="{{ route('admin.refunds.update', $refund->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="fail">
                                        <button type="submit" onclick="return confirm('Tolak pengajuan refund ini?')" 
                                                class="w-full px-6 py-3 bg-white border-2 border-red-500 text-red-500 font-bold rounded-xl text-xs uppercase hover:bg-red-500 hover:text-white transition-all">
                                            <i class="bi bi-x-lg mr-2"></i> Tolak
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.refunds.update', $refund->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="success">
                                        <button type="submit" onclick="return confirm('Konfirmasi bahwa dana telah dikembalikan?')" 
                                                class="w-full px-6 py-3 bg-orange-500 text-white font-bold rounded-xl text-xs uppercase shadow-lg shadow-orange-200 hover:bg-orange-600 transition-all">
                                            <i class="bi bi-check2-all mr-2"></i> Konfirmasi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white rounded-3xl p-20 text-center border-2 border-dashed border-gray-200">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-emoji-smile text-4xl text-gray-300"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Tidak ada pengajuan pending</h3>
                            <p class="text-sm text-gray-400">Semua permintaan refund telah diproses.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <div x-show="sidebarOpen" 
         @click="sidebarOpen = false" 
         class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak></div>

</body>
</html>