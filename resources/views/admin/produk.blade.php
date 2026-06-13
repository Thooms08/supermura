<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk | Admin</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ 
        sidebarOpen: window.innerWidth >= 1024, 
        showModal: false, 
        selectedToko: '{{ $selectedTokoUuid ?? '' }}',
        editMode: false,
        currentProduk: { variants: [], fotos: [], kategori: {} }
      }" 
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden">
        <aside :class="sidebarOpen ? 'w-64' : 'w-0'" class="bg-white border-r border-gray-200 transition-all duration-300 overflow-hidden shrink-0">
            @include('admin.sidebar')
        </aside>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200 sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800">Daftar<span class="text-orange-500">Produk</span></h2>
                </div>
                
                <div class="flex items-center gap-4">
                    <select x-model="selectedToko" @change="window.location.href = '{{ route('produk.index') }}?toko_uuid=' + selectedToko" 
                            class="bg-orange-50 border border-orange-200 text-orange-700 text-sm rounded-xl p-2.5 outline-none font-semibold">
                        <option value="">-- Pilih Toko --</option>
                        @foreach($tokos as $toko)
                            <option value="{{ $toko->uuid }}" {{ $selectedTokoUuid == $toko->uuid ? 'selected' : '' }}>{{ $toko->nama_toko }}</option>
                        @endforeach
                    </select>

                    <button @click="editMode = false; currentProduk = { variants: [], fotos: [], kategori: {} }; showModal = true" 
                            :class="selectedToko ? 'bg-orange-500 hover:bg-orange-600' : 'bg-gray-300 cursor-not-allowed'"
                            class="text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-lg transition-all">
                        <i class="bi bi-plus-circle"></i> Tambah Produk
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                        <i class="bi bi-check-circle-fill"></i> <span class="font-bold">{{ session('success') }}</span>
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($produks as $item)
                    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all">
                        <div class="h-48 bg-gray-100 relative overflow-hidden">
                            @if($item->fotos->count() > 0)
                                <img src="{{ asset('storage/produk/'.$item->fotos->first()->path_foto) }}" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2 bg-black/50 text-white text-[10px] px-2 py-1 rounded-lg">
                                    <i class="bi bi-images"></i> {{ $item->fotos->count() }}
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-800 truncate uppercase text-xs">{{ $item->nama_produk }}</h3>
                            <div class="text-orange-600 font-black text-lg my-2">Rp {{ number_format($item->harga, 0, ',', '.') }}</div>
                            <button @click="editMode = true; currentProduk = {{ $item->toJson() }}; showModal = true" 
                                    class="w-full py-2 bg-gray-100 rounded-xl text-xs font-bold hover:bg-orange-50 hover:text-orange-600 transition-all">Edit Produk</button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
        <div class="bg-white w-full max-w-6xl max-h-[95vh] rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col scale-100 transition-all"
             x-data="{ 
                priceMode: 'single',
                previews: [],
                existingPhotos: [],
                deletedPhotos: [],
                formVariants: [],
                initForm() {
                    this.previews = [];
                    this.deletedPhotos = [];
                    if(editMode) {
                        this.existingPhotos = JSON.parse(JSON.stringify(currentProduk.fotos || []));
                        this.formVariants = JSON.parse(JSON.stringify(currentProduk.variants || []));
                        this.priceMode = this.formVariants.some(v => v.harga_variant !== null) ? 'variant' : 'single';
                    } else {
                        this.existingPhotos = [];
                        this.formVariants = [{size: '', model: '', stok: 0, harga_variant: null}];
                        this.priceMode = 'single';
                    }
                },
                handleFiles(e) {
                    const files = Array.from(e.target.files);
                    if((this.previews.length + this.existingPhotos.length + files.length) > 50) {
                        alert('Maksimal 50 foto!'); return;
                    }
                    files.forEach(file => {
                        const reader = new FileReader();
                        reader.onload = (ex) => { this.previews.push({ url: ex.target.result }) };
                        reader.readAsDataURL(file);
                    });
                },
                removeExistingPhoto(id) {
                    if(confirm('Hapus foto ini?')) {
                        this.deletedPhotos.push(id);
                        this.existingPhotos = this.existingPhotos.filter(f => f.id !== id);
                    }
                }
             }" x-init="$watch('showModal', value => { if(value) initForm() })">
            
            <div class="p-8 border-b flex justify-between items-center bg-gray-50/50">
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tighter" x-text="editMode ? 'Edit Katalog: ' + currentProduk.nama_produk : 'Terbitkan Produk Baru'"></h2>
                <button @click="showModal = false" class="text-gray-400 hover:text-red-500"><i class="bi bi-x-lg text-xl"></i></button>
            </div>
            
            <form :action="editMode ? `/admin/produk/${currentProduk.id}` : '{{ route('produk.store') }}'" method="POST" enctype="multipart/form-data" class="overflow-y-auto p-8 custom-scrollbar">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>
                <input type="hidden" name="toko_uuid" value="{{ $selectedTokoUuid }}">

                <template x-for="id in deletedPhotos" :key="id">
                    <input type="hidden" name="deleted_photos[]" :value="id">
                </template>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Nama Produk</label>
                                <input type="text" name="nama_produk" x-model="currentProduk.nama_produk" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-orange-500">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Harga Dasar (Rp)</label>
                                <input type="number" name="harga" x-model="currentProduk.harga" required class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Kategori</label>
                                <input type="text" name="jenis" x-model="currentProduk.kategori.nama_jenis" :required="!editMode" placeholder="Kaos, Hijab..." class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none">
                            </div>
                            <div class="col-span-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Deskripsi Produk</label>
                                <textarea name="deskripsi" x-model="currentProduk.deskripsi" rows="3" class="w-full bg-gray-50 border-2 border-gray-100 rounded-2xl p-4 outline-none focus:border-orange-500 custom-scrollbar"></textarea>
                            </div>
                        </div>

                        <div x-show="existingPhotos.length > 0" class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Foto Tersimpan</label>
                            <div class="grid grid-cols-5 gap-2 p-3 bg-gray-100 rounded-2xl">
                                <template x-for="foto in existingPhotos" :key="foto.id">
                                    <div class="relative aspect-square rounded-lg overflow-hidden group">
                                        <img :src="'/asset/produk/' + foto.path_foto" class="w-full h-full object-cover">
                                        <button type="button" @click="removeExistingPhoto(foto.id)" 
                                                class="absolute inset-0 bg-red-500/80 text-white opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 block">Tambah Foto Baru (<span x-text="previews.length + existingPhotos.length"></span>/50)</label>
                            <input type="file" name="fotos[]" multiple accept="image/*" @change="handleFiles" class="w-full text-xs file:bg-orange-100 file:border-0 file:rounded-xl file:px-4 file:py-2">
                            <div class="grid grid-cols-5 gap-2 mt-4 max-h-44 overflow-y-auto p-3 border-2 border-dashed border-gray-100 rounded-2xl">
                                <template x-for="(p, index) in previews" :key="index">
                                    <div class="relative aspect-square rounded-lg overflow-hidden border bg-white shadow-sm">
                                        <img :src="p.url" class="w-full h-full object-cover">
                                        <button type="button" @click="previews.splice(index, 1)" class="absolute top-0 right-0 bg-red-500 text-white p-1 rounded-bl-lg"><i class="bi bi-x"></i></button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="bg-orange-50 p-6 rounded-3xl border border-orange-100 mb-4">
                            <label class="text-[10px] font-black text-orange-400 uppercase tracking-widest mb-4 block">Sistem Harga</label>
                            <div class="flex gap-4">
                                <label class="flex-1 cursor-pointer"><input type="radio" x-model="priceMode" value="single" class="hidden peer"><div class="p-3 text-center rounded-xl border-2 peer-checked:border-orange-500 peer-checked:bg-white peer-checked:text-orange-600 text-[10px] font-black transition-all uppercase">Harga Tunggal</div></label>
                                <label class="flex-1 cursor-pointer"><input type="radio" x-model="priceMode" value="variant" class="hidden peer"><div class="p-3 text-center rounded-xl border-2 peer-checked:border-orange-500 peer-checked:bg-white peer-checked:text-orange-600 text-[10px] font-black transition-all uppercase">Harga Varian</div></label>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Varian & Stok</label>
                            <button type="button" @click="formVariants.push({size: '', model: '', stok: 0, harga_variant: null})" class="text-orange-600 text-[10px] font-black underline">+ TAMBAH VARIAN</button>
                        </div>
                        <div class="space-y-3 max-h-[500px] overflow-y-auto pr-3 custom-scrollbar">
                            <template x-for="(variant, index) in formVariants" :key="index">
                                <div class="bg-white p-4 rounded-3xl border-2 border-gray-50 shadow-sm space-y-4 relative group">
                                    <input type="hidden" :name="`variants[${index}][id]`" x-model="variant.id">
                                    <div class="grid grid-cols-3 gap-3">
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-300 uppercase">Size</span>
                                            <input type="text" :name="`variants[${index}][size]`" x-model="variant.size" required class="w-full text-xs p-3 bg-gray-50 rounded-xl outline-none focus:border-orange-500 border border-transparent">
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-300 uppercase">Model</span>
                                            <input type="text" :name="`variants[${index}][model]`" x-model="variant.model" required class="w-full text-xs p-3 bg-gray-50 rounded-xl outline-none focus:border-orange-500 border border-transparent">
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-300 uppercase">Stok</span>
                                            <input type="number" :name="`variants[${index}][stok]`" x-model="variant.stok" required class="w-full text-xs p-3 bg-gray-50 rounded-xl outline-none focus:border-orange-500 border border-transparent">
                                        </div>
                                    </div>
                                    <div>
                                        <span class="text-[9px] font-bold text-gray-300 uppercase">Harga Khusus (Opsional)</span>
                                        <input type="number" :name="`variants[${index}][harga_variant]`" x-model="variant.harga_variant" 
                                               :disabled="priceMode === 'single'" 
                                               :placeholder="priceMode === 'single' ? 'Mode Tunggal Aktif' : 'Kosongkan jika pakai harga dasar'" 
                                               class="w-full text-xs p-3 bg-gray-50 rounded-xl outline-none disabled:bg-gray-100 disabled:text-gray-400 border border-transparent focus:border-orange-500">
                                    </div>
                                    <button type="button" @click="formVariants.splice(index, 1)" class="absolute -top-2 -right-2 bg-white text-red-400 w-8 h-8 rounded-full shadow-md border flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex gap-4">
                    <button type="button" @click="showModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl uppercase tracking-widest text-xs transition-all hover:bg-gray-200">Batal</button>
                    <button type="submit" class="flex-[2] py-4 bg-orange-500 text-white font-black rounded-2xl shadow-xl shadow-orange-200 uppercase tracking-widest text-xs transition-all hover:bg-orange-600" x-text="editMode ? 'Simpan Perubahan' : 'Terbitkan Produk'"></button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>