<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Sukses | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024, isSearching: false }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="{'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen}"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none">
            <div class="w-64 h-full">@include('admin.sidebar')</div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold">Riwayat <span class="text-orange-500">Sukses</span></h2>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center text-green-600 border border-green-200 shadow-sm">
                        <i class="bi bi-check2-all text-xl"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-7xl mx-auto">
                    
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
                        <div>
                            <h1 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Transaksi Selesai</h1>
                            <p class="text-gray-500 text-sm">Semua pesanan yang telah berhasil dikirim dan diterima.</p>
                        </div>
                        
                        <div class="relative w-full lg:w-96">
                            <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" id="ajax-search" placeholder="Cari No. Pesanan atau Nama..." 
                                   class="w-full pl-12 pr-12 py-3 bg-white border border-gray-200 rounded-2xl shadow-sm focus:ring-2 focus:ring-orange-500 outline-none font-medium text-sm transition-all">
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
                                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Order ID</th>
                                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Penerima</th>
                                        <th class="p-6 text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Produk</th>
                                        <th class="p-6 text-center text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Pengiriman</th>
                                        <th class="p-6 text-right text-[10px] font-black uppercase text-gray-400 tracking-[0.2em]">Total Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody id="order-table-body">
                                    @include('admin.partials.order-success-table', ['orders' => $orders])
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
            const searchInput = document.getElementById('ajax-search');
            const tableBody = document.getElementById('order-table-body');

            searchInput.addEventListener('keyup', function() {
                // Trigger spinner AlpineJS (jika ingin lebih canggih bisa pakai global state)
                let searchValue = this.value;

                fetch(`{{ route('admin.orders.success') }}?search=${searchValue}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    tableBody.innerHTML = html;
                })
                .catch(error => console.warn('Pencarian bermasalah:', error));
            });
        });
    </script>
</body>
</html>