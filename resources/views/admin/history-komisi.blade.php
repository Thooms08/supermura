<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Komisi | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('admin.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight">
                        Riwayat<span class="text-orange-500">Pencairan Dana</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Log Transaksi Selesai</h1>
                    <p class="text-gray-400 text-sm">Daftar seluruh komisi yang telah dikonfirmasi atau ditolak oleh sistem.</p>
                </div>

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-5">Tanggal Selesai</th>
                                    <th class="px-6 py-5">Affiliator</th>
                                    <th class="px-6 py-5">Nominal Keluar</th>
                                    <th class="px-6 py-5">Tujuan Pembayaran</th>
                                    <th class="px-6 py-5 text-center">Status Akhir</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($history as $row)
                                <tr class="hover:bg-gray-50/30 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-bold text-gray-700">{{ $row->updated_at->format('d M Y') }}</span>
                                            <span class="text-[10px] text-gray-400">{{ $row->updated_at->format('H:i') }} WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold text-[10px]">
                                                {{ substr($row->affiliator->nama, 0, 1) }}
                                            </div>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-gray-800">{{ $row->affiliator->nama }}</span>
                                                <span class="text-[9px] text-orange-500 font-mono tracking-tighter">{{ $row->affiliator->id_unik }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-gray-900">Rp {{ number_format($row->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-[11px] text-gray-500 font-medium">
                                            <i class="bi bi-credit-card-2-front mr-1"></i> {{ $row->nomor_pembayaran }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($row->status == 'success')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase bg-green-100 text-green-600 border border-green-200">
                                                <i class="bi bi-check-circle-fill"></i> Berhasil
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase bg-red-100 text-red-600 border border-red-200">
                                                <i class="bi bi-x-circle-fill"></i> Gagal
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <i class="bi bi-clipboard2-x text-5xl mb-3"></i>
                                            <p class="text-sm font-bold uppercase tracking-widest">Belum ada riwayat transaksi</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50/30 border-t border-gray-50">
                        {{ $history->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>