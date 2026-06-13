@php
    $komisiProduk    = ($p->komisis && $p->komisis->isNotEmpty()) ? (float) $p->komisis->max('nominal_komisi') : 0;
    $komisiRekrutVal = (float) ($komisiRekrut ?? 0);
    $adaKomisi       = $komisiProduk > 0;
    $adaKomisiRekrut = $komisiRekrutVal > 0;
    $avgRating       = $p->averageRating();
    $totalTerjual    = $p->totalTerjual();
@endphp

<div class="h-full flex flex-col bg-white rounded-[2rem] border border-gray-100 overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 group relative">

    <a href="{{ route('product.show', $p->slug) }}" class="absolute inset-0 z-10" aria-label="Lihat Detail {{ $p->nama_produk }}"></a>

    {{-- Gambar Produk --}}
    <div class="relative aspect-[4/5] overflow-hidden bg-gray-50">
        @if($p->fotos && $p->fotos->isNotEmpty())
            <img src="{{ asset('storage/produk/' . $p->fotos->first()->path_foto) }}"
                 alt="{{ $p->nama_produk }}"
                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <i class="bi bi-image text-gray-200 text-4xl"></i>
            </div>
        @endif

        <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        {{-- Badge Kategori --}}
        <div class="absolute top-4 left-4 z-20">
            <span class="bg-white/90 backdrop-blur-md text-orange-600 text-[9px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-sm">
                {{ $p->kategori->nama_jenis ?? 'Produk' }}
            </span>
        </div>

        {{-- Badge Komisi --}}
        @if($adaKomisi)
            <div class="absolute top-4 right-4 z-20">
                <span class="bg-green-500 text-white text-[9px] font-black px-2.5 py-1.5 rounded-xl shadow-sm flex items-center gap-1 leading-none">
                    <i class="bi bi-tag-fill text-[8px]"></i> Komisi
                </span>
            </div>
        @endif
    </div>

    {{-- Konten --}}
    <div class="p-5 md:p-6 flex flex-col flex-1">

        {{-- Nama Produk --}}
        <div class="mb-3">
            <h3 class="text-sm md:text-base font-bold text-gray-800 group-hover:text-orange-500 transition-colors line-clamp-1">
                {{ $p->nama_produk }}
            </h3>
        </div>

        {{-- Rating & Terjual --}}
        @if($avgRating !== null || $totalTerjual > 0)
            <div class="flex items-center gap-2 mb-3">
                @if($avgRating !== null)
                    <span class="flex items-center gap-0.5 text-[10px] font-black text-yellow-500 leading-none">
                        ★ <span class="text-gray-600">{{ $avgRating }}/5</span>
                    </span>
                @endif
                @if($totalTerjual > 0)
                    <span class="text-[10px] font-bold text-gray-400 leading-none">{{ $totalTerjual }} terjual</span>
                @endif
            </div>
        @endif

        {{-- Harga & Tombol --}}
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

        {{-- Info Komisi --}}
        @if($adaKomisi || $adaKomisiRekrut)
            <div class="border-t border-gray-50 mt-3 pt-3 space-y-1.5 mt-auto">

                @if($adaKomisi)
                    <div class="flex items-center justify-between bg-orange-50 rounded-xl px-3 py-2">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-orange-500 rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-cash-coin text-white text-[9px]"></i>
                            </div>
                            <span class="text-[9px] font-black text-orange-700 uppercase tracking-wide leading-none">Komisi Affiliate</span>
                        </div>
                        <span class="text-[10px] font-black text-orange-600 leading-none">
                            Rp {{ number_format($komisiProduk, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

                @if($adaKomisiRekrut)
                    <div class="flex items-center justify-between bg-green-50 rounded-xl px-3 py-2">
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 bg-green-500 rounded-lg flex items-center justify-center shrink-0">
                                <i class="bi bi-people-fill text-white text-[9px]"></i>
                            </div>
                            <span class="text-[9px] font-black text-green-700 uppercase tracking-wide leading-none">Komisi Rekrut</span>
                        </div>
                        <span class="text-[10px] font-black text-green-600 leading-none">
                            Rp {{ number_format($komisiRekrutVal, 0, ',', '.') }}
                        </span>
                    </div>
                @endif

            </div>
        @endif

    </div>

    <div class="absolute inset-0 border-2 border-orange-500 rounded-[2rem] opacity-0 group-hover:opacity-10 scale-95 group-hover:scale-100 transition-all duration-500 pointer-events-none"></div>
</div>