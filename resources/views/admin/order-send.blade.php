<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Dikirim | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
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

        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">@include('admin.sidebar')</div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">

            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Pesanan <span class="text-indigo-500">Dikirim</span>
                    </h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 border border-indigo-100 shadow-sm">
                        <i class="bi bi-send-fill text-lg"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-800 uppercase tracking-tighter">Dalam Pengiriman</h1>
                            <p class="text-gray-500 text-sm">Pantau paket yang sedang dalam perjalanan ke tangan pembeli.</p>
                        </div>
                        <div class="bg-white px-6 py-3 rounded-2xl border shadow-sm text-right">
                            <span class="text-[10px] font-bold text-gray-400 block uppercase tracking-widest">Total Dikirim</span>
                            <span class="font-black text-indigo-600 text-xl">{{ $orders->count() }} Paket</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 font-bold rounded-xl shadow-sm">
                            <i class="bi bi-check-circle-fill mr-2"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 font-bold rounded-xl shadow-sm">
                            <i class="bi bi-x-circle-fill mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 tracking-widest">Order & Penerima</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 tracking-widest">Detail Item</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-center tracking-widest">Resi & Kurir</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-center tracking-widest">Total</th>
                                        <th class="p-6 text-xs font-black uppercase text-gray-400 text-right tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($orders as $order)
                                    <tr class="hover:bg-indigo-50/20 transition-colors" x-data="{ openDetail: false, openConfirm: false }">

                                        {{-- Kolom Order & Penerima --}}
                                        <td class="p-6">
                                            <div class="font-black text-gray-800 text-sm mb-1 uppercase tracking-tighter">
                                                #{{ $order->nomor_pesanan }}
                                            </div>
                                            <div class="text-sm font-bold text-gray-600">
                                                {{ $order->pengunjung->nama_lengkap ?? 'Guest' }}
                                            </div>
                                            <div class="text-[10px] text-gray-400 mt-1 flex items-center gap-1">
                                                <i class="bi bi-calendar3"></i>
                                                {{ $order->created_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                                            </div>
                                        </td>

                                        {{-- Kolom Item --}}
                                        <td class="p-6">
                                            <div class="space-y-2">
                                                @foreach($order->items as $item)
                                                <div class="flex flex-col border-l-2 border-indigo-100 pl-3">
                                                    <span class="text-xs font-bold text-gray-700">{{ $item->nama_produk }}</span>
                                                    <span class="text-[10px] text-indigo-500 font-bold">
                                                        x{{ $item->qty }} — Rp {{ number_format($item->harga, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>

                                        {{-- Kolom Resi --}}
                                        <td class="p-6 text-center">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl border border-indigo-100 font-black text-xs uppercase tracking-widest mb-2">
                                                <i class="bi bi-truck text-sm"></i>
                                                {{ $order->shippingMethod->nama_metode ?? $order->metode_pengiriman }}
                                            </div>
                                            <div class="text-sm font-black text-gray-800 font-mono">
                                                {{ $order->no_resi ?? '-' }}
                                            </div>
                                        </td>

                                        {{-- Kolom Total --}}
                                        <td class="p-6 text-center">
                                            <div class="font-black text-gray-900 text-base">
                                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                            </div>
                                        </td>

                                        {{-- Kolom Aksi --}}
                                        <td class="p-6 text-right">
                                            <div class="flex justify-end gap-2">

                                                {{-- Tombol Lihat Alamat --}}
                                                <button @click="openDetail = true"
                                                        class="w-9 h-9 flex items-center justify-center bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-all shadow-sm"
                                                        title="Lihat Alamat Penerima">
                                                    <i class="bi bi-eye-fill"></i>
                                                </button>

                                                {{-- Tombol Lacak Paket — buka 17track --}}
                                                @if($order->no_resi)
                                                <a href="https://www.17track.net/en?nums={{ $order->no_resi }}"
                                                   target="_blank" rel="noopener noreferrer"
                                                   class="w-9 h-9 flex items-center justify-center bg-blue-100 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm"
                                                   title="Lacak Paket via 17track">
                                                    <i class="bi bi-geo-alt-fill"></i>
                                                </a>
                                                @endif

                                                {{-- Tombol Konfirmasi Paket Tiba --}}
                                                <button @click="openConfirm = true"
                                                        class="px-4 py-2 bg-green-100 text-green-700 font-black rounded-xl hover:bg-green-600 hover:text-white transition-all shadow-sm text-[10px] uppercase tracking-wide flex items-center gap-1.5"
                                                        title="Konfirmasi Paket Tiba">
                                                    <i class="bi bi-house-check-fill"></i> Paket Tiba
                                                </button>
                                            </div>

                                            {{-- Modal Detail Alamat --}}
                                            <div x-show="openDetail"
                                                 class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                                                 x-cloak>
                                                <div @click.away="openDetail = false"
                                                     class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl text-left">
                                                    <div class="bg-indigo-500 p-8 text-white">
                                                        <h3 class="text-xl font-black uppercase tracking-tighter">Data Penerima</h3>
                                                        <p class="text-indigo-100 text-[10px] font-bold uppercase tracking-widest opacity-80">Informasi Pengiriman</p>
                                                    </div>
                                                    <div class="p-8 space-y-5">
                                                        <div class="flex items-start gap-4">
                                                            <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-500 shrink-0">
                                                                <i class="bi bi-person-circle text-xl"></i>
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Nama Penerima</label>
                                                                <p class="font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</p>
                                                                <p class="text-xs text-indigo-600 font-semibold mt-0.5">{{ $order->pengunjung->no_whatsapp ?? '-' }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-start gap-4 border-t pt-5">
                                                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0">
                                                                <i class="bi bi-geo-alt-fill text-xl"></i>
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Alamat Pengiriman</label>
                                                                <p class="text-[11px] font-bold text-gray-800 mb-1">
                                                                    {{ $order->pengunjung->kota_kabupaten ?? '-' }}, {{ $order->pengunjung->provinsi ?? '-' }}
                                                                    {{ $order->pengunjung->kode_pos ? '- ' . $order->pengunjung->kode_pos : '' }}
                                                                </p>
                                                                <p class="text-xs text-gray-500 italic">{{ $order->pengunjung->alamat_lengkap ?? 'Alamat tidak diatur.' }}</p>
                                                            </div>
                                                        </div>
                                                        @if($order->no_resi)
                                                        <div class="flex items-start gap-4 border-t pt-5">
                                                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-500 shrink-0">
                                                                <i class="bi bi-truck text-xl"></i>
                                                            </div>
                                                            <div>
                                                                <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Kurir & Resi</label>
                                                                <p class="font-bold text-gray-800">{{ $order->shippingMethod->nama_metode ?? '-' }}</p>
                                                                <p class="text-xs font-mono text-blue-600 mt-0.5">{{ $order->no_resi }}</p>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="p-6 bg-gray-50 flex gap-3">
                                                        @if($order->no_resi)
                                                        <a href="https://www.17track.net/en?nums={{ $order->no_resi }}"
                                                           target="_blank"
                                                           class="flex-1 py-3 bg-blue-600 text-white font-black rounded-xl uppercase text-[10px] tracking-widest text-center flex items-center justify-center gap-1.5">
                                                            <i class="bi bi-geo-alt-fill"></i> Lacak Paket
                                                        </a>
                                                        @endif
                                                        <button @click="openDetail = false"
                                                                class="flex-1 py-3 bg-gray-900 text-white font-black rounded-xl uppercase text-[10px] tracking-widest">
                                                            Tutup
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Modal Konfirmasi Paket Tiba --}}
                                            <div x-show="openConfirm"
                                                 class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
                                                 x-cloak>
                                                <div @click.away="openConfirm = false"
                                                     class="bg-white rounded-[2.5rem] w-full max-w-sm overflow-hidden shadow-2xl text-left">
                                                    <div class="bg-green-600 p-8 text-white">
                                                        <h3 class="text-xl font-black uppercase tracking-tighter">Konfirmasi Paket Tiba</h3>
                                                        <p class="text-green-100 text-[10px] font-bold uppercase tracking-widest opacity-80 mt-1">
                                                            #{{ $order->nomor_pesanan }}
                                                        </p>
                                                    </div>
                                                    <div class="p-8 space-y-4">
                                                        <div class="p-4 bg-green-50 border border-green-100 rounded-2xl flex gap-3 items-start">
                                                            <i class="bi bi-info-circle-fill text-green-500 shrink-0 mt-0.5"></i>
                                                            <p class="text-[11px] text-green-700 font-semibold leading-relaxed">
                                                                Tindakan ini akan menandai pesanan sebagai <strong>SELESAI</strong> dan secara otomatis mencatat komisi affiliator (jika ada). Pastikan paket sudah benar-benar diterima pembeli.
                                                            </p>
                                                        </div>
                                                        <div class="text-sm text-gray-600 space-y-1">
                                                            <div class="flex justify-between">
                                                                <span class="font-bold text-gray-400 text-xs uppercase tracking-widest">Penerima</span>
                                                                <span class="font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="font-bold text-gray-400 text-xs uppercase tracking-widest">Resi</span>
                                                                <span class="font-mono font-bold text-blue-600">{{ $order->no_resi ?? '-' }}</span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="font-bold text-gray-400 text-xs uppercase tracking-widest">Total</span>
                                                                <span class="font-black text-orange-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="p-6 bg-gray-50 flex gap-3">
                                                        <button @click="openConfirm = false"
                                                                class="flex-1 py-3 bg-gray-200 text-gray-700 font-black rounded-xl uppercase text-[10px] tracking-widest">
                                                            Batal
                                                        </button>
                                                        <form action="{{ route('admin.orders.markAsArrived', $order->id) }}" method="POST" class="flex-1">
                                                            @csrf @method('PATCH')
                                                            <button type="submit"
                                                                    class="w-full py-3 bg-green-600 text-white font-black rounded-xl uppercase text-[10px] tracking-widest shadow-lg shadow-green-200 hover:bg-green-700 transition-colors">
                                                                <i class="bi bi-house-check-fill mr-1"></i> Ya, Tiba!
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="p-24 text-center">
                                            <div class="bg-indigo-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-200 text-4xl">
                                                <i class="bi bi-send"></i>
                                            </div>
                                            <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-xs">Tidak Ada Paket Dalam Pengiriman</p>
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
