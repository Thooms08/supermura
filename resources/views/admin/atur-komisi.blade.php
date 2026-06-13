<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Komisi | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ 
        sidebarOpen: window.innerWidth >= 1024,
        modalOpen: false,
        selectedProduk: null,
        existingKomisi: {},

        {{-- Fungsi untuk Edit --}}
        editKomisi(produk) {
            this.selectedProduk = produk;
            this.existingKomisi = {};
            {{-- Map data komisi dari DB ke objek local Alpine --}}
            produk.komisis.forEach(k => {
                let key = k.produk_variant_id ? k.produk_variant_id : 'single';
                this.existingKomisi[key] = Math.floor(k.nominal_komisi);
            });
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
      }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden relative">
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'" class="transition-all duration-300 transform bg-white border-r fixed inset-y-0 z-50 lg:relative shadow-xl lg:shadow-none">
            @include('admin.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800">Atur <span class="text-orange-500">Komisi Affiliator</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3 animate-pulse">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
                @endif

                <div class="max-w-5xl mx-auto space-y-8">
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center" x-show="!selectedProduk">
                        <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-plus-circle-dotted text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Atur Komisi Baru</h3>
                        <p class="text-gray-500 text-sm mb-6">Klik tombol di bawah untuk memilih produk dan menentukan nominal komisi.</p>
                        <button @click="modalOpen = true; existingKomisi = {}" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-orange-200 transition-all">
                            <i class="bi bi-search mr-2"></i> PILIH PRODUK
                        </button>
                    </div>

                    <template x-if="selectedProduk">
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in-up">
                            <div class="bg-orange-500 p-6 flex items-center justify-between text-white">
                                <div class="flex items-center gap-4">
                                    <div class="bg-white p-1 rounded-xl">
                                        <img :src="'/asset/produk/' + (selectedProduk.fotos[0]?.path_foto || '')" class="w-12 h-12 rounded-lg object-cover bg-gray-200" onerror="this.src='https://via.placeholder.com/150'">
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-lg" x-text="selectedProduk.nama_produk"></h4>
                                        <p class="text-xs text-orange-100 uppercase tracking-widest font-bold">Sedang Mengatur Komisi</p>
                                    </div>
                                </div>
                                <button @click="selectedProduk = null" class="text-white/70 hover:text-white"><i class="bi bi-x-circle text-2xl"></i></button>
                            </div>

                            <form action="{{ route('admin.komisi.store') }}" method="POST" class="p-8">
                                @csrf
                                <input type="hidden" name="produk_id" :value="selectedProduk.id">

                                <template x-if="selectedProduk.variants.length === 0">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nominal Komisi (IDR)</label>
                                        <div class="relative">
                                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                                            <input type="number" name="komisi[single]" x-model="existingKomisi['single']" class="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 rounded-2xl outline-none font-bold text-lg transition-all" placeholder="0">
                                        </div>
                                    </div>
                                </template>

                                <template x-if="selectedProduk.variants.length > 0">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <template x-for="variant in selectedProduk.variants" :key="variant.id">
                                            <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                                                <label class="block text-xs font-bold text-orange-600 uppercase mb-3" x-text="'Variant: ' + variant.model + ' (' + variant.size + ')'"></label>
                                                <div class="relative">
                                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-sm">Rp</span>
                                                    <input type="number" :name="'komisi[' + variant.id + ']'" x-model="existingKomisi[variant.id]" class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-bold" placeholder="0">
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </template>

                                <div class="flex gap-4 mt-8">
                                    <button type="submit" class="flex-1 bg-gray-800 hover:bg-black text-white font-bold py-4 rounded-2xl transition-all shadow-lg">
                                        <i class="bi bi-check2-all mr-2"></i> SIMPAN PERUBAHAN
                                    </button>
                                </div>
                            </form>
                        </div>
                    </template>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                            <h3 class="font-bold text-gray-800">Daftar Komisi Aktif</h3>
                            <span class="bg-orange-100 text-orange-600 text-xs font-bold px-3 py-1 rounded-full">{{ $komisiAktif->count() }} Produk</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-gray-50/50 text-gray-400 text-[10px] uppercase tracking-widest font-bold">
                                    <tr>
                                        <th class="px-6 py-4">Produk</th>
                                        <th class="px-6 py-4">Tipe</th>
                                        <th class="px-6 py-4">Rincian Komisi</th>
                                        <th class="px-6 py-4 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm">
                                    @forelse($komisiAktif as $ka)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ asset('storage/produk/' . ($ka->fotos->first()->path_foto ?? '')) }}" class="w-10 h-10 rounded-lg object-cover bg-gray-100" onerror="this.src='https://via.placeholder.com/150'">
                                                <span class="font-bold text-gray-700">{{ $ka->nama_produk }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $ka->variants->count() > 0 ? 'bg-purple-50 text-purple-600' : 'bg-blue-50 text-blue-600' }}">
                                                {{ $ka->variants->count() > 0 ? 'Variant' : 'Satuan' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($ka->komisis as $kom)
                                                <span class="text-[11px] bg-gray-100 text-gray-600 px-2 py-1 rounded-lg font-medium">
                                                    @if($kom->produk_variant_id)
                                                        {{ $kom->variant->model }}: 
                                                    @endif
                                                    Rp{{ number_format($kom->nominal_komisi, 0, ',', '.') }}
                                                </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <button @click="editKomisi({{ $ka }})" class="p-2 text-blue-500 hover:bg-blue-50 rounded-xl transition-colors">
                                                    <i class="bi bi-pencil-square text-lg"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.komisi.destroy', $ka->id) }}" method="POST" onsubmit="return confirm('Hapus semua pengaturan komisi untuk produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-xl transition-colors">
                                                        <i class="bi bi-trash3 text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-10 text-center text-gray-400 italic">Belum ada komisi yang diatur.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4">
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl relative overflow-hidden flex flex-col max-h-[80vh]">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0">
                    <h3 class="font-bold text-gray-800 text-xl">Pilih Produk</h3>
                    <button @click="modalOpen = false" class="p-2 rounded-full hover:bg-gray-100 text-gray-400"><i class="bi bi-x-lg"></i></button>
                </div>
                
                <div class="p-4 overflow-y-auto custom-scrollbar">
                    <div class="grid grid-cols-1 gap-3">
                        @foreach($produks as $p)
                        <div @click="editKomisi({{ $p }}); modalOpen = false" class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 hover:border-orange-500 hover:bg-orange-50 cursor-pointer transition-all group">
                            <div class="w-14 h-14 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                                <img src="{{ asset('/asset/produk/' . ($p->fotos->first()->path_foto ?? '')) }}" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/150'">
                            </div>
                            <div class="flex-1">
                                <h5 class="font-bold text-gray-800 group-hover:text-orange-600">{{ $p->nama_produk }}</h5>
                                <p class="text-xs text-gray-400">
                                    {{ $p->variants->count() > 0 ? $p->variants->count() . ' Variants' : 'Produk Satuan' }}
                                </p>
                            </div>
                            <i class="bi bi-plus-circle text-orange-400 opacity-0 group-hover:opacity-100 transition-opacity"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>