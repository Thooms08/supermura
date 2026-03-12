@forelse($refunds as $refund)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-6 transition-all hover:border-red-100">
        <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4 bg-red-50/20">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-red-500 rounded-xl flex items-center justify-center text-white shadow-lg shadow-red-100">
                    <i class="bi bi-x-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-800 text-sm">{{ $refund->pesanan->pengunjung->nama_lengkap ?? 'User' }}</h3>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">ORDER #{{ $refund->pesanan->nomor_pesanan }}</p>
                </div>
            </div>
            <div class="px-4 py-1.5 rounded-full bg-red-100 text-red-600 border border-red-200 text-[10px] font-black uppercase tracking-widest">
                Pesanan Gagal Dibatalkan
            </div>
        </div>

        <div class="p-6 grid md:grid-cols-2 gap-8">
            <div class="space-y-4">
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Detail Produk</p>
                @foreach($refund->pesanan->items as $item)
                    <div class="flex items-center gap-4 bg-gray-50/50 p-3 rounded-2xl border border-gray-100">
                        <div class="w-14 h-14 rounded-xl overflow-hidden bg-white border flex-shrink-0">
                            @if($item->produk && $item->produk->fotos->first())
                                <img src="{{ asset('asset/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $item->nama_produk }}</p>
                            <p class="text-xs text-gray-400">{{ $item->qty }} unit x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            <p class="text-[10px] font-bold text-orange-600 mt-1">Subtotal: Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="bg-gray-50 p-5 rounded-3xl border border-dashed border-gray-200 flex flex-col justify-between">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase mb-2 tracking-widest">Alasan Pengajuan</p>
                    <p class="text-xs text-gray-600 italic leading-relaxed">"{{ $refund->alasan }}"</p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-200 flex justify-between items-end">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Dana Yang Tertahan</p>
                        <p class="text-xl font-black text-gray-800">Rp {{ number_format($refund->pesanan->total_harga, 0, ',', '.') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-red-400 font-bold uppercase italic">Permintaan Ditolak Admin</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="p-20 text-center bg-white rounded-[2.5rem] border-2 border-dashed border-gray-100">
        <i class="bi bi-shield-exclamation text-4xl text-gray-200 mb-4 block"></i>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Tidak ada riwayat refund gagal</p>
    </div>
@endforelse