<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Gagal | Admin</title>
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
      x-data="{ sidebarOpen: window.innerWidth >= 1024, isSearching: false }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }"
      @searching.window="isSearching = $event.detail">

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
                        Riwayat <span class="text-red-500">Gagal</span>
                    </h2>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500 border border-red-100 shadow-sm">
                        <i class="bi bi-x-circle-fill text-xl"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
                        <div>
                            <h1 class="text-2xl md:text-3xl font-black text-gray-800 uppercase tracking-tighter">Pesanan Dibatalkan</h1>
                            <p class="text-gray-500 text-sm">Monitor data transaksi yang tidak berhasil diproses.</p>
                        </div>

                        <div class="relative w-full lg:w-96">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="fail-search-input" placeholder="Cari No. Order atau Nama..." 
                                   class="w-full pl-12 pr-12 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none font-medium text-sm transition-all border-none ring-1 ring-gray-100">
                            
                            <div x-show="isSearching" class="absolute right-4 top-1/2 -translate-y-1/2" x-cloak>
                                <div class="animate-spin rounded-full h-4 w-4 border-2 border-orange-500 border-t-transparent"></div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50/50 border-b border-gray-100">
                                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Order ID</th>
                                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Detail Produk</th>
                                        <th class="p-6 text-center text-[10px] font-black uppercase text-gray-400 tracking-widest">Pengiriman</th>
                                        <th class="p-6 text-right text-[10px] font-black uppercase text-gray-400 tracking-widest">Total Harga</th>
                                        <th class="p-6 text-right text-[10px] font-black uppercase text-gray-400 tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="fail-table-body" class="divide-y divide-gray-50">
                                    @include('admin.partials.order-fail-table', ['orders' => $orders])
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('fail-search-input');
            const tableBody = document.getElementById('fail-table-body');

            searchInput.addEventListener('keyup', function() {
                let searchValue = this.value;
                
                // Trigger Alpine Loading State
                window.dispatchEvent(new CustomEvent('searching', { detail: true }));

                fetch(`{{ route('admin.orders.fail') }}?search=${searchValue}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                    window.dispatchEvent(new CustomEvent('searching', { detail: false }));
                })
                .catch(error => {
                    console.warn('Terjadi kesalahan pencarian:', error);
                    window.dispatchEvent(new CustomEvent('searching', { detail: false }));
                });
            });
        });
    </script>
</body>
</html>