<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat Promosi | Admin</title>
     @include('partials.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }" class="transition-all duration-300 transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative">
            <div class="w-64 h-full">@include('admin.sidebar')</div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold">Marketing <span class="text-orange-500">Tools</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
                @endif

                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-10">
                    <h3 class="text-xl font-black mb-6 flex items-center gap-2">
                        <i class="bi bi-plus-circle-fill text-orange-500"></i> Tambah Alat Promosi
                    </h3>
                    <form action="{{ route('admin.alat-promosi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Upload Poster (Opsional)</label>
                                <div class="relative group">
                                    <input type="file" name="poster" class="w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all border-2 border-dashed border-gray-200 p-4 rounded-2xl group-hover:border-orange-200">
                                </div>
                                <p class="text-[10px] text-gray-400 mt-2 italic">*Format: JPG, PNG (Maks 2MB)</p>
                            </div>
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Caption Iklan (Opsional)</label>
                                <textarea name="caption" rows="4" class="w-full p-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 focus:bg-white rounded-2xl outline-none transition-all text-sm" placeholder="Tulis caption promosi di sini..."></textarea>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl font-black shadow-lg shadow-orange-200 hover:scale-105 transition-all">
                                SIMPAN IKLAN
                            </button>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($promosi as $item)
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-orange-100 transition-all duration-500">
                        <div class="h-56 bg-gray-100 overflow-hidden relative">
                            @if($item->poster)
                                <img src="{{ asset('asset/alat-promosi/'.$item->poster) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <i class="bi bi-image text-5xl mb-2"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Tidak ada poster</span>
                                </div>
                            @endif
                            <form action="{{ route('admin.alat-promosi.destroy', $item->id) }}" method="POST" class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 bg-red-500 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-red-600 transition-colors" onclick="return confirm('Hapus konten ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                        <div class="p-6">
                            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-3">Caption</p>
                            <div class="text-sm text-gray-600 leading-relaxed line-clamp-4 italic">
                                {!! $item->caption ? nl2br(e($item->caption)) : '<span class="text-gray-300">-</span>' !!}
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                                <span class="text-[10px] font-medium text-gray-400">
                                    <i class="bi bi-calendar-event mr-1"></i> {{ $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-full py-20 text-center opacity-30">
                            <i class="bi bi-megaphone text-6xl"></i>
                            <p class="mt-4 font-black uppercase tracking-widest">Belum ada alat promosi</p>
                        </div>
                    @endforelse
                </div>

            </main>
        </div>
    </div>
</body>
</html>