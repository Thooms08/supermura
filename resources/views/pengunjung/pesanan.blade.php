<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | SUPERMURA.ID</title>
    @include('partials.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .swal2-confirm { background-color: #f97316 !important; border-radius: 0.75rem !important; }
        .swal2-cancel { border-radius: 0.75rem !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
                Pesanan <span class="text-orange-500">Saya</span>
            </h1>
            <a href="{{ route('home') }}" class="text-sm font-bold text-orange-500 hover:text-orange-600 transition-colors">
                <i class="bi bi-arrow-left"></i> Belanja Lagi
            </a>
        </div>

        <div class="mb-8 overflow-x-auto no-scrollbar">
            <div class="flex border-b border-gray-200 min-w-max">
                @php
                    $currentStatus = request('status');
                    $tabs = [
                        ['label' => 'Semua', 'status' => null],
                        ['label' => 'Baru', 'status' => 'pending'],
                        ['label' => 'Proses', 'status' => 'process'],
                        ['label' => 'Selesai', 'status' => 'success'],
                        ['label' => 'Batal Resmi', 'status' => 'canceled'],
                        ['label' => 'Proses Batal', 'status' => 'refund_pending'],
                        ['label' => 'Batal Ditolak', 'status' => 'refund_fail'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    <a href="{{ route('pengunjung.pesanan', ['status' => $tab['status']]) }}" 
                       class="px-6 py-4 text-xs font-black uppercase tracking-widest transition-all border-b-2 {{ $currentStatus == $tab['status'] ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        @if(session('success'))
            <script>
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
            </script>
        @endif

        <div class="space-y-6">
            @forelse($orders as $order)
                @php
                    $refundStatus = $order->refund->status ?? null;
                    $isRefundPending = $refundStatus === 'pending';
                    $isRefundSuccess = $refundStatus === 'success';
                    $isRefundFail = $refundStatus === 'fail';
                    $isDisabled = $isRefundPending || $isRefundSuccess || $isRefundFail;

                    $statusText = match($order->status) {
                        'pending'  => 'Menunggu',
                        'process'  => 'Diproses',
                        'success'  => 'Selesai',
                        'fail'     => 'Gagal',
                        'canceled' => 'Dibatalkan',
                        default    => 'Unknown'
                    };

                    $statusColor = match($order->status) {
                        'process'  => 'bg-blue-50 text-blue-600 border-blue-100',
                        'success'  => 'bg-green-50 text-green-600 border-green-100',
                        'fail', 'canceled' => 'bg-gray-200 text-gray-500 border-gray-300',
                        default    => 'bg-gray-50 text-gray-600'
                    };

                    // Nama Metode Pengiriman Aman (Fix error pemanggilan)
                    $namaMetode = $order->shippingMethod->nama_metode ?? 'Standar';
                @endphp

                <div class="rounded-[2rem] border border-gray-100 overflow-hidden transition-all shadow-sm 
                    {{ $isDisabled ? 'bg-gray-100 opacity-75 grayscale-[0.5]' : 'bg-white hover:shadow-md' }}">
                    
                    <div class="p-6 border-b border-gray-50 flex flex-wrap justify-between items-center gap-4 {{ $isDisabled ? 'bg-gray-200/50' : 'bg-gray-50/50' }}">
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-white px-3 py-1 rounded-full border">#{{ $order->nomor_pesanan }}</span>
                            @if($isRefundPending)
                                <span class="text-[9px] font-bold text-red-500 animate-pulse uppercase tracking-tighter">MENUNGGU KONFIRMASI BATAL</span>
                            @endif
                        </div>
                        <span class="px-4 py-1.5 rounded-full border text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <div class="p-6 space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 border">
                                    @if($item->produk && $item->produk->fotos->first())
                                        <img src="{{ asset('asset/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->nama_produk }}</h4>
                                    <p class="text-xs text-gray-400 mt-1 uppercase font-bold">{{ $item->qty }} Barang x Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach

                        @if($order->status === 'success')
                            <div class="mt-4 p-4 bg-green-50 rounded-2xl border border-green-100">
                                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest mb-1">Nomor Resi</p>
                                <p class="text-sm font-bold text-gray-800">{{ $order->no_resi ?? 'Resi sedang disiapkan' }}</p>
                                <p class="text-[11px] text-green-600 mt-1 italic">
                                    Untuk melacak paket, silakan akses situs resmi <strong>{{ $namaMetode }}</strong>.
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="px-6 py-6 border-t border-gray-50 {{ $isDisabled ? 'bg-gray-200/20' : 'bg-gray-50/30' }}">
                        <div class="flex flex-wrap justify-between items-end gap-4 mb-5">
                            <div class="space-y-2">
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-700">
                                    <i class="bi bi-truck text-orange-500"></i> {{ $namaMetode }}
                                </div>
                                @if($order->refund)
                                     <div class="bg-white/80 p-3 rounded-xl border border-gray-200">
                                         <p class="text-[9px] text-gray-400 uppercase font-bold">Status Refund: {{ strtoupper($order->refund->status) }}</p>
                                         <p class="text-xs text-gray-600 italic">"{{ $order->refund->alasan }}"</p>
                                     </div>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-400 font-bold uppercase tracking-widest">Total Bayar</p>
                                <p class="text-xl font-black text-orange-600 tracking-tighter">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button x-data x-on:click="$dispatch('open-address-{{ $order->id }}')" 
                                    class="p-2.5 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-orange-50 hover:text-orange-600 shadow-sm transition-colors">
                                <i class="bi bi-geo-alt-fill"></i>
                            </button>

                            @if(!$isDisabled && in_array($order->status, ['pending', 'process']))
                                <button x-data x-on:click="$dispatch('open-cancel-{{ $order->id }}')"
                                        class="flex-1 md:flex-none px-6 py-2.5 bg-white border-2 border-red-500 text-red-500 font-black rounded-xl text-xs uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                    Batalkan Pesanan
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @open-address-{{ $order->id }}.window="open = true" x-show="open" 
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
                    <div @click.away="open = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                        <div class="bg-orange-500 p-6 text-white flex justify-between items-center">
                            <h3 class="text-lg font-black uppercase tracking-tighter">Detail Pengiriman</h3>
                            <button @click="open = false" class="text-white/80 hover:text-white text-2xl"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="p-8 space-y-6">
                            <div>
                                <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-1">Penerima</label>
                                <p class="text-sm font-bold text-gray-800">{{ $order->pengunjung->nama_lengkap ?? '-' }}</p>
                                <p class="text-xs text-orange-600 font-semibold">{{ $order->pengunjung->no_whatsapp ?? '-' }}</p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-1">Alamat Lengkap</label>
                                <p class="text-sm text-gray-700 leading-relaxed">
                                    {{ $order->pengunjung->alamat_lengkap ?? 'Alamat tidak tersedia' }}<br>
                                    {{ $order->pengunjung->kota_kabupaten ?? '' }}, {{ $order->pengunjung->provinsi ?? '' }}<br>
                                    <span class="font-bold">{{ $order->pengunjung->kode_pos ?? '' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50">
                            <button @click="open = false" class="w-full py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-xl uppercase text-xs">Tutup</button>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" @open-cancel-{{ $order->id }}.window="open = true" x-show="open" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
                    <div @click.away="open = false" class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl">
                        <form id="form-batal-{{ $order->id }}" action="{{ route('pengunjung.pesanan.batalkan', $order->id) }}" method="POST">
                            @csrf
                            <div class="bg-orange-500 p-8 text-white">
                                <h3 class="text-xl font-black uppercase tracking-tighter">Form Pembatalan</h3>
                                <p class="text-xs opacity-90 mt-1">Lengkapi data untuk memproses pengembalian dana.</p>
                            </div>
                            <div class="p-8 space-y-5">
                                <div class="p-4 bg-orange-50 border border-orange-100 rounded-2xl flex gap-3">
                                    <i class="bi bi-info-circle-fill text-orange-500"></i>
                                    <p class="text-[11px] text-orange-700 leading-relaxed font-bold">
                                        Proses pengembalian dana membutuhkan waktu 1–7 hari kerja. Jika dalam 7 hari dana belum diterima, silakan hubungi admin Flavory.id.
                                    </p>
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2">Rekening / E-Wallet</label>
                                    <input type="text" name="nomor_pengembalian" required placeholder="Contoh: BCA - 1234xxx a/n Nama" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm font-bold focus:ring-2 focus:ring-orange-500">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2">Alasan</label>
                                    <textarea name="alasan" required rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm font-bold focus:ring-2 focus:ring-orange-500"></textarea>
                                </div>
                            </div>
                            <div class="p-6 bg-gray-50 flex gap-3">
                                <button type="button" @click="open = false" class="flex-1 py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-xl uppercase text-xs">Kembali</button>
                                <button type="button" onclick="confirmBatal('{{ $order->id }}')" class="flex-1 py-3 bg-orange-500 text-white font-black rounded-xl uppercase text-xs shadow-lg shadow-orange-200">Kirim Pengajuan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-20 bg-white rounded-[2.5rem] text-center border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">Tidak ada pesanan.</p>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        function confirmBatal(id) {
            Swal.fire({
                title: 'YAKIN INGIN DIBATALKAN?',
                text: "Permintaan pembatalan akan dikirimkan ke Admin untuk diverifikasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Saya Yakin',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-batal-' + id).submit();
                }
            })
        }
    </script>
</body>
</html>