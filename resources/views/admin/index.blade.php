<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .transition-all {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 300ms;
        }

        /* Custom Orange Theme for Bootstrap Components */
        .btn-orange {
            background-color: #f97316;
            color: white;
            border: none;
        }
        .btn-orange:hover {
            background-color: #ea580c;
            color: white;
        }
        .form-control:focus {
            border-color: #f97316;
            box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25);
        }
        .modal-header {
            background: linear-gradient(to right, #f97316, #ea580c);
            color: white;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #fdba74;
            border-radius: 10px;
        }
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
                @include('admin.sidebar')
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
                    <button 
                        @click="sidebarOpen = !sidebarOpen" 
                        class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors focus:outline-none"
                    >
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Dashboard<span class="text-orange-500">Admin</span>
                    </h2>
                </div>

                <div class="flex items-center gap-3 cursor-pointer group" data-bs-toggle="modal" data-bs-target="#modalEditProfile">
                    <div class="text-right hidden sm:block">
                        <p class="text-[10px] text-orange-500 font-bold uppercase mt-1 tracking-widest group-hover:text-orange-600 transition-colors">
                            {{ Auth::user()->name }}
                        </p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 border border-orange-200 shadow-sm group-hover:bg-orange-200 group-hover:scale-105 transition-all">
                        <i class="bi bi-person-fill text-xl"></i>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-6 rounded-2xl" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                    <div class="p-6 md:p-10 bg-gradient-to-br from-orange-500 to-orange-600 relative">
                        <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                        
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 relative z-10">
                            <div class="flex items-center space-x-5">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">
                                        Hallo {{ Auth::user()->name }}, Selamat Datang
                                    </h1>
                                    <p class="text-orange-50 mt-1 font-medium opacity-90">
                                        Sistem siap digunakan. Cek laporan terbaru Anda hari ini.
                                    </p>
                                </div>
                            </div>

                            <div class="bg-white/10 backdrop-blur-xl p-5 rounded-2xl border border-white/20 text-white min-w-[280px] shadow-lg">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-orange-200">Waktu Real-Time</span>
                                    <i class="bi bi-clock-history text-orange-200"></i>
                                </div>
                                <div class="text-4xl font-mono font-bold tracking-tighter" id="clock-time">00:00:00</div>
                                <div class="text-sm font-medium text-orange-50 mt-1" id="clock-date">Memuat tanggal...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="h-64 bg-white border border-dashed border-gray-300 rounded-3xl flex items-center justify-center text-gray-400 italic">
                        Grafik Penjualan Akan Muncul Di Sini
                    </div>
                    <div class="h-64 bg-white border border-dashed border-gray-300 rounded-3xl flex items-center justify-center text-gray-400 italic">
                        Aktivitas Terbaru Akan Muncul Di Sini
                    </div>
                </div>-->

            </main>
        </div>
    </div>

    <div class="modal fade" id="modalEditProfile" tabindex="-1" aria-labelledby="modalEditProfileLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-2xl rounded-3xl overflow-hidden">
                <div class="modal-header border-0 p-4">
                    <h5 class="modal-title fw-bold" id="modalEditProfileLabel">
                        <i class="bi bi-person-circle me-2"></i> Edit Profil Admin
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4">
                        
                        @if($errors->any())
                            <div class="alert alert-danger rounded-xl py-2 px-3 mb-4">
                                <ul class="mb-0 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-gray-600">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control form-control-lg rounded-xl text-sm" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-gray-600">Alamat Email</label>
                            <input type="email" name="email" class="form-control form-control-lg rounded-xl text-sm" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>

                        <div class="p-3 bg-orange-50 rounded-2xl border border-orange-100 mb-3 mt-4">
                            <p class="text-[11px] text-orange-700 font-bold uppercase mb-2 tracking-wider">Ganti Password (Opsional)</p>
                            
                            <div class="mb-3">
                                <label class="form-label small text-gray-500">Password Baru</label>
                                <input type="password" name="password" class="form-control rounded-xl text-sm" placeholder="Isi jika ingin ganti">
                            </div>

                            <div class="mb-0">
                                <label class="form-label small text-gray-500">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control rounded-xl text-sm" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 p-4 pt-0">
                        <button type="button" class="btn btn-light rounded-xl px-4 text-sm fw-bold" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-orange rounded-xl px-4 text-sm fw-bold shadow-sm">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi Jam Real-time
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false, timeZone: 'Asia/Jakarta' };
            const dateOptions = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', timeZone: 'Asia/Jakarta' };
            
            document.getElementById('clock-time').innerText = now.toLocaleTimeString('id-ID', timeOptions);
            document.getElementById('clock-date').innerText = now.toLocaleDateString('id-ID', dateOptions);
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Otomatis buka modal jika ada error validasi
        @if($errors->any())
            const modalEditProfile = new bootstrap.Modal(document.getElementById('modalEditProfile'));
            modalEditProfile.show();
        @endif
    </script>

</body>
</html>