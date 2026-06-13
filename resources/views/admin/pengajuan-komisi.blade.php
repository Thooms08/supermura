<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Komisi | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
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
                        Manajemen<span class="text-orange-500">Pengajuan Komisi</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-pulse">
                    <i class="bi bi-check-circle-fill"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                @endif

                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h5 class="font-black text-gray-800 uppercase text-xs tracking-widest">Daftar Permintaan Pencairan Dana</h5>
                        <span class="bg-orange-100 text-orange-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase">
                            Total: {{ $pengajuan->total() }} Data
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50/50 text-[10px] font-black uppercase text-gray-400 tracking-widest border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">Affiliator</th>
                                    <th class="px-6 py-4">Nominal</th>
                                    <th class="px-6 py-4">Info Pembayaran</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($pengajuan as $item)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-800">{{ $item->affiliator->nama }}</span>
                                            <span class="text-[10px] font-medium text-orange-500">{{ $item->affiliator->id_unik }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-sm font-black text-gray-800">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2 text-xs text-gray-600 bg-gray-100 p-2 rounded-lg border border-gray-200 w-fit">
                                            <i class="bi bi-wallet2"></i>
                                            {{ $item->nomor_pembayaran }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @php
                                            $badges = [
                                                'pending' => 'bg-yellow-100 text-yellow-600',
                                                'success' => 'bg-green-100 text-green-600',
                                                'fail'    => 'bg-red-100 text-red-600',
                                            ];
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter {{ $badges[$item->status] }}">
                                            {{ $item->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-end gap-2">
                                            @if($item->status == 'pending')
                                                <form action="{{ route('admin.pengajuan-komisi.update', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="success">
                                                    <button type="submit" class="p-2 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-all shadow-sm shadow-green-100" title="Konfirmasi Cair">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>

                                                <form action="{{ route('admin.pengajuan-komisi.update', $item->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <input type="hidden" name="status" value="fail">
                                                    <button type="submit" class="p-2 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-all shadow-sm shadow-red-100" title="Tolak Pengajuan">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-[10px] italic text-gray-400 uppercase font-bold">Selesai</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400 italic text-sm">Belum ada pengajuan baru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50/30 border-t border-gray-50">
                        {{ $pengajuan->links() }}
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>