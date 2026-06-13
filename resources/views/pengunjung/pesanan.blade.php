<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya | SUPERMURA.ID</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .swal2-confirm { background-color: #f97316 !important; border-radius: 0.75rem !important; }
        .swal2-cancel  { border-radius: 0.75rem !important; }
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-4xl mx-auto px-4 py-8 md:py-12">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">
                Pesanan <span class="text-orange-500">Saya</span>
            </h1>
            <a href="{{ route('home') }}" class="text-sm font-bold text-orange-500 hover:text-orange-600 transition-colors">
                <i class="bi bi-arrow-left"></i> Belanja Lagi
            </a>
        </div>

        {{-- Tab Filter --}}
        <div class="mb-8 overflow-x-auto no-scrollbar">
            <div class="flex border-b border-gray-200 min-w-max">
                @php
                    $currentStatus = request('status');
                    $tabs = [
                        ['label' => 'Semua',              'status' => null],
                        ['label' => 'Belum Bayar',        'status' => 'pending'],
                        ['label' => 'Diproses',           'status' => 'process'],
                        ['label' => 'Dikirim',            'status' => 'send'],
                        ['label' => 'Selesai',            'status' => 'success'],
                        ['label' => 'Gagal/Expired',      'status' => 'fail'],
                        ['label' => 'Batal Sebelum Bayar','status' => 'cancelled_before_payment'],
                        ['label' => 'Proses Batal',       'status' => 'refund_pending'],
                        ['label' => 'Batal Disetujui',    'status' => 'canceled'],
                        ['label' => 'Batal Ditolak',      'status' => 'refund_fail'],
                    ];
                @endphp
                @foreach($tabs as $tab)
                    <a href="{{ route('pengunjung.pesanan', ['status' => $tab['status']]) }}"
                       class="px-5 py-4 text-[10px] font-black uppercase tracking-widest transition-all border-b-2
                              {{ $currentStatus == $tab['status'] ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-400 hover:text-gray-600' }}">
                        {{ $tab['label'] }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 3000, showConfirmButton: false });
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", timer: 3000, showConfirmButton: false });
                });
            </script>
        @endif

        {{-- List Pesanan --}}
        <div class="space-y-6">
            @forelse($orders as $order)
                @php
                    $refundStatus    = $order->refund->status ?? null;
                    $isRefundPending = $refundStatus === 'pending';
                    $isRefundSuccess = $refundStatus === 'success';
                    $isRefundFail    = $refundStatus === 'fail';
                    $isDisabled      = $isRefundPending || $isRefundSuccess || $order->cancelled_before_payment;

                    // Apakah pesanan masih bisa dibayar:
                    $isPending       = $order->status === 'pending';
                    $isProcess       = $order->status === 'process';
                    $hasPaymentLink  = !empty($order->snap_token);
                    $canPayNow       = $isPending && $hasPaymentLink;

                    // Pembatalan langsung (pending = belum bayar, tidak perlu form refund)
                    $canCancelDirect = $isPending && !$isRefundPending && !$isRefundSuccess;

                    // Pembatalan dengan form refund (process = sudah bayar, butuh rekening)
                    $canCancelRefund = $isProcess && !$isRefundPending && !$isRefundSuccess;

                    // Status label — cek cancelled_before_payment lebih dulu
                    if ($order->cancelled_before_payment) {
                        $statusConfig = ['text' => 'Dibatalkan (Sebelum Bayar)', 'color' => 'bg-gray-100 text-gray-500 border-gray-200'];
                    } else {
                        $statusConfig = match($order->status) {
                            'pending'  => ['text' => 'Belum Dibayar',   'color' => 'bg-orange-50 text-orange-600 border-orange-200'],
                            'process'  => ['text' => 'Sedang Diproses', 'color' => 'bg-blue-50 text-blue-600 border-blue-100'],
                            'send'     => ['text' => 'Dalam Pengiriman','color' => 'bg-indigo-50 text-indigo-600 border-indigo-100'],
                            'success'  => ['text' => 'Selesai',         'color' => 'bg-green-50 text-green-600 border-green-100'],
                            'fail'     => ['text' => 'Gagal / Expired', 'color' => 'bg-red-50 text-red-500 border-red-100'],
                            'canceled' => ['text' => 'Dibatalkan',      'color' => 'bg-gray-100 text-gray-500 border-gray-200'],
                            default    => ['text' => ucfirst($order->status), 'color' => 'bg-gray-50 text-gray-500 border-gray-200'],
                        };
                    }

                    $namaMetode = $order->shippingMethod->nama_metode ?? 'Standar';
                @endphp

                <div class="rounded-[2rem] border overflow-hidden transition-all shadow-sm
                            {{ $isDisabled ? 'bg-gray-50 opacity-80' : 'bg-white hover:shadow-md border-gray-100' }}">

                    {{-- Header Kartu --}}
                    <div class="px-6 py-4 border-b border-gray-50 flex flex-wrap justify-between items-center gap-3
                                {{ $isDisabled ? 'bg-gray-100/60' : 'bg-gray-50/60' }}">
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-white px-3 py-1 rounded-full border">
                                #{{ $order->nomor_pesanan }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-semibold">
                                <i class="bi bi-clock mr-1"></i>{{ $order->created_at->format('d M Y, H:i') }}
                            </span>
                            @if($isRefundPending)
                                <span class="text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-200 px-2 py-1 rounded-full animate-pulse uppercase tracking-tighter">
                                    Menunggu Konfirmasi Batal
                                </span>
                            @endif
                        </div>
                        <span class="px-4 py-1.5 rounded-full border text-[10px] font-black uppercase tracking-widest {{ $statusConfig['color'] }}">
                            {{ $statusConfig['text'] }}
                        </span>
                    </div>

                    {{-- Banner Belum Bayar --}}
                    @if($canPayNow)
                    <div class="px-6 py-3 bg-orange-50 border-b border-orange-100 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <i class="bi bi-exclamation-circle-fill text-orange-500"></i>
                            <span class="text-xs font-bold text-orange-700">
                                Pesanan ini menunggu pembayaran. Segera selesaikan agar pesanan diproses.
                            </span>
                        </div>
                        <a href="{{ $order->snap_token }}" target="_blank"
                           class="shrink-0 px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-orange-200 transition-all flex items-center gap-1.5">
                            <i class="bi bi-credit-card-fill"></i> Bayar Sekarang
                        </a>
                    </div>
                    @endif

                    {{-- Banner Dalam Pengiriman (status send) --}}
                    @if($order->status === 'send')
                    <div class="px-6 py-4 bg-indigo-50 border-b border-indigo-100">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="bi bi-send-fill text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-indigo-700 uppercase tracking-widest mb-1">
                                        Paket Sedang Dalam Pengiriman
                                    </p>
                                    <div class="space-y-0.5">
                                        <p class="text-xs text-indigo-600 font-semibold">
                                            <i class="bi bi-truck mr-1"></i>
                                            Kurir: <strong>{{ $namaMetode }}</strong>
                                        </p>
                                        <p class="text-xs text-indigo-600 font-semibold">
                                            <i class="bi bi-upc-scan mr-1"></i>
                                            No. Resi:
                                            <strong class="font-mono tracking-wider">
                                                {{ $order->no_resi ?? 'Belum tersedia' }}
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if($order->no_resi)
                            <a href="https://www.17track.net/en?nums={{ $order->no_resi }}"
                               target="_blank" rel="noopener noreferrer"
                               class="shrink-0 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-200 transition-all flex items-center gap-1.5">
                                <i class="bi bi-geo-alt-fill"></i> Lacak Paket
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Isi Item --}}
                    <div class="p-6 space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex gap-4 items-start">
                                <div class="w-16 h-16 rounded-2xl overflow-hidden bg-gray-50 border shrink-0">
                                    @if($item->produk && $item->produk->fotos->isNotEmpty())
                                        <img src="{{ asset('storage/produk/'.$item->produk->fotos->first()->path_foto) }}"
                                             class="w-full h-full object-cover"
                                             alt="{{ $item->nama_produk }}">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-200">
                                            <i class="bi bi-image text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-gray-800 text-sm line-clamp-1">{{ $item->nama_produk }}</h4>
                                    {{-- Tampilkan varian jika ada --}}
                                    @if($item->variant)
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">
                                            {{ $item->variant->size }} - {{ $item->variant->model }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $item->qty }} × Rp {{ number_format($item->harga, 0, ',', '.') }}
                                        <span class="font-black text-gray-700 ml-2">= Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        {{-- Info Resi (hanya jika sudah selesai) --}}
                        @if($order->status === 'success')
                            <div class="mt-2 p-4 bg-green-50 rounded-2xl border border-green-100">
                                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest mb-1">
                                    <i class="bi bi-box-seam mr-1"></i> Nomor Resi
                                </p>
                                <p class="text-sm font-bold text-gray-800">
                                    {{ $order->no_resi ?? 'Resi sedang disiapkan oleh penjual' }}
                                </p>
                                <p class="text-[11px] text-green-600 mt-1 italic">
                                    Lacak paket via situs resmi <strong>{{ $namaMetode }}</strong>.
                                </p>
                            </div>
                        @endif

                        {{-- Info Refund (jika ada) --}}
                        @if($order->refund)
                            @php
                                $refundConfig = match($order->refund->status) {
                                    'pending' => ['bg' => 'bg-amber-50 border-amber-200', 'text' => 'text-amber-700', 'label' => 'Menunggu Verifikasi Admin'],
                                    'success' => ['bg' => 'bg-green-50 border-green-200', 'text' => 'text-green-700', 'label' => 'Pembatalan Disetujui'],
                                    'fail'    => ['bg' => 'bg-red-50 border-red-200',   'text' => 'text-red-700',   'label' => 'Pembatalan Ditolak'],
                                    default   => ['bg' => 'bg-gray-50 border-gray-200',  'text' => 'text-gray-700',  'label' => 'Refund'],
                                };
                            @endphp
                            <div class="mt-2 p-4 {{ $refundConfig['bg'] }} border rounded-2xl">
                                <p class="text-[10px] font-black {{ $refundConfig['text'] }} uppercase tracking-widest mb-1">
                                    {{ $refundConfig['label'] }}
                                </p>
                                <p class="text-xs text-gray-600 italic">"{{ $order->refund->alasan }}"</p>
                                @if($order->refund->nomor_pengembalian)
                                    <p class="text-[11px] text-gray-500 mt-1">Rekening: {{ $order->refund->nomor_pengembalian }}</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Footer Kartu --}}
                    <div class="px-6 py-5 border-t border-gray-50 {{ $isDisabled ? 'bg-gray-100/30' : 'bg-gray-50/30' }}">
                        <div class="flex flex-wrap justify-between items-center gap-4">
                            {{-- Kiri: metode kirim + total --}}
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 text-xs font-bold text-gray-600">
                                    <i class="bi bi-truck text-orange-500"></i> {{ $namaMetode }}
                                </div>
                                <div>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Total Bayar: </span>
                                    <span class="text-base font-black text-orange-600">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Kanan: tombol aksi --}}
                            <div class="flex items-center gap-2 flex-wrap">
                                {{-- Tombol detail alamat --}}
                                <button x-data x-on:click="$dispatch('open-address-{{ $order->id }}')"
                                        class="p-2.5 bg-white border border-gray-200 text-gray-500 rounded-xl hover:bg-orange-50 hover:text-orange-600 shadow-sm transition-colors"
                                        title="Lihat Alamat">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </button>

                                {{-- Tombol lanjutkan pembayaran (pending & ada link) --}}
                                @if($canPayNow)
                                    <a href="{{ $order->snap_token }}" target="_blank"
                                       class="px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-orange-200 transition-all flex items-center gap-1.5">
                                        <i class="bi bi-credit-card-fill"></i> Lanjutkan Pembayaran
                                    </a>
                                @endif

                                {{-- Tombol Lacak Paket (khusus status send) --}}
                                @if($order->status === 'send' && $order->no_resi)
                                    <a href="https://www.17track.net/en?nums={{ $order->no_resi }}"
                                       target="_blank" rel="noopener noreferrer"
                                       class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-indigo-200 transition-all flex items-center gap-1.5">
                                        <i class="bi bi-geo-alt-fill"></i> Lacak Paket
                                    </a>
                                @endif

                                {{-- Tombol Paket Sudah Diterima (aktif setelah 3 hari sejak dikirim) --}}
                                @if($order->status === 'send')
                                    @php
                                        $canConfirm = $order->sent_at && $order->sent_at->diffInDays(now()) >= 3;
                                        $sisaHari   = $order->sent_at ? max(0, 3 - (int) $order->sent_at->diffInDays(now())) : 3;
                                    @endphp
                                    @if($canConfirm)
                                        <button type="button"
                                                onclick="confirmTerima('{{ $order->id }}')"
                                                class="px-5 py-2.5 bg-green-500 hover:bg-green-600 text-white font-black rounded-xl text-[10px] uppercase tracking-widest shadow-lg shadow-green-200 transition-all flex items-center gap-1.5">
                                            <i class="bi bi-check-circle-fill"></i> Paket Sudah Diterima
                                        </button>
                                        <form id="form-terima-{{ $order->id }}"
                                              action="{{ route('pengunjung.pesanan.konfirmasi', $order->id) }}"
                                              method="POST" class="hidden">
                                            @csrf
                                        </form>
                                    @else
                                        <button type="button" disabled
                                                title="Tombol aktif setelah {{ $sisaHari }} hari lagi"
                                                class="px-5 py-2.5 bg-gray-200 text-gray-400 font-black rounded-xl text-[10px] uppercase tracking-widest cursor-not-allowed flex items-center gap-1.5">
                                            <i class="bi bi-check-circle"></i> Paket Sudah Diterima
                                            <span class="ml-1 text-[9px] font-bold normal-case tracking-normal">({{ $sisaHari }}h lagi)</span>
                                        </button>
                                    @endif
                                @endif

                                {{-- Batalkan langsung: BELUM BAYAR (pending) — tanpa form refund --}}
                                @if($canCancelDirect)
                                    <button type="button"
                                            onclick="confirmBatalLangsung('{{ $order->id }}')"
                                            class="px-5 py-2.5 bg-white border-2 border-red-400 text-red-500 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-x-circle mr-1"></i> Batalkan
                                    </button>
                                    <form id="form-batal-langsung-{{ $order->id }}"
                                          action="{{ route('pengunjung.pesanan.batalkan', $order->id) }}"
                                          method="POST" class="hidden">
                                        @csrf
                                        <input type="hidden" name="alasan" value="Dibatalkan oleh pembeli (belum dibayar)">
                                    </form>
                                @endif

                                {{-- Ajukan pembatalan + refund: SUDAH BAYAR (process) — buka modal form --}}
                                @if($canCancelRefund)
                                    <button x-data x-on:click="$dispatch('open-cancel-{{ $order->id }}')"
                                            class="px-5 py-2.5 bg-white border-2 border-red-400 text-red-500 font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                        <i class="bi bi-arrow-return-left mr-1"></i> Ajukan Pembatalan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Detail Alamat --}}
                <div x-data="{ open: false }" @open-address-{{ $order->id }}.window="open = true"
                     x-show="open" x-cloak x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div @click.away="open = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
                        <div class="bg-orange-500 p-6 text-white flex justify-between items-center">
                            <h3 class="text-lg font-black uppercase tracking-tighter">Detail Pengiriman</h3>
                            <button @click="open = false" class="text-white/80 hover:text-white text-2xl"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="p-8 space-y-5">
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
                                    {{ $order->pengunjung->kota_kabupaten ?? '' }}, {{ $order->pengunjung->provinsi ?? '' }}
                                    {{ $order->pengunjung->kode_pos ? '- ' . $order->pengunjung->kode_pos : '' }}
                                </p>
                            </div>
                            <hr class="border-gray-100">
                            <div>
                                <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-1">Metode Pengiriman</label>
                                <p class="text-sm font-bold text-gray-800">{{ $namaMetode }}</p>
                            </div>
                        </div>
                        <div class="p-6 bg-gray-50">
                            <button @click="open = false" class="w-full py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-xl uppercase text-xs">Tutup</button>
                        </div>
                    </div>
                </div>

                {{-- Modal Form Pembatalan (HANYA untuk pesanan PROCESS = sudah bayar) --}}
                @if($canCancelRefund)
                <div x-data="{ open: false }" @open-cancel-{{ $order->id }}.window="open = true"
                     x-show="open" x-cloak x-transition
                     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div @click.away="open = false" class="bg-white rounded-[2.5rem] w-full max-w-lg overflow-hidden shadow-2xl">
                        <form id="form-batal-{{ $order->id }}"
                              action="{{ route('pengunjung.pesanan.batalkan', $order->id) }}"
                              method="POST">
                            @csrf
                            <div class="bg-red-500 p-8 text-white">
                                <h3 class="text-xl font-black uppercase tracking-tighter">Ajukan Pembatalan</h3>
                                <p class="text-xs opacity-90 mt-1">Lengkapi data untuk memproses pengembalian dana.</p>
                            </div>
                            <div class="p-8 space-y-5">
                                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl flex gap-3 items-start">
                                    <i class="bi bi-info-circle-fill text-amber-500 shrink-0 mt-0.5"></i>
                                    <p class="text-[11px] text-amber-700 leading-relaxed font-semibold">
                                        Proses pengembalian dana membutuhkan waktu 1–7 hari kerja setelah admin menyetujui. Pastikan nomor rekening yang kamu masukkan sudah benar.
                                    </p>
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2">
                                        Rekening / E-Wallet Tujuan Dana Kembali
                                    </label>
                                    <input type="text" name="nomor_pengembalian" required
                                           placeholder="Contoh: BCA - 123456789 a/n Nama Lengkap"
                                           class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm font-bold focus:ring-2 focus:ring-red-400">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-black text-gray-400 tracking-widest block mb-2">
                                        Alasan Pembatalan <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="alasan" required rows="3" minlength="10"
                                              placeholder="Jelaskan alasan pembatalan pesanan ini..."
                                              class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none text-sm font-bold focus:ring-2 focus:ring-red-400"></textarea>
                                </div>
                            </div>
                            <div class="p-6 bg-gray-50 flex gap-3">
                                <button type="button" @click="open = false"
                                        class="flex-1 py-3 bg-white border border-gray-200 text-gray-500 font-bold rounded-xl uppercase text-xs">
                                    Kembali
                                </button>
                                <button type="button" onclick="confirmBatal('{{ $order->id }}')"
                                        class="flex-1 py-3 bg-red-500 text-white font-black rounded-xl uppercase text-xs shadow-lg shadow-red-200 hover:bg-red-600 transition-colors">
                                    Kirim Pengajuan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                @endif

            @empty
                <div class="p-20 bg-white rounded-[2.5rem] text-center border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="bi bi-bag-x text-orange-300 text-3xl"></i>
                    </div>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-sm">
                        @if(request('status'))
                            Tidak ada pesanan dengan status ini.
                        @else
                            Belum ada pesanan. Yuk, mulai belanja!
                        @endif
                    </p>
                    <a href="{{ route('home') }}" class="inline-block mt-6 px-8 py-3 bg-orange-500 text-white font-black rounded-2xl text-xs uppercase tracking-widest hover:bg-orange-600 transition-all shadow-lg shadow-orange-200">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <script>
        // Konfirmasi pembatalan LANGSUNG untuk pesanan belum bayar (pending)
        function confirmBatalLangsung(id) {
            Swal.fire({
                title: 'Batalkan Pesanan?',
                text: 'Pesanan ini belum dibayar. Pesanan akan langsung dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-batal-langsung-' + id).submit();
                }
            });
        }

        // Konfirmasi penerimaan paket oleh pengunjung
        function confirmTerima(id) {
            Swal.fire({
                title: 'Paket Sudah Diterima?',
                text: 'Pastikan paket sudah benar-benar kamu terima. Pesanan akan ditandai Selesai.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Sudah Diterima',
                cancelButtonText: 'Belum',
                reverseButtons: true,
                confirmButtonColor: '#22c55e',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-terima-' + id).submit();
                }
            });
        }

        // Konfirmasi pengajuan refund untuk pesanan sudah bayar (process)
        function confirmBatal(id) {
            Swal.fire({
                title: 'Kirim Pengajuan Pembatalan?',
                text: 'Permintaan pembatalan akan dikirim ke Admin untuk diverifikasi sebelum dana dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim',
                cancelButtonText: 'Tidak',
                reverseButtons: true,
                confirmButtonColor: '#ef4444',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-batal-' + id).submit();
                }
            });
        }
    </script>
</body>
</html>
