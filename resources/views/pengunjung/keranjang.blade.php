<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | SUPERMURA.ID</title>
    @include('partials.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50" x-data="cartComponent()">

    <nav class="bg-white border-b sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-gray-600 hover:text-orange-600 font-bold flex items-center gap-2">
                <i class="bi bi-arrow-left text-xl"></i> Kembali
            </a>
            <h1 class="text-xl font-black text-orange-600 uppercase tracking-tighter">Keranjang Saya</h1>
            <div class="w-10"></div>
        </div>
    </nav>

    <main class="max-w-5xl mx-auto px-4 py-10 pb-32">
        @if($cartItems->isEmpty())
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-orange-50 text-orange-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="bi bi-cart-x text-6xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800">Keranjang Kosong</h2>
                <p class="text-gray-400 mt-2 mb-8">Yuk, cari produk favoritmu sekarang!</p>
                <a href="{{ route('home') }}" class="bg-orange-600 text-white px-8 py-4 rounded-2xl font-bold shadow-lg hover:bg-orange-700 transition-all">Mulai Belanja</a>
            </div>
        @else
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-2xl border">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" @click="toggleSelectAll" :checked="selectAll" class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <span class="font-bold text-gray-700">Pilih Semua</span>
                </label>
                <button @click="confirmDeleteAll" class="text-sm font-bold text-red-500 hover:underline">Hapus Terpilih</button>
            </div>

            <form id="checkoutForm" action="{{ route('keranjang.checkout.process') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    @foreach($cartItems as $item)
                    <div class="bg-white p-4 md:p-6 rounded-[2rem] border shadow-sm flex items-center gap-4 md:gap-6 relative overflow-hidden group">
                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" 
                               x-model="selectedItems" 
                               data-price="{{ $item->variant ? ($item->variant->harga_variant ?? $item->produk->harga) : $item->produk->harga }}"
                               data-qty="{{ $item->qty }}"
                               class="w-6 h-6 rounded-lg border-gray-300 text-orange-600 focus:ring-orange-500 z-10">

                        <a href="{{ route('product.detail', $item->produk->id) }}" class="block shrink-0">
                            <img src="{{ asset('asset/produk/'.$item->produk->fotos->first()->path_foto) }}" class="w-20 h-20 md:w-28 md:h-28 object-cover rounded-2xl border">
                        </a>

                        <div class="flex-1">
                            <a href="{{ route('product.detail', $item->produk->id) }}" class="block">
                                <h3 class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-1">{{ $item->produk->nama_produk }}</h3>
                            </a>
                            @if($item->variant)
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Varian: {{ $item->variant->size }} - {{ $item->variant->model }}</p>
                            @endif
                            <div class="text-orange-600 font-black text-lg mt-2">
                                Rp {{ number_format($item->variant ? ($item->variant->harga_variant ?? $item->produk->harga) : $item->produk->harga, 0, ',', '.') }}
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                <div class="flex items-center bg-gray-50 rounded-xl p-1 border">
                                    <button type="button" @click="updateQty({{ $item->id }}, 'decrease')" class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">-</button>
                                    <span class="px-4 font-black text-sm" id="qty-{{ $item->id }}">{{ $item->qty }}</span>
                                    <button type="button" @click="updateQty({{ $item->id }}, 'increase')" class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">+</button>
                                </div>

                                <button type="button" @click="deleteItem({{ $item->id }})" class="text-gray-300 hover:text-red-500 transition-colors">
                                    <i class="bi bi-trash3 text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </form>
        @endif
    </main>

    @if(!$cartItems->isEmpty())
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 z-40 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex flex-col">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Pembayaran</span>
                <span class="text-2xl font-black text-orange-600">Rp <span x-text="formatNumber(totalPrice)"></span></span>
            </div>
            <button type="submit" form="checkoutForm" 
                    :disabled="selectedItems.length === 0"
                    :class="selectedItems.length === 0 ? 'bg-gray-200 cursor-not-allowed' : 'bg-orange-600 hover:bg-orange-700 shadow-orange-200 shadow-lg'"
                    class="px-10 py-4 text-white font-black rounded-2xl transition-all uppercase tracking-widest text-xs">
                Checkout (<span x-text="selectedItems.length"></span>)
            </button>
        </div>
    </div>
    @endif

    <script>
        function cartComponent() {
            return {
                selectedItems: [],
                selectAll: false,
                cartData: @json($cartItems->map(function($item) {
                    return [
                        'id' => $item->id,
                        'price' => $item->variant ? ($item->variant->harga_variant ?? $item->produk->harga) : $item->produk->harga,
                        'qty' => $item->qty
                    ];
                })),

                get totalPrice() {
                    let total = 0;
                    this.selectedItems.forEach(id => {
                        const item = this.cartData.find(i => i.id == id);
                        if (item) total += item.price * item.qty;
                    });
                    return total;
                },

                toggleSelectAll() {
                    this.selectAll = !this.selectAll;
                    if (this.selectAll) {
                        this.selectedItems = this.cartData.map(i => i.id.toString());
                    } else {
                        this.selectedItems = [];
                    }
                },

                updateQty(id, action) {
                    fetch(`/keranjang/update/${id}?action=${action}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if(data.success) {
                            // Update local data for total calculation
                            const item = this.cartData.find(i => i.id == id);
                            item.qty = data.new_qty;
                            document.getElementById(`qty-${id}`).innerText = data.new_qty;
                        }
                    });
                },

                deleteItem(id) {
                    if(confirm('Hapus item ini dari keranjang?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/keranjang/delete/${id}`;
                        form.innerHTML = `@csrf @method('DELETE')`;
                        document.body.appendChild(form);
                        form.submit();
                    }
                },

                formatNumber(number) {
                    return new Intl.NumberFormat('id-ID').format(number);
                }
            }
        }
    </script>
</body>
</html>