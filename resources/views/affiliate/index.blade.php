<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Affiliator Dashboard | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #ff6b00; border-radius: 10px; }
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
                @include('affiliate.sidebar')
            </div>
        </aside>

        <div 
            x-show="sidebarOpen" 
            @click="sidebarOpen = false" 
            class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity"
            x-transition:enter="transition opacity ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition opacity ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            x-cloak>
        </div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block uppercase">
                        Affiliator<span class="text-orange-500">Dashboard</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="p-6 md:p-10 bg-gradient-to-br from-orange-500 to-orange-600 relative">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10 text-white">
                            <div class="flex items-center space-x-5">
                                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/30">
                                    <i class="bi bi-person-badge text-3xl"></i>
                                </div>
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight uppercase">
                                        SELAMAT DATANG, {{ $affiliator->nama }}
                                    </h1>
                                    <p class="text-orange-50 mt-1 font-medium opacity-90">ID: {{ $affiliator->id_unik }}</p>
                                </div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-xl p-5 rounded-2xl border border-white/20 min-w-[280px]">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-orange-200">Waktu Real-Time</span>
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <div class="text-4xl font-mono font-bold tracking-tighter" id="clock-time">00:00:00</div>
                                <div class="text-sm font-medium text-orange-50 mt-1" id="clock-date">Memuat tanggal...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-[2rem] border border-orange-100 shadow-xl shadow-orange-500/5 mb-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-4 text-center md:text-left">
                            <div class="w-14 h-14 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                                <i class="bi bi-share-fill"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-gray-800 uppercase text-sm tracking-tight">Link Referral Anda</h3>
                                <p class="text-xs text-gray-400 mt-1">Gunakan link ini untuk merekrut orang & dapatkan komisi <span class="font-bold text-orange-600">Rp {{ number_format($komisi_rekrut, 0, ',', '.') }}</span>!
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center bg-gray-50 border border-gray-100 p-2 rounded-2xl w-full md:w-auto overflow-hidden">
                            <code class="px-4 text-sm font-bold text-orange-600 truncate max-w-[200px] md:max-w-md" id="refLink">
                                {{ url('/daftar-affiliate?ref=' . $affiliator->id_unik) }}
                            </code>
                            <button onclick="copyRef()" class="ml-2 bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 shrink-0 shadow-lg shadow-orange-200">
                                <i class="bi bi-copy"></i> SALIN
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Update Jam Real-Time
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
            const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
            
            document.getElementById('clock-time').innerText = now.toLocaleTimeString('id-ID', timeOptions).replace(/\./g, ':');
            document.getElementById('clock-date').innerText = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Fungsi Salin Link dengan SweetAlert2 (Tanpa Tombol OK)
        function copyRef() {
            const link = document.getElementById('refLink').innerText;
            navigator.clipboard.writeText(link).then(() => {
                // Konfigurasi SweetAlert2 sebagai Toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false, // Menghilangkan tombol OK
                    timer: 2000, // Menghilang otomatis dalam 2 detik
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Link Referral disalin!'
                });
            }).catch(err => {
                console.error('Gagal menyalin: ', err);
            });
        }
    </script>
</body>
</html>