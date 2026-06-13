<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat Promosi | Affiliator</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
               class="transition-all duration-300 transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative">
            <div class="w-64 h-full">@include('affiliate.sidebar')</div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold">Alat <span class="text-orange-500">Promosi</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="mb-8">
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Materi Iklan Siap Pakai</h1>
                    <p class="text-gray-400 text-sm">Gunakan poster dan caption di bawah ini untuk mempromosikan produk Anda.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($promosi as $item)
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden flex flex-col">
                        <div class="h-64 bg-gray-100 relative group">
                            @if($item->poster)
                                <img src="{{ asset('storage/alat-promosi/'.$item->poster) }}" class="w-full h-full object-cover">
                                <a href="{{ route('affiliate.alat-promosi.download', $item->poster) }}" 
                                   class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="bg-white text-orange-600 px-6 py-2 rounded-full font-bold text-xs flex items-center gap-2">
                                        <i class="bi bi-download"></i> Download Poster
                                    </span>
                                </a>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <i class="bi bi-image text-5xl mb-2"></i>
                                    <span class="text-[10px] font-bold uppercase">Tanpa Poster</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 flex flex-col flex-1">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-[10px] font-black uppercase text-orange-500 tracking-widest">Caption Iklan</span>
                                <button onclick="copyToClipboard('{{ $item->id }}')" 
                                        class="text-[10px] font-bold text-gray-400 hover:text-orange-600 flex items-center gap-1 transition-colors">
                                    <i class="bi bi-clipboard"></i> Salin Teks
                                </button>
                            </div>
                            
                            <div class="bg-gray-50 rounded-2xl p-4 text-xs text-gray-600 leading-relaxed italic flex-1 border border-gray-100">
                                <span id="caption-{{ $item->id }}">{{ $item->caption ?? '-' }}</span>
                            </div>

                            <div class="mt-6 grid grid-cols-2 gap-3">
                                @if($item->poster)
                                    <a href="{{ route('affiliate.alat-promosi.download', $item->poster) }}" 
                                       class="flex items-center justify-center gap-2 py-3 bg-orange-50 text-orange-600 rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-orange-100 transition-all">
                                        <i class="bi bi-download"></i> Download
                                    </a>
                                @endif
                                <button onclick="copyToClipboard('{{ $item->id }}')" 
                                        class="flex items-center justify-center gap-2 py-3 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-wider hover:bg-black transition-all {{ !$item->poster ? 'col-span-2' : '' }}">
                                    <i class="bi bi-files"></i> Salin Caption
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-full py-20 text-center opacity-30">
                            <i class="bi bi-megaphone text-6xl"></i>
                            <p class="mt-4 font-black uppercase tracking-widest">Belum ada materi promosi tersedia.</p>
                        </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>

    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-full text-xs font-bold shadow-2xl opacity-0 transition-opacity z-[100] pointer-events-none">
        <i class="bi bi-check-circle-fill text-green-400 mr-2"></i> Caption berhasil disalin!
    </div>

    <script>
        function copyToClipboard(id) {
            const text = document.getElementById('caption-' + id).innerText;
            if(text === '-') return;

            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById('toast');
                toast.classList.replace('opacity-0', 'opacity-100');
                setTimeout(() => {
                    toast.classList.replace('opacity-100', 'opacity-0');
                }, 2000);
            });
        }
    </script>
</body>
</html>