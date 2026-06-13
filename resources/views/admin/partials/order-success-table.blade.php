@forelse($orders as $order)
<tr class="hover:bg-orange-50/30 transition-colors border-b">
    <td class="p-6">
        <div class="font-black text-gray-800 text-sm mb-1 uppercase tracking-tighter">#{{ $order->nomor_pesanan }}</div>
        <div class="text-[10px] text-gray-400 flex items-center gap-1">
            <i class="bi bi-calendar-check"></i> {{ $order->updated_at->setTimezone('Asia/Jakarta')->format('d/m/Y H:i') }}
        </div>
    </td>
    <td class="p-6">
        <div x-data="{ openCust: false }">
            <button @click="openCust = true" class="text-left group">
                <div class="text-sm font-bold text-gray-700 group-hover:text-orange-600 transition-colors">{{ $order->pengunjung->nama_lengkap ?? 'Guest' }}</div>
                <div class="text-[10px] text-gray-400">Klik untuk detail alamat</div>
            </button>
            
            <div x-show="openCust" class="fixed inset-0 z-[70] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak>
                <div @click.away="openCust = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                    <div class="bg-gray-900 p-8 text-white relative">
                        <h3 class="text-xl font-black uppercase tracking-tighter">Profil Penerima</h3>
                        <p class="text-orange-500 text-[10px] font-bold uppercase tracking-widest">Informasi Lengkap</p>
                    </div>
                    <div class="p-8 space-y-4 text-sm text-gray-600">
                        <p><strong>WhatsApp:</strong> {{ $order->pengunjung->no_whatsapp ?? '-' }}</p>
                        <p><strong>Email:</strong> {{ $order->pengunjung->email ?? '-' }}</p>
                        <p><strong>Provinsi:</strong> {{ $order->pengunjung->provinsi ?? '-' }}</p>
                        <p><strong>Kota/Kab:</strong> {{ $order->pengunjung->kota_kabupaten ?? '-' }}</p>
                        <p><strong>Alamat:</strong> {{ $order->pengunjung->alamat_lengkap ?? '-' }}</p>
                    </div>
                    <div class="p-6 bg-gray-50 text-right">
                        <button @click="openCust = false" class="px-6 py-2 bg-orange-600 text-white font-black rounded-xl text-xs uppercase tracking-widest">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </td>
    <td class="p-6">
        <div class="space-y-3">
            @foreach($order->items as $item)
            <div class="flex items-center gap-3">
                @if($item->produk && $item->produk->fotos->first())
                    <img src="{{ asset('storage/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-10 h-10 rounded-lg object-cover border border-orange-100">
                @endif
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-gray-800 leading-tight">{{ $item->nama_produk }}</span>
                    <span class="text-[10px] text-gray-400">Qty: {{ $item->qty }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </td>
    <td class="p-6 text-center">
        <div class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ $order->shippingMethod->nama_metode ?? $order->metode_pengiriman }}</div>
        <div class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-bold border border-green-200">
            <i class="bi bi-qr-code-scan"></i> {{ $order->no_resi ?? 'RESIDALAMPROSES' }}
        </div>
    </td>
    <td class="p-6 text-right">
        <div class="text-base font-black text-orange-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>
        <span class="text-[10px] font-bold text-green-500 uppercase italic"><i class="bi bi-check-all"></i> Selesai</span>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="p-20 text-center">
        <div class="text-gray-300 mb-4 text-5xl"><i class="bi bi-search"></i></div>
        <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Data tidak ditemukan.</p>
    </td>
</tr>
@endforelse