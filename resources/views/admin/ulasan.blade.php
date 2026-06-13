<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moderasi Ulasan | Admin </title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
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
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight">
                        ThomsFashion<span class="text-orange-500 text-sm ml-1 uppercase">Moderasi Ulasan</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                        <div class="p-6 md:p-10 bg-gradient-to-br from-orange-500 to-orange-600 relative">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                <div>
                                    <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Manajemen Ulasan</h1>
                                    <p class="text-orange-50 mt-1 font-medium opacity-90">Pantau dan kelola feedback dari pelanggan secara real-time.</p>
                                </div>
                                <div class="bg-white/20 backdrop-blur-md px-6 py-3 rounded-2xl border border-white/30 text-white text-center">
                                    <span class="text-xs font-bold uppercase tracking-widest block mb-1">Total Masuk</span>
                                    <span class="text-2xl font-black">{{ $ulasans->count() }} Ulasan</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl shadow-lg shadow-green-100 font-bold text-sm flex items-center gap-3">
                            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                        </div>
                    @endif

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-400 border-b border-gray-100">
                                        <th class="p-6 text-[10px] font-black uppercase tracking-widest">Produk & Pengulas</th>
                                        <th class="p-6 text-[10px] font-black uppercase tracking-widest">Rating & Komentar</th>
                                        <th class="p-6 text-[10px] font-black uppercase tracking-widest text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @forelse($ulasans as $u)
                                    <tr class="hover:bg-orange-50/20 transition-colors">
                                        <td class="p-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center font-black text-xl shrink-0">
                                                    {{ substr($u->nama, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-black text-gray-800 leading-tight truncate">{{ $u->nama }}</p>
                                                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-1 truncate">
                                                        Item: <span class="text-orange-500">{{ $u->produk->nama_produk ?? 'Produk Dihapus' }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-6">
                                            <div class="flex text-orange-500 text-[10px] mb-2">
                                                @for($i=1; $i<=5; $i++)
                                                    <i class="bi {{ $i <= $u->rating ? 'bi-star-fill' : 'bi-star' }} me-0.5"></i>
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-600 leading-relaxed mb-3 italic">"{{ $u->komentar }}"</p>
                                            
                                            @if($u->foto)
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($u->foto as $img)
                                                    @php
                                                        $imgUrl = str_starts_with($img, 'asset/') ? asset(str_replace('asset/', 'storage/', $img)) : asset('storage/ulasan/' . $img);
                                                    @endphp
                                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-gray-100">
                                                        <img src="{{ $imgUrl }}" class="w-full h-full object-cover">
                                                    </div>
                                                @endforeach
                                            </div>
                                            @endif
                                        </td>
                                        <td class="p-6 text-center">
                                            <form action="{{ route('admin.ulasan.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Hapus ulasan ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-10 h-10 bg-red-50 text-red-500 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="p-20 text-center">
                                            <div class="flex flex-col items-center">
                                                <i class="bi bi-chat-left-quote text-6xl text-gray-100 mb-4"></i>
                                                <p class="text-gray-400 font-medium italic">Belum ada ulasan masuk.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>
</body>
</html>