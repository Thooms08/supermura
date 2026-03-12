<div class="bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative">
    
    <a href="{{ route('product.show', $p->id) }}" class="absolute inset-0 z-10" aria-label="Lihat Detail {{ $p->nama_produk }}"></a>

    <div class="relative aspect-[4/5] overflow-hidden bg-gray-50">
        {{-- Logika Foto Produk: Ambil dari relasi 'fotos' (tabel produk_fotos) kolom 'path_foto' --}}
        @if($p->fotos && $p->fotos->isNotEmpty())
            <img src="{{ asset('asset/produk/' . $p->fotos->first()->path_foto) }}" 
                 alt="{{ $p->nama_produk }}" 
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <i class="bi bi-image text-gray-200 text-4xl"></i>
            </div>
        @endif

        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        
        <div class="absolute top-4 left-4 z-20">
            <span class="bg-white/90 backdrop-blur-md text-orange-600 text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-sm">
                {{ $p->kategori->nama_jenis ?? 'Produk' }}
            </span>
        </div>
    </div>

    <div class="p-5 md:p-6">
        <div class="mb-3">
            <h3 class="text-sm md:text-base font-bold text-gray-800 group-hover:text-orange-500 transition-colors line-clamp-1">
                {{ $p->nama_produk }}
            </h3>
        </div>

        <div class="flex items-center justify-between gap-2 mt-4">
            <div class="flex flex-col">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-1">Harga</span>
                <span class="text-base font-black text-gray-900 tracking-tighter">
                    Rp {{ number_format($p->harga, 0, ',', '.') }}
                </span>
            </div>

            <div class="w-10 h-10 bg-orange-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-orange-200 group-hover:bg-black group-hover:shadow-none transition-all duration-300 relative z-20">
                <i class="bi bi-bag-plus-fill text-lg"></i>
            </div>
        </div>
    </div>

    <div class="absolute inset-0 border-2 border-orange-500 rounded-[2rem] opacity-0 group-hover:opacity-10 scale-95 group-hover:scale-100 transition-all duration-500 pointer-events-none"></div>
</div>