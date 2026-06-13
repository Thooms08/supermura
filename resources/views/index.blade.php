@extends('layouts.app')

@section('title', 'SUPERMURA.ID | Fashion UMKM')

@section('content')
    {{-- Include Favicon di Head via layouts atau di sini --}}

    <header class="bg-white py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[3rem] p-8 md:p-20 text-white relative overflow-hidden shadow-2xl shadow-orange-200">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>

                <div class="relative z-10 max-w-2xl">
                    <h1 class="text-4xl md:text-7xl font-black leading-tight mb-6 uppercase tracking-tighter">
                        SUPERMURA<span class="text-orange-200">.ID</span>
                    </h1>
                    <p class="text-orange-50 text-lg md:text-xl mb-10 font-medium opacity-90 leading-relaxed">
                        Temukan koleksi fashion terbaik dan kuliner khas nusantara dengan kualitas premium dan harga jujur.
                    </p>
                </div>
                <i class="bi bi-bag-heart absolute -right-10 -bottom-10 text-[18rem] text-white/10 rotate-12 hidden lg:block"></i>
            </div>

            <div class="max-w-3xl mx-auto -mt-10 relative z-20 px-4">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-5">
                        <i class="bi bi-search text-gray-400 group-focus-within:text-orange-500 transition-colors text-lg"></i>
                    </span>
                    <input type="text" id="searchInput"
                           placeholder="Cari nama produk, kategori, atau model..."
                           class="w-full pl-14 pr-14 py-6 bg-white border-none shadow-2xl rounded-[2rem] text-sm font-medium focus:ring-4 focus:ring-orange-500/10 outline-none transition-all text-gray-700 placeholder:text-gray-300">

                    <div id="searchLoader" class="hidden absolute inset-y-0 right-6 flex items-center">
                        <div class="animate-spin h-5 w-5 border-2 border-orange-500 border-t-transparent rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main id="katalog" class="max-w-7xl mx-auto px-4 py-12 md:py-20">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tighter uppercase">Katalog Produk</h2>
                <div class="h-1.5 w-20 bg-orange-500 rounded-full mt-4"></div>
            </div>

            <div class="flex gap-2">
                <span class="px-4 py-2 bg-orange-50 text-orange-600 rounded-xl text-[10px] font-black uppercase tracking-widest border border-orange-100">
                    {{ $produks->count() }} Produk Tersedia
                </span>
            </div>
        </div>

        <div id="productGrid" class="flex flex-wrap gap-4 md:gap-8">
            @forelse($produks as $p)
               <div class="w-[calc(50%-0.5rem)] md:w-[calc(33.333%-1.334rem)] lg:w-[calc(25%-1.5rem)] flex">
                    @include('partials.product-grid', ['p' => $p])
                </div>
            @empty
                <div class="w-full py-20 text-center">
                    <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-box2 text-5xl text-gray-200"></i>
                    </div>
                    <h4 class="text-lg font-black text-gray-400 uppercase tracking-tighter">Stok Sedang Kosong</h4>
                    <p class="text-gray-300 text-sm font-medium mt-2">Nantikan update produk menarik lainnya segera!</p>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-12 text-center">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em]">
            &copy; 2024 SUPERMURA.ID - Seluruh Hak Cipta Dilindungi
        </p>
    </footer>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const productGrid = document.getElementById('productGrid');
        const loader = document.getElementById('searchLoader');
        let timeout = null;

        searchInput.addEventListener('input', function() {
            const query = this.value;
            loader.classList.remove('hidden');

            clearTimeout(timeout);
            timeout = setTimeout(function() {
                fetch(`{{ route('product.search') }}?query=${encodeURIComponent(query)}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => response.text())
                .then(html => {
                    productGrid.innerHTML = html;
                    loader.classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error Search:', error);
                    loader.classList.add('hidden');
                });
            }, 400); // Debounce 400ms
        });
    });
</script>
@endpush