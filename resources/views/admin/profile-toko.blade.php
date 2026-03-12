<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Toko | Admin</title>
     @include('partials.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024, showModal: false, editMode: false, currentToko: {} }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="{'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen}"
               class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('admin.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight">Profile<span class="text-orange-500">Toko</span></h2>
                </div>
                <button @click="editMode = false; currentToko = {}; showModal = true" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-orange-200">
                    <i class="bi bi-plus-lg"></i> Toko Baru
                </button>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($tokos as $toko)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="h-32 bg-gradient-to-r from-orange-400 to-orange-500 relative">
                            <div class="absolute -bottom-10 left-6">
                                <img src="{{ $toko->foto_toko ? asset('asset/profile-toko/'.$toko->foto_toko) : 'https://ui-avatars.com/api/?name='.$toko->nama_toko.'&background=FB923C&color=fff' }}" 
                                     class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-sm bg-white">
                            </div>
                        </div>
                        <div class="pt-12 p-6">
                            <h3 class="text-xl font-bold text-gray-800">{{ $toko->nama_toko }}</h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $toko->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            
                            <div class="space-y-2 mb-6">
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <i class="bi bi-envelope text-orange-500"></i> {{ $toko->email }}
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <i class="bi bi-whatsapp text-orange-500"></i> {{ $toko->no_whatsapp }}
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-600">
                                    <i class="bi bi-geo-alt text-orange-500"></i> {{ Str::limit($toko->alamat, 40) }}
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button @click="editMode = true; currentToko = {{ json_encode($toko) }}; showModal = true" 
                                        class="flex-1 bg-gray-100 hover:bg-orange-100 hover:text-orange-600 text-gray-600 py-2 rounded-xl text-sm font-bold transition-colors">
                                    Edit
                                </button>
                                <form action="{{ route('profile-toko.destroy', $toko->id) }}" method="POST" onsubmit="return confirm('Hapus toko ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-xl transition-all">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-300">
                        <i class="bi bi-shop text-5xl text-gray-200"></i>
                        <p class="text-gray-400 mt-4 italic">Belum ada data toko.</p>
                    </div>
                    @endforelse
                </div>
            </main>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-xl font-bold text-gray-800" x-text="editMode ? 'Edit Profile Toko' : 'Tambah Toko Baru'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600"><i class="bi bi-x-lg"></i></button>
            </div>
            <form :action="editMode ? `/admin/profile-toko/${currentToko.id}` : '{{ route('profile-toko.store') }}'" 
                  method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if ($errors->any())
                        <div class="md:col-span-2 p-3 bg-red-50 text-red-500 rounded-xl text-xs">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Foto Toko</label>
                        <input type="file" name="foto_toko" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Toko *</label>
                        <input type="text" name="nama_toko" x-model="currentToko.nama_toko" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email *</label>
                        <input type="email" name="email" x-model="currentToko.email" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">WhatsApp *</label>
                        <input type="text" name="no_whatsapp" x-model="currentToko.no_whatsapp" required class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Alamat</label>
                        <textarea name="alamat" x-model="currentToko.alamat" rows="2" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Deskripsi</label>
                        <textarea name="deskripsi" x-model="currentToko.deskripsi" rows="2" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none"></textarea>
                    </div>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-200 transition-all">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>