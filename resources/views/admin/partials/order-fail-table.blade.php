@forelse($orders as $order)
<tr class="hover:bg-red-50/20 transition-colors">
    <td class="p-6">
        <div class="font-black text-gray-800 text-sm mb-1 uppercase tracking-tighter">#{{ $order->nomor_pesanan }}</div>
        <div class="text-[10px] text-gray-400 italic">
            <i class="bi bi-calendar-x"></i> {{ $order->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }}
        </div>
    </td>
    <td class="p-6">
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex items-center gap-3">
                @if($item->produk && $item->produk->fotos->first())
                    <img src="{{ asset('storage/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-100">
                @endif
                <div class="flex flex-col">
                    <span class="text-xs font-bold text-gray-700 leading-tight">{{ $item->nama_produk }}</span>
                    <span class="text-[10px] text-gray-400">Qty: {{ $item->qty }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </td>
    <td class="p-6 text-center">
        <div class="inline-flex items-center px-3 py-1 bg-gray-100 rounded-full text-[10px] font-bold text-gray-500 uppercase tracking-tighter">
            <i class="bi bi-truck mr-1"></i> {{ $order->shippingMethod->nama_metode ?? $order->metode_pengiriman }}
        </div>
    </td>
    <td class="p-6 text-right">
        <div class="text-base font-black text-gray-800">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
        <span class="text-[9px] font-black text-red-500 uppercase tracking-widest">Status: {{ $order->status }}</span>
    </td>
    <td class="p-6 text-right" x-data="{ openDetail: false }">
        <button @click="openDetail = true" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl font-bold text-xs hover:bg-orange-500 hover:text-white transition-all shadow-sm">
            Detail
        </button>

        <div x-show="openDetail" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
            <div @click.away="openDetail = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl text-left">
                <div class="bg-orange-600 p-8 text-white relative">
                    <h3 class="text-xl font-black uppercase tracking-tighter">Detail Pengunjung</h3>
                </div>
                <div class="p-8 space-y-5">
                    <div>
                        <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">Nama Lengkap</label>
                        <p class="font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-[9px] uppercase font-black text-gray-400 block tracking-widest mb-1">WhatsApp</label>
                        <p class="text-sm font-bold text-gray-800">{{ $order->pengunjung->no_whatsapp ?? '-' }}</p>
                    </div>
                    <div class="border-t pt-4 text-xs">
                        <p><strong>Alamat:</strong> {{ $order->pengunjung->alamat_lengkap ?? '-' }}</p>
                    </div>
                </div>
                <div class="p-6 bg-gray-50 text-right">
                    <button @click="openDetail = false" class="px-8 py-2.5 bg-gray-900 text-white font-black rounded-xl uppercase text-[10px] tracking-widest">Tutup</button>
                </div>
            </div>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="p-24 text-center">
        <div class="bg-gray-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-300 text-5xl"><i class="bi bi-clipboard-x"></i></div>
        <p class="text-gray-400 font-bold uppercase tracking-[0.3em] text-xs">Data Tidak Ditemukan</p>
    </td>
</tr>
@endforelse

@if($orders->hasPages())
<tr class="bg-gray-50">
    <td colspan="5" class="p-4">
        {{ $orders->appends(request()->query())->links() }}
    </td>
</tr>
@endif