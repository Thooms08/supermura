{{-- Digunakan oleh AJAX search — loop collection $produks --}}
@forelse($produks as $p)
    @include('partials.product-grid', ['p' => $p, 'komisiRekrut' => $komisiRekrut ?? 0])
@empty
    <div class="col-span-full py-20 text-center">
        <div class="w-24 h-24 bg-gray-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-6">
            <i class="bi bi-box2 text-5xl text-gray-200"></i>
        </div>
        <h4 class="text-lg font-black text-gray-400 uppercase tracking-tighter">Produk Tidak Ditemukan</h4>
    </div>
@endforelse
