@forelse($refunds as $refund)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4 bg-gray-50/30">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white">
                    <i class="bi bi-check-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800">{{ $refund->pesanan->pengunjung->nama_lengkap ?? 'User' }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">#{{ $refund->pesanan->nomor_pesanan }}</p>
                </div>
            </div>
            <div class="px-4 py-1.5 rounded-full bg-gray-100 text-gray-500 border border-gray-200 text-[10px] font-black uppercase tracking-widest">
                Pesanan Dibatalkan
            </div>
        </div>

        <div class="p-6 grid md:grid-cols-2 gap-8">
            <div class="space-y-4">
                @foreach($refund->pesanan->items as $item)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-gray-50 border flex-shrink-0">
                            @if($item->produk && $item->produk->fotos->first())
                                <img src="{{ asset('storage/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="bi bi-image"></i></div>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $item->nama_produk }}</p>
                            <p class="text-xs text-gray-400">{{ $item->qty }} unit x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-gray-50 p-4 rounded-2xl border border-dashed">
                <p class="text-[10px] text-gray-400 font-bold uppercase mb-1">Alasan Refund</p>
                <p class="text-xs text-gray-600 italic">"{{ $refund->alasan }}"</p>
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Dikembalikan</p>
                    <p class="text-lg font-black text-orange-600">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="p-20 text-center bg-white rounded-3xl border-2 border-dashed">
        <p class="text-gray-400 font-bold">Data refund tidak ditemukan.</p>
    </div>
@endforelse