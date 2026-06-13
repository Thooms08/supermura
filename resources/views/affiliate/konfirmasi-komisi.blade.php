<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Berhasil | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .success-pulse {
            animation: pulse-orange 2s infinite;
        }

        @keyframes pulse-orange {
            0% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.4); }
            70% { box-shadow: 0 0 0 20px rgba(249, 115, 22, 0); }
            100% { box-shadow: 0 0 0 0 rgba(249, 115, 22, 0); }
        }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = (window.innerWidth >= 1024)">
    
    <div class="flex h-screen overflow-hidden">
        <aside 
            :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }" 
            class="transition-all duration-300 transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative">
            <div class="w-64 h-full">
                @include('affiliate.sidebar')
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold">Status <span class="text-orange-500">Pengajuan</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 flex justify-center items-center">
                <div class="w-full max-w-lg">
                    <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(255,120,0,0.1)] p-8 md:p-12 border border-orange-50 text-center relative overflow-hidden">
                        
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-orange-50 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-orange-50 rounded-full blur-3xl"></div>

                        <div class="relative mb-8">
                            <div class="w-24 h-24 bg-orange-500 text-white rounded-full flex items-center justify-center mx-auto success-pulse shadow-xl shadow-orange-200">
                                <i class="bi bi-clock-history text-4xl"></i>
                            </div>
                            <div class="absolute top-0 right-[35%] w-8 h-8 bg-green-500 text-white rounded-full border-4 border-white flex items-center justify-center">
                                <i class="bi bi-check-lg text-sm font-bold"></i>
                            </div>
                        </div>

                        <h1 class="text-3xl font-black text-gray-800 mb-4 tracking-tight">Pengajuan Diproses!</h1>
                        <p class="text-gray-500 leading-relaxed mb-8">
                            Permintaan penarikan komisi Anda telah kami terima dan sedang dalam antrean verifikasi.
                        </p>

                        <div class="bg-orange-50 border border-orange-100 rounded-3xl p-6 mb-8 text-left">
                            <div class="flex gap-4 items-start">
                                <div class="bg-white p-3 rounded-2xl shadow-sm">
                                    <i class="bi bi-calendar-check text-orange-600 text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800">Estimasi Pencairan</h4>
                                    <p class="text-xs text-orange-700 font-medium mt-1">
                                        Membutuhkan waktu <span class="font-bold underline">1 - 7 hari kerja</span> (Sabtu, Minggu & Libur Nasional tidak dihitung).
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center gap-2 text-gray-400 text-xs font-medium mb-10">
                            <i class="bi bi-shield-check text-green-500"></i>
                            Dana akan ditransfer sesuai detail rekening yang Anda berikan.
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <a href="{{ route('affiliate.komisi.index') }}" 
                               class="flex items-center justify-center gap-2 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all active:scale-95">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <a href="{{ route('affiliate.komisi.index') }}" 
                               class="flex items-center justify-center gap-2 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl font-bold shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-95 transition-all">
                                <i class="bi bi-layout-text-sidebar-reverse"></i> Lihat Riwayat
                            </a>
                        </div>

                    </div>

                    <p class="text-center mt-8 text-gray-400 text-xs font-bold uppercase tracking-widest">
                        Butuh bantuan? <a href="#" class="text-orange-500 hover:underline">Hubungi Admin</a>
                    </p>
                </div>
            </main>
        </div>
    </div>

</body>
</html>