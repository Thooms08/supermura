<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pengiriman | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        showModal: false, 
        editMode: false,
        currentMethod: { id: '', nama_metode: '', is_aktif: 1 }
      }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'" 
               class="transition-all duration-300 ease-in-out bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden shrink-0">
            <div class="w-64 h-full">
                @include('admin.sidebar')
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight">Logistik<span class="text-orange-500">Admin</span></h2>
                </div>

                <button @click="editMode = false; currentMethod = { id: '', nama_metode: '', is_aktif: 1 }; showModal = true" 
                        class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-xl text-sm font-bold transition-all flex items-center gap-2 shadow-lg shadow-orange-200">
                    <i class="bi bi-plus-lg"></i> Tambah Metode
                </button>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-bounce">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Metode Pengiriman</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($methods as $method)
                            <tr class="hover:bg-orange-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 font-bold">
                                            <i class="bi bi-truck"></i>
                                        </div>
                                        <span class="font-bold text-gray-700">{{ $method->nama_metode }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $method->is_aktif ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                        {{ $method->is_aktif ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <button @click="editMode = true; currentMethod = {{ json_encode($method) }}; showModal = true" 
                                                class="p-2 bg-gray-100 text-gray-500 hover:bg-orange-500 hover:text-white rounded-lg transition-all">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('shipping-methods.destroy', $method->id) }}" method="POST" onsubmit="return confirm('Hapus metode ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-gray-100 text-red-500 hover:bg-red-500 hover:text-white rounded-lg transition-all">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada metode pengiriman.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
        <div @click.away="showModal = false" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden scale-100 transition-all">
            <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-xl font-black text-gray-800 uppercase tracking-tighter" x-text="editMode ? 'Edit Metode' : 'Metode Baru'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-red-500 transition-colors"><i class="bi bi-x-lg"></i></button>
            </div>
            
            <form :action="editMode ? `/admin/shipping-methods/${currentMethod.id}` : '{{ route('shipping-methods.store') }}'" method="POST" class="p-8 space-y-6">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Kurir / Metode</label>
                    <input type="text" name="nama_metode" x-model="currentMethod.nama_metode" required placeholder="Contoh: JNE Reguler"
                           class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-orange-500 transition-all font-bold text-gray-700">
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status Aktif</label>
                    <select name="is_aktif" x-model="currentMethod.is_aktif" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-orange-500 font-bold text-gray-700">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="pt-4 flex gap-3">
                    <button type="button" @click="showModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-gray-200 transition-all">Batal</button>
                    <button type="submit" class="flex-[2] py-4 bg-orange-500 text-white font-black rounded-2xl shadow-xl shadow-orange-200 uppercase tracking-widest text-xs hover:bg-orange-600 transition-all">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>