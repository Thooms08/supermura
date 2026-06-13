<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-0'"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('affiliate.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block uppercase">Notifikasi</h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-4xl mx-auto">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Aktivitas Terbaru</h3>
                        <span class="px-4 py-1.5 bg-orange-100 text-orange-600 rounded-full text-[10px] font-black uppercase tracking-widest">
                            Real-time Update
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse($notifications as $notif)
                            @if($notif->tipe_notif == 'komisi')
                                {{-- Card Notifikasi Pengajuan Komisi --}}
                                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-start gap-4">
                                    <div class="w-12 h-12 shrink-0 rounded-2xl flex items-center justify-center 
                                        @if($notif->status == 'pending') bg-yellow-50 text-yellow-600 
                                        @elseif($notif->status == 'success') bg-green-50 text-green-600 
                                        @else bg-red-50 text-red-600 @endif">
                                        <i class="bi bi-cash-stack text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Pengajuan Komisi</span>
                                            <span class="text-[10px] font-medium text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">
                                            Pengajuan sebesar <span class="text-orange-600">Rp {{ number_format($notif->nominal, 0, ',', '.') }}</span> 
                                            @if($notif->status == 'pending') sedang diproses.
                                            @elseif($notif->status == 'success') telah berhasil dicairkan!
                                            @else gagal diproses. Silakan hubungi admin. @endif
                                        </p>
                                        <div class="mt-2">
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter
                                                @if($notif->status == 'pending') bg-yellow-100 text-yellow-700
                                                @elseif($notif->status == 'success') bg-green-100 text-green-700
                                                @else bg-red-100 text-red-700 @endif">
                                                Status: {{ $notif->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Card Notifikasi Rekrut Affiliator --}}
                                <div class="bg-white p-5 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow flex items-start gap-4">
                                    <div class="w-12 h-12 shrink-0 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                        <i class="bi bi-person-plus-fill text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-[10px] font-black uppercase tracking-widest text-orange-400">Rekrut Baru</span>
                                            <span class="text-[10px] font-medium text-gray-400">{{ $notif->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm font-bold text-gray-800">
                                            <span class="text-orange-600">{{ $notif->nama }}</span> dari {{ $notif->domisili }} telah bergabung menggunakan kode Anda!
                                        </p>
                                        <p class="text-[11px] text-green-600 font-bold mt-1">
                                            <i class="bi bi-plus-circle-fill mr-1"></i> Bonus Komisi: Rp {{ number_format($notif->nominal_bonus, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="bg-white p-20 rounded-[3rem] border-2 border-dashed border-gray-100 text-center">
                                <div class="w-20 h-20 bg-gray-50 text-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="bi bi-bell-slash text-4xl"></i>
                                </div>
                                <h4 class="text-lg font-bold text-gray-400 uppercase tracking-tighter">Belum ada notifikasi</h4>
                                <p class="text-sm text-gray-300">Aktivitas Anda akan muncul secara otomatis di sini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    {{-- Script AJAX untuk Update Badge di Sidebar secara Real-time --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function updateNotifBadge() {
            $.ajax({
                url: "{{ route('affiliate.notifikasi.count') }}",
                type: "GET",
                success: function(data) {
                    const badge = $('#notifBadge');
                    if(data.total > 0) {
                        badge.text(data.total).show();
                    } else {
                        badge.hide();
                    }
                }
            });
        }

        // Jalankan setiap 10 detik
        setInterval(updateNotifBadge, 10000);
        updateNotifBadge(); // Load awal
    </script>
</body>
</html>