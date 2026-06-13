<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Gagal | Admin</title>
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
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 transform bg-white lg:translate-x-0 lg:static lg:inset-0 shadow-sm border-r">
            @include('admin.sidebar')
        </aside>

        <main class="flex-1 overflow-x-hidden">
            <header class="flex items-center justify-between px-8 py-5 bg-white border-b lg:hidden">
                <span class="text-xl font-bold text-orange-500 tracking-tighter uppercase">Admin <span class="text-gray-800">Panel</span></span>
                <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none">
                    <i class="bi bi-list text-3xl"></i>
                </button>
            </header>

            <div class="p-6 md:p-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
                    <div>
                        <h1 class="text-2xl font-extrabold text-gray-800">Refund <span class="text-red-500">Gagal</span></h1>
                        <p class="text-sm text-gray-400">Daftar pengembalian dana yang ditolak atau gagal diproses.</p>
                    </div>

                    <div class="relative w-full md:w-80">
                        <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" id="search-refund-fail" placeholder="Cari nama, produk, alasan..." 
                               class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none text-sm transition-all shadow-sm">
                    </div>
                </div>

                <div id="fail-list-container">
                    @include('admin.partials.refund-fail-list')
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById('search-refund-fail').addEventListener('input', function(e) {
            let keyword = e.target.value;
            let container = document.getElementById('fail-list-container');

            // Feedback visual saat mencari
            container.style.opacity = '0.5';

            fetch(`{{ route('admin.refunds.search') }}?keyword=${keyword}`)
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;
                    container.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Terjadi kesalahan:', error);
                    container.style.opacity = '1';
                });
        });
    </script>
</body>
</html>