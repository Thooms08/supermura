<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | SUPERMURA.ID</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-50" x-data="cartComponent()">

    {{-- Toast notifikasi --}}
    <div x-show="toast.show" x-cloak x-transition
         class="fixed top-6 left-1/2 -translate-x-1/2 z-[100] px-6 py-4 rounded-2xl shadow-2xl text-white text-sm font-bold flex items-center gap-3"
         :class="toast.type === 'error' ? 'bg-red-500' : 'bg-gray-900'">
        <i :class="toast.type === 'error' ? 'bi bi-x-circle' : 'bi bi-check-circle'"></i>
        <span x-text="toast.message"></span>
    </div>

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

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
        @endif

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
            {{-- Header pilih semua --}}
            <div class="flex items-center justify-between mb-6 bg-white p-4 rounded-2xl border">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" @click="toggleSelectAll" :checked="selectAll"
                           class="w-5 h-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500">
                    <span class="font-bold text-gray-700">Pilih Semua</span>
                </label>
                <button type="button" @click="confirmDeleteSelected"
                        x-show="selectedItems.length > 0"
                        class="text-sm font-bold text-red-500 hover:underline">
                    Hapus Terpilih (<span x-text="selectedItems.length"></span>)
                </button>
            </div>

            <form id="checkoutForm" action="{{ route('keranjang.checkout.process') }}" method="POST">
                @csrf
                <div class="space-y-4" id="cartList">
                    @foreach($cartItems as $item)
                    @php
                        $hargaSatuan = $item->variant
                            ? ($item->variant->harga_variant ?? $item->produk->harga)
                            : $item->produk->harga;
                        $stokMax = $item->variant ? $item->variant->stok : $item->produk->totalStok();
                    @endphp
                    <div id="cart-item-{{ $item->id }}"
                         class="bg-white p-4 md:p-6 rounded-[2rem] border shadow-sm flex items-center gap-4 md:gap-6 relative overflow-hidden group">

                        {{-- Checkbox --}}
                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}"
                               x-model="selectedItems"
                               class="w-6 h-6 rounded-lg border-gray-300 text-orange-600 focus:ring-orange-500 z-10 shrink-0">

                        {{-- Foto produk — link pakai slug (product.show) --}}
                        <a href="{{ route('product.show', $item->produk->slug) }}" class="block shrink-0">
                            <img src="{{ $item->produk->fotos->isNotEmpty() ? asset('storage/produk/'.$item->produk->fotos->first()->path_foto) : asset('asset/default-image.jpg') }}"
                                 class="w-20 h-20 md:w-28 md:h-28 object-cover rounded-2xl border"
                                 alt="{{ $item->produk->nama_produk }}">
                        </a>

                        <div class="flex-1 min-w-0">
                            <a href="{{ route('product.show', $item->produk->slug) }}" class="block">
                                <h3 class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors line-clamp-1">
                                    {{ $item->produk->nama_produk }}
                                </h3>
                            </a>

                            @if($item->variant)
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                    Varian: {{ $item->variant->size }} - {{ $item->variant->model }}
                                </p>
                            @endif

                            {{-- Stok info --}}
                            <p class="text-[10px] text-gray-400 mt-0.5">
                                Stok tersedia: <span class="font-bold {{ $stokMax <= 5 ? 'text-red-500' : 'text-gray-600' }}">{{ $stokMax }}</span>
                            </p>

                            <div class="text-orange-600 font-black text-lg mt-2">
                                Rp {{ number_format($hargaSatuan, 0, ',', '.') }}
                            </div>

                            <div class="flex items-center justify-between mt-4">
                                {{-- Qty control --}}
                                <div class="flex items-center bg-gray-50 rounded-xl p-1 border">
                                    <button type="button"
                                            @click="updateQty({{ $item->id }}, 'decrease', {{ $hargaSatuan }})"
                                            class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">
                                        -
                                    </button>
                                    <span class="px-4 font-black text-sm" id="qty-{{ $item->id }}">{{ $item->qty }}</span>
                                    <button type="button"
                                            @click="updateQty({{ $item->id }}, 'increase', {{ $hargaSatuan }})"
                                            class="w-8 h-8 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">
                                        +
                                    </button>
                                </div>

                                {{-- Subtotal item --}}
                                <div class="text-right hidden md:block">
                                    <span class="text-[9px] text-gray-400 uppercase font-bold">Subtotal</span>
                                    <div class="font-black text-gray-800 text-sm" id="subtotal-{{ $item->id }}">
                                        Rp {{ number_format($hargaSatuan * $item->qty, 0, ',', '.') }}
                                    </div>
                                </div>

                                {{-- Tombol hapus --}}
                                <button type="button" @click="deleteItem({{ $item->id }})"
                                        class="text-gray-300 hover:text-red-500 transition-colors ml-2">
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
                <span class="text-2xl font-black text-orange-600">
                    Rp <span x-text="formatNumber(totalPrice)"></span>
                </span>
                <span class="text-[10px] text-gray-400" x-show="selectedItems.length === 0">
                    Pilih produk untuk checkout
                </span>
            </div>
            <button type="submit" form="checkoutForm"
                    :disabled="selectedItems.length === 0"
                    :class="selectedItems.length === 0 ? 'bg-gray-200 cursor-not-allowed text-gray-400' : 'bg-orange-600 hover:bg-orange-700 shadow-orange-200 shadow-lg text-white'"
                    class="px-10 py-4 font-black rounded-2xl transition-all uppercase tracking-widest text-xs">
                Checkout (<span x-text="selectedItems.length"></span>)
            </button>
        </div>
    </div>
    @endif

    @php
        $cartDataJson = $cartItems->map(function ($item) {
            return [
                'id'    => $item->id,
                'price' => $item->variant
                    ? ($item->variant->harga_variant ?? $item->produk->harga)
                    : $item->produk->harga,
                'qty'   => $item->qty,
            ];
        })->values()->toArray();
    @endphp
    <script>
    const CSRF_TOKEN = '{{ csrf_token() }}';

    function cartComponent() {
        return {
            selectedItems: [],
            selectAll: false,
            toast: { show: false, message: '', type: 'success' },

            // Data lokal untuk kalkulasi total (id, price, qty)
            cartData: @json($cartDataJson),

            // ── Total harga dari item yang di-centang ──
            get totalPrice() {
                return this.selectedItems.reduce((total, id) => {
                    const item = this.cartData.find(i => i.id == id);
                    return item ? total + (item.price * item.qty) : total;
                }, 0);
            },

            // ── Pilih / batal semua ──
            toggleSelectAll() {
                this.selectAll = !this.selectAll;
                this.selectedItems = this.selectAll
                    ? this.cartData.map(i => String(i.id))
                    : [];
            },

            // ── Tampilkan toast ──
            showToast(message, type = 'success') {
                this.toast = { show: true, message, type };
                setTimeout(() => { this.toast.show = false; }, 3000);
            },

            // ── Update qty (+ / -) ──
            async updateQty(id, action, price) {
                try {
                    const res = await fetch(`/keranjang/update/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ action }),
                    });

                    const data = await res.json();

                    if (!res.ok || !data.success) {
                        this.showToast(data.message || 'Gagal memperbarui qty.', 'error');
                        return;
                    }

                    // Item dihapus karena qty turun ke 0
                    if (data.deleted) {
                        document.getElementById(`cart-item-${id}`)?.remove();
                        this.cartData = this.cartData.filter(i => i.id != id);
                        this.selectedItems = this.selectedItems.filter(i => i != String(id));
                        this.showToast('Item dihapus dari keranjang.');
                        if (this.cartData.length === 0) location.reload();
                        return;
                    }

                    // Update data lokal
                    const item = this.cartData.find(i => i.id == id);
                    if (item) item.qty = data.new_qty;

                    // Update tampilan
                    const qtyEl = document.getElementById(`qty-${id}`);
                    if (qtyEl) qtyEl.innerText = data.new_qty;

                    const subtotalEl = document.getElementById(`subtotal-${id}`);
                    if (subtotalEl) {
                        subtotalEl.innerText = 'Rp ' + this.formatNumber(data.new_qty * price);
                    }

                } catch (err) {
                    this.showToast('Terjadi kesalahan jaringan.', 'error');
                }
            },

            // ── Hapus satu item ──
            async deleteItem(id) {
                if (!confirm('Hapus item ini dari keranjang?')) return;

                try {
                    const res = await fetch(`/keranjang/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                    });

                    if (res.ok) {
                        document.getElementById(`cart-item-${id}`)?.remove();
                        this.cartData = this.cartData.filter(i => i.id != id);
                        this.selectedItems = this.selectedItems.filter(i => i != String(id));
                        this.showToast('Item dihapus dari keranjang.');
                        if (this.cartData.length === 0) location.reload();
                    }
                } catch (err) {
                    this.showToast('Gagal menghapus item.', 'error');
                }
            },

            // ── Hapus semua item yang di-centang ──
            async confirmDeleteSelected() {
                if (this.selectedItems.length === 0) return;
                if (!confirm(`Hapus ${this.selectedItems.length} item yang dipilih?`)) return;

                const idsToDelete = [...this.selectedItems];
                for (const id of idsToDelete) {
                    await fetch(`/keranjang/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                            'Accept': 'application/json',
                        },
                    });
                    document.getElementById(`cart-item-${id}`)?.remove();
                    this.cartData = this.cartData.filter(i => i.id != id);
                }
                this.selectedItems = [];
                this.selectAll = false;
                this.showToast(`${idsToDelete.length} item berhasil dihapus.`);
                if (this.cartData.length === 0) location.reload();
            },

            formatNumber(number) {
                return new Intl.NumberFormat('id-ID').format(number);
            },
        };
    }
    </script>
</body>
</html>
