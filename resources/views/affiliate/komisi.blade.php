<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Komisi | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="sidebarOpen = (window.innerWidth >= 1024)">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">@include('affiliate.sidebar')</div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Laporan<span class="text-orange-500">Komisi</span>
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('affiliate.komisi.pengajuan') }}" 
                       class="flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white text-xs font-black uppercase tracking-wider rounded-xl shadow-lg shadow-orange-200 hover:scale-105 active:scale-95 transition-all">
                        <i class="bi bi-cash-stack text-base"></i>
                        <span>Cairkan Komisi</span>
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 p-8 rounded-[2.5rem] text-white shadow-xl shadow-orange-200 relative overflow-hidden group col-span-1 lg:col-span-1">
                        <div class="absolute -right-4 -top-4 w-32 h-32 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em] opacity-80 mb-2">Total Saldo Bisa Cair</p>
                        <h3 class="text-4xl font-black tracking-tighter">Rp {{ number_format($stats['saldo_tersedia'], 0, ',', '.') }}</h3>
                        <div class="mt-4 flex items-center gap-2 text-[10px] font-bold bg-white/20 w-fit px-3 py-1 rounded-full backdrop-blur-md">
                            <i class="bi bi-info-circle"></i> Termasuk Bonus Referral
                        </div>
                        <i class="bi bi-wallet2 absolute bottom-6 right-8 text-6xl opacity-10"></i>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Komisi Penjualan Produk</p>
                        <h3 class="text-3xl font-black text-gray-800">Rp {{ number_format($stats['komisi_penjualan'], 0, ',', '.') }}</h3>
                        <div class="absolute top-8 right-8 w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="bi bi-bag-check-fill text-xl"></i>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm relative overflow-hidden">
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Bonus Rekrut Affiliator</p>
                        <h3 class="text-3xl font-black text-gray-800">Rp {{ number_format($stats['komisi_rekrut'], 0, ',', '.') }}</h3>
                        <div class="absolute top-8 right-8 w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center shadow-inner">
                            <i class="bi bi-people-fill text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">WD Berhasil</p>
                            <p class="text-lg font-black text-gray-800">Rp {{ number_format($stats['penarikan_berhasil'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-arrow-clockwise"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">WD Diproses</p>
                            <p class="text-lg font-black text-gray-800">Rp {{ number_format($stats['penarikan_pending'], 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-xl shrink-0">
                            <i class="bi bi-x-circle-fill"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">WD Gagal</p>
                            <p class="text-lg font-black text-red-500">Rp {{ number_format($stats['penarikan_gagal'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                        <h5 class="font-black text-gray-800 uppercase text-xs tracking-[0.2em]">Riwayat Komisi Penjualan</h5>
                        <div class="bg-gray-50 px-4 py-2 rounded-xl text-[10px] font-bold text-gray-400 flex items-center gap-2">
                            <i class="bi bi-funnel"></i> SORT BY LATEST
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400 tracking-[0.15em]">
                                <tr>
                                    <th class="px-8 py-5">Informasi Produk</th>
                                    <th class="px-8 py-5">Tanggal</th>
                                    <th class="px-8 py-5">Nominal</th>
                                    <th class="px-8 py-5 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($history as $h)
                                <tr class="hover:bg-gray-50/50 transition-all group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-all duration-300 shadow-sm">
                                                <i class="bi bi-box-seam text-lg"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-gray-800">{{ $h->produk->nama_produk ?? 'Produk Tidak Ditemukan' }}</p>
                                                <p class="text-[10px] text-gray-400 uppercase font-bold tracking-tight mt-0.5">
                                                    {{ $h->variant->model ?? 'Tipe Satuan' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-xs font-bold text-gray-500">
                                        <div class="flex flex-col">
                                            <span>{{ $h->created_at->format('d M Y') }}</span>
                                            <span class="text-[10px] text-gray-300 font-medium">{{ $h->created_at->format('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="font-black text-gray-900 text-sm">
                                            Rp {{ number_format($h->nominal_komisi, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        @php
                                            $badge = [
                                                'berhasil' => 'bg-green-100 text-green-600',
                                                'pending'  => 'bg-blue-100 text-blue-600',
                                                'batal'    => 'bg-red-100 text-red-600',
                                            ][$h->status] ?? 'bg-gray-100 text-gray-600';
                                        @endphp
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $badge }}">
                                            {{ $h->status }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mb-4">
                                                <i class="bi bi-inbox text-4xl"></i>
                                            </div>
                                            <p class="text-xs font-black uppercase tracking-widest text-gray-300">Belum ada riwayat komisi</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-8 bg-gray-50/30 border-t border-gray-50">
                        {{ $history->links() }}
                    </div>
                </div>

                <div class="mt-10 p-8 bg-orange-50 rounded-[2.5rem] border border-orange-100 flex flex-col md:flex-row items-center gap-6">
                    <div class="w-16 h-16 bg-white text-orange-500 rounded-2xl flex items-center justify-center text-3xl shadow-sm shrink-0">
                        <i class="bi bi-lightbulb"></i>
                    </div>
                    <div>
                        <h6 class="text-sm font-black text-orange-900 uppercase tracking-widest">Informasi Penting</h6>
                        <p class="text-xs text-orange-700/80 leading-relaxed mt-2">
                            Saldo yang tampil adalah gabungan dari komisi penjualan dan bonus rekrut member baru. 
                            Penarikan dana diproses setiap hari kerja pukul 09:00 - 17:00 WIB. Pastikan nomor rekening Anda sudah benar untuk menghindari kegagalan transfer.
                        </p>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>