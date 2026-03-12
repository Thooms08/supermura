<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.favicon')
    <title>Checkout | Flavory.id</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

    <div class="max-w-4xl mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-orange-600 transition-colors flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="text-3xl font-black text-orange-600 uppercase tracking-tighter">Konfirmasi Pesanan</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-6">
                
                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-lg flex items-center gap-2">
                            <i class="bi bi-geo-alt text-orange-600"></i> Alamat Pengiriman
                        </h3>
                        <a href="{{ route('pengunjung.alamat.index') }}" class="text-xs bg-orange-100 text-orange-600 px-4 py-2 rounded-full font-bold hover:bg-orange-600 hover:text-white transition-all">
                            <i class="bi bi-plus-lg"></i> Alamat
                        </a>
                    </div>
                    <div class="text-sm space-y-1 p-4 bg-orange-50/50 rounded-2xl border border-orange-100">
                        @if($profil)
                            <p class="font-bold text-gray-900">{{ $profil->nama_lengkap }}</p>
                            <p class="text-gray-600">{{ $profil->no_whatsapp }}</p>
                            <p class="text-gray-600">{{ $profil->alamat_lengkap }}</p>
                            <p class="text-gray-600">{{ $profil->kota_kabupaten }}, {{ $profil->provinsi }}, {{ $profil->kode_pos }}</p>
                        @else
                            <p class="text-gray-500 italic">Alamat belum diset. Silakan tambah alamat.</p>
                        @endif
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <h3 class="font-bold text-lg mb-4">Produk yang Dipesan</h3>
                    <div class="flex gap-4">
                        <img src="{{ asset('asset/produk/'.$produk->fotos->first()->path_foto) }}" class="w-20 h-20 object-cover rounded-2xl border">
                        <div class="flex-1">
                            <h4 class="font-bold">{{ $produk->nama_produk }}</h4>
                            <p class="text-xs text-gray-400">Qty: {{ $checkoutData['qty'] }}</p>
                            <p class="text-orange-600 font-black mt-1">Rp {{ number_format($checkoutData['harga'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-3xl border shadow-sm">
                    <h3 class="font-bold text-lg mb-4">Metode Pengiriman</h3>
                    <select id="shipping_method" class="w-full p-3 bg-gray-50 border rounded-2xl outline-none focus:ring-2 focus:ring-orange-500">
                        @foreach($shippingMethods as $method)
                            <option value="{{ $method->nama_metode }}">{{ $method->nama_metode }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-gray-900 text-white p-8 rounded-[2.5rem] shadow-xl sticky top-6">
                    <h3 class="text-xl font-black mb-6 uppercase tracking-widest text-orange-500">Ringkasan Biaya</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between opacity-70">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($checkoutData['qty'] * $checkoutData['harga'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between opacity-70">
                            <span>Biaya Layanan</span>
                            <span>Rp 0</span>
                        </div>
                        <hr class="border-white/10">
                        <div class="flex justify-between items-end">
                            <span class="font-bold">Total Tagihan</span>
                            <span class="text-2xl font-black text-orange-500">Rp {{ number_format($checkoutData['qty'] * $checkoutData['harga'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <button id="pay-button" class="w-full mt-8 py-4 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-700 transition-all uppercase tracking-widest text-xs shadow-lg shadow-orange-900/20">
                        Bayar Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            payButton.disabled = true;
            payButton.innerHTML = "Memproses...";

            fetch("{{ route('checkout.process') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    shipping_method: document.getElementById('shipping_method').value,
                    total_harga: {{ $checkoutData['qty'] * $checkoutData['harga'] }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.invoice_url) {
                    // Redirect langsung ke halaman pembayaran Xendit
                    window.location.href = data.invoice_url;
                } else {
                    alert(data.message || "Gagal membuat invoice pembayaran.");
                    payButton.disabled = false;
                    payButton.innerHTML = "Bayar Sekarang";
                }
            })
            .catch(error => {
                console.error("Error:", error);
                alert("Terjadi kesalahan saat memproses pesanan.");
                payButton.disabled = false;
                payButton.innerHTML = "Bayar Sekarang";
            });
        });
    </script>
</body>
</html>