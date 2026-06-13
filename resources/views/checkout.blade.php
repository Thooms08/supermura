<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('layouts.favicon')
    <title>Checkout | SUPERMURA.ID</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

    @php
        // Hitung total dari masing-masing source
        if ($source === 'cart') {
            $grandTotal = $totalHarga;
        } else {
            $grandTotal = $checkoutData['qty'] * $checkoutData['harga'];
        }
    @endphp

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-8">
            <a href="{{ $source === 'cart' ? route('keranjang.index') : route('home') }}"
               class="text-gray-500 hover:text-orange-600 transition-colors flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-black text-orange-600 uppercase tracking-tighter">Konfirmasi Pesanan</h1>
            <div class="w-10"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">

                {{-- Alamat Pengiriman --}}
                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg flex items-center gap-2">
                            <i class="bi bi-geo-alt text-orange-600"></i> Alamat Pengiriman
                        </h3>
                        <a href="{{ route('pengunjung.alamat.index') }}"
                           class="text-xs bg-orange-100 text-orange-600 px-4 py-2 rounded-full font-bold hover:bg-orange-600 hover:text-white transition-all">
                            <i class="bi bi-plus-lg"></i> Kelola Alamat
                        </a>
                    </div>
                    <div class="text-sm space-y-1 p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                        @if($profil)
                            <p class="font-bold text-gray-900">{{ $profil->nama_lengkap }}</p>
                            <p class="text-gray-600">{{ $profil->no_whatsapp }}</p>
                            <p class="text-gray-600">{{ $profil->alamat_lengkap }}</p>
                            <p class="text-gray-600">
                                {{ $profil->kota_kabupaten }}, {{ $profil->provinsi }} {{ $profil->kode_pos }}
                            </p>
                        @else
                            <p class="text-red-500 font-bold italic">
                                <i class="bi bi-exclamation-triangle mr-1"></i>
                                Alamat belum diset. Silakan tambah alamat terlebih dahulu.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- Daftar Produk --}}
                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="bi bi-bag text-orange-600"></i> Produk yang Dipesan
                    </h3>

                    @if($source === 'cart')
                        {{-- Multiple items dari keranjang --}}
                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                            @php
                                $h = $item->variant
                                    ? ($item->variant->harga_variant ?? $item->produk->harga)
                                    : $item->produk->harga;
                            @endphp
                            <div class="flex gap-4 items-center py-3 border-b border-gray-50 last:border-0">
                                <img src="{{ $item->produk->fotos->isNotEmpty() ? asset('storage/produk/'.$item->produk->fotos->first()->path_foto) : asset('asset/default-image.jpg') }}"
                                     class="w-16 h-16 object-cover rounded-2xl border shrink-0"
                                     alt="{{ $item->produk->nama_produk }}">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm line-clamp-1">{{ $item->produk->nama_produk }}</h4>
                                    @if($item->variant)
                                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">
                                            {{ $item->variant->size }} - {{ $item->variant->model }}
                                        </p>
                                    @endif
                                    <p class="text-xs text-gray-400 mt-0.5">Qty: {{ $item->qty }}</p>
                                </div>
                                <div class="text-orange-600 font-black text-sm shrink-0">
                                    Rp {{ number_format($h * $item->qty, 0, ',', '.') }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        {{-- Single item dari Buy Now --}}
                        <div class="flex gap-4 items-center">
                            <img src="{{ $produk->fotos->isNotEmpty() ? asset('storage/produk/'.$produk->fotos->first()->path_foto) : asset('asset/default-image.jpg') }}"
                                 class="w-20 h-20 object-cover rounded-2xl border"
                                 alt="{{ $produk->nama_produk }}">
                            <div class="flex-1">
                                <h4 class="font-bold">{{ $produk->nama_produk }}</h4>
                                <p class="text-xs text-gray-400 mt-1">Qty: {{ $checkoutData['qty'] }}</p>
                                <p class="text-orange-600 font-black mt-1">
                                    Rp {{ number_format($checkoutData['harga'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Metode Pengiriman --}}
                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                        <i class="bi bi-truck text-orange-600"></i> Metode Pengiriman
                    </h3>
                    @if($shippingMethods->isEmpty())
                        <p class="text-sm text-gray-400 italic">Belum ada metode pengiriman tersedia.</p>
                    @else
                        <select id="shipping_method_select"
                                class="w-full p-3 bg-gray-50 border rounded-2xl outline-none focus:ring-2 focus:ring-orange-500 font-medium">
                            @foreach($shippingMethods as $method)
                                <option value="{{ $method->id }}"
                                        data-name="{{ $method->nama_metode }}">
                                    {{ $method->nama_metode }}
                                </option>
                            @endforeach
                        </select>
                    @endif

                    {{-- Keterangan Pengiriman --}}
                    <div class="mt-4 p-4 bg-green-50 border border-green-100 rounded-2xl space-y-2">
                        <div class="flex items-start gap-2">
                            <i class="bi bi-truck text-green-600 mt-0.5"></i>
                            <p class="text-xs text-green-700 font-medium leading-relaxed">
                                Estimasi pengiriman <span class="font-black">3-5 hari kerja</span>, tergantung kebijakan pihak ekspedisi (bisa lebih cepat atau lebih lambat dari estimasi).
                            </p>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="bi bi-gift text-green-600 mt-0.5"></i>
                            <p class="text-xs text-green-700 font-medium leading-relaxed">
                                <span class="font-black">Gratis Ongkir</span> ke seluruh wilayah Indonesia.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Ringkasan & Tombol Bayar --}}
            <div class="space-y-6">
                <div class="bg-gray-900 text-white p-8 rounded-[2.5rem] shadow-xl sticky top-6">
                    <h3 class="text-xl font-black mb-6 uppercase tracking-widest text-orange-500">
                        Ringkasan Biaya
                    </h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between opacity-70">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between opacity-70">
                            <span>Biaya Layanan</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="border-white/10">
                        <div class="flex justify-between items-end">
                            <span class="font-bold">Total Tagihan</span>
                            <span class="text-2xl font-black text-orange-500">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    {{-- Checkbox Syarat & Ketentuan --}}
                    <div class="mt-6 flex items-start gap-2">
                        <input type="checkbox" id="agree-terms"
                               class="mt-1 w-4 h-4 accent-orange-500 cursor-pointer shrink-0">
                        <label for="agree-terms" class="text-xs text-gray-300 leading-relaxed cursor-pointer">
                            Saya telah membaca dan menyetujui
                            <a href="{{ route('ketentuan') }}" target="_blank"
                               class="text-orange-400 font-bold underline hover:text-orange-300">
                                Syarat dan Ketentuan
                            </a>
                            yang berlaku.
                        </label>
                    </div>

                    <button id="pay-button"
                            {{ !$profil ? 'disabled' : '' }}
                            class="w-full mt-4 py-4 font-black rounded-2xl transition-all uppercase tracking-widest text-xs shadow-lg
                                   {{ $profil ? 'bg-orange-600 hover:bg-orange-700 shadow-orange-900/20' : 'bg-gray-600 cursor-not-allowed opacity-50' }}">
                        {{ $profil ? 'Bayar Sekarang' : 'Lengkapi Alamat Dulu' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const payButton  = document.getElementById('pay-button');
        const agreeTerms = document.getElementById('agree-terms');

        payButton.addEventListener('click', function () {
            if (payButton.disabled) return;

            // Validasi checkbox syarat & ketentuan
            if (!agreeTerms.checked) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Centang Persetujuan',
                    text: 'Anda harus menyetujui Syarat dan Ketentuan terlebih dahulu sebelum melanjutkan pembayaran.',
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#ea580c'
                });
                return;
            }

            const shippingSelect = document.getElementById('shipping_method_select');
            const shippingId     = shippingSelect ? shippingSelect.value : null;
            const shippingName   = shippingSelect
                ? shippingSelect.options[shippingSelect.selectedIndex]?.dataset.name
                : '';

            payButton.disabled   = true;
            payButton.innerHTML  = '<i class="bi bi-arrow-repeat animate-spin mr-2"></i>Memproses...';

            fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    source:              "{{ $source }}",
                    shipping_method:     shippingName,
                    shipping_method_id:  shippingId,
                    total_harga:         {{ $grandTotal }},
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.invoice_url) {
                    window.location.href = data.invoice_url;
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Gagal membuat invoice pembayaran.',
                        confirmButtonColor: '#ea580c'
                    });
                    payButton.disabled  = false;
                    payButton.innerHTML = 'Bayar Sekarang';
                }
            })
            .catch(err => {
                console.error('Checkout error:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Terjadi kesalahan saat memproses pesanan.',
                    confirmButtonColor: '#ea580c'
                });
                payButton.disabled  = false;
                payButton.innerHTML = 'Bayar Sekarang';
            });
        });
    </script>
</body>
</html>