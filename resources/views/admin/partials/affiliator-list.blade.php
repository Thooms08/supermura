@forelse($affiliators as $aff)
<tr class="hover:bg-gray-50/50 transition-colors">
    <td class="px-6 py-4">
        <span class="px-2 py-1 bg-orange-100 text-orange-600 rounded-lg font-mono font-bold text-xs border border-orange-200">
            {{ $aff->id_unik ?? 'N/A' }}
        </span>
    </td>
    <td class="px-6 py-4">
        <p class="font-bold text-gray-800">{{ $aff->nama }}</p>
        <p class="text-[10px] text-gray-400 font-medium">{{ $aff->created_at->format('d M Y, H:i') }}</p>
    </td>
    <td class="px-6 py-4">
        <div class="flex items-center gap-2 mb-1">
            <i class="bi bi-whatsapp text-green-500"></i>
            <span class="text-sm font-semibold text-gray-700">{{ $aff->no_whatsapp }}</span>
        </div>
        
        <div class="flex items-center gap-2">
            <i class="bi bi-geo-alt text-gray-400"></i>
            <span class="text-xs text-gray-500">{{ $aff->domisili }}</span>
        </div>
    </td>
    <td class="px-6 py-4">
        <span class="text-sm font-medium text-gray-600">{{ $aff->user->email ?? '-' }}</span>
    </td>
    <td class="px-6 py-4">
        @if($aff->metode_pembayaran == 'transfer')
            <span class="text-[10px] font-bold text-blue-600 uppercase block mb-1">Transfer</span>
            <a href="{{ asset('storage/'.$aff->bukti_transfer) }}" target="_blank" class="text-[9px] px-2 py-1 bg-blue-50 text-blue-500 border border-blue-100 rounded-md hover:bg-blue-100 transition-all italic">Lihat Bukti</a>
        @elseif($aff->metode_pembayaran == 'tunai')
            <span class="text-[10px] font-bold text-orange-600 uppercase block mb-1">Tunai</span>
            <span class="text-xs font-bold text-gray-700">Rp {{ number_format($aff->nominal_tunai, 0, ',', '.') }}</span>
        @else
            <span class="text-[10px] font-bold text-green-600 uppercase">Gratis</span>
        @endif
    </td>
    <td class="px-6 py-4">
        <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter {{ $aff->status == 'aktif' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
            {{ $aff->status }}
        </span>
    </td>
    <td class="px-6 py-4 text-center">
        <form action="{{ route('admin.affiliator.status', $aff->id) }}" method="POST">
            @csrf
            <button type="submit" class="p-2 rounded-lg {{ $aff->status == 'aktif' ? 'text-red-400 hover:bg-red-50 hover:text-red-600' : 'text-green-400 hover:bg-green-50 hover:text-green-600' }} transition-all">
                <i class="bi {{ $aff->status == 'aktif' ? 'bi-person-x-fill' : 'bi-person-check-fill' }} text-lg"></i>
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="px-6 py-12 text-center text-gray-400 italic">Data tidak ditemukan.</td>
</tr>
@endforelse