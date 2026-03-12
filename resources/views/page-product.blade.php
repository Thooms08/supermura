<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $produk->nama_produk }} | SUPERMURA.ID</title>
    @include('partials.favicon')
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta property="og:type" content="website">
<meta property="og:url" content="https://supermura.id/product/{{ $produk->id }}">
<meta property="og:title" content="{{ $produk->nama_produk }} | Rp {{ number_format($produk->harga, 0, ',', '.') }}">
<meta property="og:description" content="{{ Str::limit($produk->deskripsi, 150) }}">
<meta property="og:image" content="{{ $produk->fotos->first() ? asset('asset/produk/' . $produk->fotos->first()->path_foto) : asset('default-image.jpg') }}">

<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://supermura.id/product/{{ $produk->id }}">
<meta property="twitter:title" content="{{ $produk->nama_produk }}">
<meta property="twitter:description" content="{{ Str::limit($produk->deskripsi, 150) }}">
<meta property="twitter:image" content="{{ $produk->fotos->first() ? asset('asset/produk/' . $produk->fotos->first()->path_foto) : asset('default-image.jpg') }}">

    <style>
        [x-cloak] { display: none !important; }
        @keyframes cart-bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-cart { animation: cart-bounce 0.5s ease-in-out; }
        
        @media (max-width: 768px) {
            body { padding-bottom: 100px; }
        }

        .sticky-action-shadow {
            box-shadow: 0 -10px 25px -5px rgba(0, 0, 0, 0.05);
        }

        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-50" x-data="productPage()" x-init="init()">

    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-10"
         x-cloak
         class="fixed bottom-24 md:bottom-10 left-0 right-0 z-[100] flex justify-center px-4 pointer-events-none">
        <div class="bg-gray-900 text-white px-6 py-4 rounded-[2rem] shadow-2xl flex items-center gap-4 pointer-events-auto border border-white/10 backdrop-blur-lg bg-opacity-90">
            <div class="w-10 h-10 bg-orange-600 rounded-full flex items-center justify-center">
                <i class="bi bi-check2 text-xl font-bold"></i>
            </div>
            <div>
                <p class="font-bold text-sm">Berhasil!</p>
                <p class="text-xs text-gray-400">Masuk keranjang.</p>
            </div>
            <a href="{{ route('keranjang.index') }}" class="ml-4 bg-white/10 hover:bg-orange-600 px-4 py-2 rounded-xl text-xs font-bold transition-all">Lihat</a>
        </div>
    </div>

    @auth
    <div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4 z-40 md:hidden sticky-action-shadow">
        <div class="flex items-center gap-3">
            <a href="{{ route('keranjang.index') }}" class="w-14 h-14 flex items-center justify-center bg-gray-100 text-gray-600 rounded-2xl relative">
                <i class="bi bi-cart3 text-xl"></i>
            </a>
            
            <button @click="addToCart" :disabled="isAdding"
                    class="flex-1 h-14 bg-orange-100 text-orange-600 font-bold rounded-2xl flex items-center justify-center gap-2">
                <i class="bi bi-cart-plus text-lg" x-show="!isAdding"></i>
                <span class="text-xs">KERANJANG</span>
            </button>

            <button @click="handleBuyNow('mobile')"
                    class="flex-[1.5] h-14 bg-orange-600 text-white font-black rounded-2xl shadow-lg shadow-orange-200 text-xs tracking-widest">
                BELI SEKARANG
            </button>

            <form x-ref="buyNowFormMobile" action="{{ route('buy.now') }}" method="POST" class="hidden">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                <input type="hidden" name="qty" :value="qty">
                <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
            </form>
        </div>
    </div>
    @endauth

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="/" class="text-gray-600 hover:text-orange-600 font-bold flex items-center gap-2 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="flex items-center gap-4">
            <button @click="shareProduct()" class="hover:bg-gray-100 p-2 rounded-full transition-all">
                <i class="bi bi-share text-xl text-gray-400 cursor-pointer hover:text-orange-600"></i>
            </button>
        </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8 lg:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <div class="lg:col-span-7 space-y-4">
                <div class="relative aspect-square bg-white rounded-[2.5rem] md:rounded-[3rem] overflow-hidden border shadow-inner group">
                    <div class="absolute top-5 left-0 right-0 z-20 flex gap-2 px-8">
                        @foreach($produk->fotos as $index => $foto)
                        <div class="h-1 flex-1 bg-black/10 rounded-full overflow-hidden">
                            <div class="h-full bg-orange-500 transition-all duration-300"
                                 :style="currentIndex === {{ $index }} ? 'width: 100%' : 'width: 0%'"></div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex h-full transition-transform duration-500 ease-out"
                         :style="`transform: translateX(-${currentIndex * 100}%)`"
                         @click="openLightbox()">
                        @foreach($produk->fotos as $foto)
                            <img src="{{ asset('asset/produk/'.$foto->path_foto) }}" 
                                 class="w-full h-full object-cover flex-shrink-0 cursor-zoom-in">
                        @endforeach
                    </div>
                    
                    <button @click="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-md p-2 rounded-full text-white opacity-0 group-hover:opacity-100 transition-all">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <button @click="next()" class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 backdrop-blur-md p-2 rounded-full text-white opacity-0 group-hover:opacity-100 transition-all">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>

                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
                    @foreach($produk->fotos as $index => $foto)
                    <button @click="changeFoto({{ $index }})" 
                            class="w-20 h-20 shrink-0 rounded-2xl overflow-hidden border-2 transition-all"
                            :class="currentIndex === {{ $index }} ? 'border-orange-500 ring-4 ring-orange-50' : 'border-transparent opacity-60'">
                        <img src="{{ asset('asset/produk/'.$foto->path_foto) }}" class="w-full h-full object-cover">
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="lg:col-span-5 space-y-8">
                <div>
                    <span class="bg-orange-100 text-orange-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">
                        {{ $produk->kategori->nama_jenis }}
                    </span>
                    <h1 class="text-3xl md:text-4xl font-black text-gray-800 mt-4 leading-tight">{{ $produk->nama_produk }}</h1>
                    <div class="text-3xl font-black text-orange-600 mt-4">
                        Rp <span x-text="selectedVariant ? new Intl.NumberFormat('id-ID').format(selectedVariant.harga_variant || {{ $produk->harga }}) : '{{ number_format($produk->harga, 0, ',', '.') }}'"></span>
                    </div>
                </div>

                <div class="prose prose-orange text-gray-500 leading-relaxed text-sm md:text-base">
                    <p>{{ $produk->deskripsi }}</p>
                </div>

                <div id="variant-section" class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-6">
                    @auth
                        @if($produk->variants->count() > 0)
                        <div class="space-y-4">
                            <label class="text-xs font-black text-gray-400 uppercase tracking-widest">Pilih Varian & Ukuran</label>
                            <div class="flex flex-wrap gap-3">
                                @foreach($produk->variants as $variant)
                                <button @click="selectedVariant = {{ $variant }}" 
                                        class="px-4 py-3 rounded-2xl border-2 text-xs font-bold transition-all"
                                        :class="selectedVariant && selectedVariant.id === {{ $variant->id }} ? 'border-orange-500 bg-orange-50 text-orange-600' : 'border-gray-100 text-gray-600 hover:border-orange-200'">
                                    {{ $variant->size }} - {{ $variant->model }}
                                    <div class="text-[9px] font-medium opacity-60">Stok: {{ $variant->stok }}</div>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="flex items-center gap-6">
                            <div class="flex items-center bg-gray-50 rounded-2xl p-1 border">
                                <button @click="if(qty > 1) qty--" class="w-10 h-10 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">-</button>
                                <input type="number" x-model="qty" class="w-12 bg-transparent text-center font-black outline-none" readonly>
                                <button @click="qty++" class="w-10 h-10 flex items-center justify-center font-bold text-gray-400 hover:text-orange-600">+</button>
                            </div>
                            <div class="text-xs font-bold text-gray-400 uppercase">Jumlah</div>
                        </div>

                        <div class="pt-6 border-t border-dashed flex justify-between items-center">
                            <div class="text-xs font-black text-gray-400 uppercase tracking-widest">Subtotal</div>
                            <div class="text-2xl font-black text-gray-800">
                                Rp <span x-text="new Intl.NumberFormat('id-ID').format(qty * (selectedVariant ? (selectedVariant.harga_variant || {{ $produk->harga }}) : {{ $produk->harga }}))"></span>
                            </div>
                        </div>
                        
                        <div class="hidden md:flex gap-4">
                            <button @click="addToCart" :disabled="isAdding"
                                    class="flex-1 py-5 bg-orange-100 text-orange-600 font-black rounded-3xl hover:bg-orange-200 transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                                <i class="bi bi-cart-plus text-lg" x-show="!isAdding"></i>
                                <span x-text="isAdding ? '...' : 'Keranjang'"></span>
                            </button>

                            <form x-ref="buyNowForm" action="{{ route('buy.now') }}" method="POST" class="flex-[2]">
                                @csrf
                                <input type="hidden" name="produk_id" value="{{ $produk->id }}">
                                <input type="hidden" name="qty" :value="qty">
                                <input type="hidden" name="variant_id" :value="selectedVariant ? selectedVariant.id : ''">
                                <button type="button" @click="handleBuyNow('desktop')" class="w-full py-5 bg-orange-600 text-white font-black rounded-3xl shadow-xl shadow-orange-100 hover:bg-orange-700 transition-all uppercase tracking-widest text-xs">
                                    Beli Sekarang
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="text-center space-y-4">
                            <p class="text-sm font-bold text-gray-400">Silakan masuk untuk bertransaksi</p>
                            <a href="{{ route('login') }}" class="block w-full py-5 bg-orange-600 text-white font-black rounded-3xl shadow-xl shadow-orange-100 uppercase tracking-widest text-xs">
                                Login Sekarang
                            </a>
                        </div>
                    @endauth
                </div>

                <div class="bg-gray-900 rounded-[2.5rem] p-6 flex items-center justify-between text-white shadow-2xl">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-orange-500">
                            <img src="{{ asset('asset/profile-toko/'.$produk->toko->foto_toko) }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-black text-lg">{{ $produk->toko->nama_toko }}</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Official Store</p>
                        </div>
                    </div>
                    <button @click="showAddress = true" class="w-12 h-12 bg-white/10 hover:bg-orange-600 rounded-2xl flex items-center justify-center transition-all">
                        <i class="bi bi-geo-alt-fill text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <section class="mt-20 border-t border-gray-100 pt-16">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 tracking-tighter uppercase">Ulasan Pembeli</h2>
                    <p class="text-gray-400 text-sm font-medium mt-2">Dengarkan pengalaman mereka yang sudah membeli.</p>
                </div>
                @auth
                <button @click="showReviewForm = !showReviewForm" 
                        class="px-8 py-4 bg-gray-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-orange-600 transition-all flex items-center gap-2">
                    <i class="bi bi-pencil-square text-lg"></i>
                    <span x-text="showReviewForm ? 'Tutup Form' : 'Tulis Ulasan'"></span>
                </button>
                @endauth
            </div>

            @auth
            <div x-show="showReviewForm" x-transition x-cloak class="mb-16 bg-white p-8 md:p-12 rounded-[3rem] border-2 border-dashed border-orange-100">
                <form @submit.prevent="submitUlasan" enctype="multipart/form-data" class="space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <div class="space-y-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Beri Rating</label>
                            <div class="flex gap-3">
                                <template x-for="i in 5">
                                    <i @click="newUlasan.rating = i" 
                                       class="bi cursor-pointer text-3xl transition-all hover:scale-110"
                                       :class="i <= newUlasan.rating ? 'bi-star-fill text-orange-500' : 'bi-star text-gray-200'"></i>
                                </template>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Upload Foto (Maks 5)</label>
                            <input type="file" @change="handleFiles" multiple accept="image/*"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-orange-50 file:text-orange-600 hover:file:bg-orange-100 transition-all">
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest">Komentar Anda</label>
                        <textarea x-model="newUlasan.komentar" required
                                  class="w-full p-6 bg-gray-50 border-none rounded-[2rem] focus:ring-2 focus:ring-orange-500 min-h-[150px] text-gray-600" 
                                  placeholder="Bagaimana kualitas produk ini?"></textarea>
                    </div>

                    <button type="submit" :disabled="loadingUlasan"
                            class="w-full md:w-auto px-12 py-5 bg-orange-600 text-white font-black rounded-2xl shadow-xl shadow-orange-100 disabled:opacity-50 uppercase tracking-widest text-xs">
                        <span x-text="loadingUlasan ? 'Sedang Mengirim...' : 'Kirim Ulasan'"></span>
                    </button>
                </form>
            </div>
            @endauth

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @forelse($produk->ulasans as $u)
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-100 shadow-sm space-y-5 relative">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-600 text-white rounded-2xl flex items-center justify-center font-black text-sm shadow-lg shadow-orange-100">
                                {{ substr($u->nama, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $u->nama }}</h4>
                                <div class="flex gap-0.5 mt-1">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="bi {{ $i <= $u->rating ? 'bi-star-fill text-orange-400' : 'bi-star text-gray-100' }} text-xs"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">{{ $u->created_at->diffForHumans() }}</span>
                    </div>

                    <p class="text-gray-500 text-sm leading-relaxed">{{ $u->komentar }}</p>

                    @if($u->foto)
                    <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
                        @foreach($u->foto as $f)
                            <img src="{{ asset($f) }}" @click="openLightboxReview('{{ asset($f) }}')"
                                 class="w-20 h-20 rounded-2xl object-cover cursor-zoom-in border-2 border-transparent hover:border-orange-500 transition-all shrink-0">
                        @endforeach
                    </div>
                    @endif

                    @auth
                        @if($u->user_id == Auth::id())
                        <div class="flex gap-4 pt-2">
                            <button @click="prepareEdit({{ json_encode($u) }})" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:text-blue-800 transition-all">Edit</button>
                            <button @click="deleteUlasan({{ $u->id }})" class="text-[10px] font-black text-red-500 uppercase tracking-widest hover:text-red-700 transition-all">Hapus</button>
                        </div>
                        @endif
                    @endauth
                </div>
                @empty
                <div class="col-span-full py-24 text-center bg-white rounded-[3rem] border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="bi bi-chat-square-dots text-3xl text-gray-200"></i>
                    </div>
                    <p class="text-gray-400 font-bold uppercase tracking-widest text-xs">Belum ada ulasan untuk produk ini</p>
                </div>
                @endforelse
            </div>
        </section>
    </main>

    <div x-show="showEditModal" class="fixed inset-0 z-[160] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div @click.away="showEditModal = false" class="bg-white w-full max-w-lg rounded-[3rem] p-10 shadow-2xl space-y-8">
            <h3 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Edit Ulasan Anda</h3>
            
            <div class="space-y-6">
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Ubah Rating</label>
                    <div class="flex gap-3">
                        <template x-for="i in 5">
                            <i @click="editingData.rating = i" 
                               class="bi cursor-pointer text-2xl transition-all"
                               :class="i <= editingData.rating ? 'bi-star-fill text-orange-500' : 'bi-star text-gray-200'"></i>
                        </template>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Ubah Komentar</label>
                    <textarea x-model="editingData.komentar" class="w-full p-5 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-orange-500 min-h-[120px] text-sm text-gray-600"></textarea>
                </div>
            </div>

            <div class="flex gap-4">
                <button @click="showEditModal = false" class="flex-1 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl hover:bg-gray-200 transition-all text-[10px] uppercase tracking-widest">Batal</button>
                <button @click="updateUlasan" class="flex-[2] py-4 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-700 transition-all text-[10px] uppercase tracking-widest shadow-lg shadow-orange-100">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <div x-show="showLightbox" x-transition x-cloak
         class="fixed inset-0 z-[150] flex items-center justify-center bg-black/95 p-4">
        <button @click="showLightbox = false" class="absolute top-6 right-6 text-white text-3xl">
            <i class="bi bi-x-lg"></i>
        </button>
        <img :src="currentLightboxFoto" class="max-w-full max-h-full object-contain rounded-xl">
    </div>

    <div x-show="showAddress" class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm" x-cloak x-transition>
        <div @click.away="showAddress = false" class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl text-center space-y-6">
            <div class="w-20 h-20 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto">
                <i class="bi bi-geo-alt-fill text-4xl"></i>
            </div>
            <div>
                <h3 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">Lokasi Toko</h3>
                <p class="text-gray-500 mt-4 leading-relaxed font-medium">{{ $produk->toko->alamat ?? 'Alamat tidak tersedia.' }}</p>
            </div>
            <button @click="showAddress = false" class="w-full py-4 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all">Tutup</button>
        </div>
    </div>

    <script>
    function productPage() {
        return {
            async shareProduct() {
            const shareData = {
                title: "{{ $produk->nama_produk }}",
                text: `Cek produk ini di SUPERMURA.ID: {{ $produk->nama_produk }} - Rp {{ number_format($produk->harga, 0, ',', '.') }}\n\n`,
                url: "https://supermura.id/product/{{ $produk->id }}"
            };

            try {
                // Cek jika browser mendukung Web Share API (Mobile Chrome/Safari)
                if (navigator.share) {
                    await navigator.share(shareData);
                } else {
                    // Fallback: Salin link ke clipboard jika di Desktop/Browser lama
                    await navigator.clipboard.writeText(shareData.url);
                    Swal.fire({
                        icon: 'success',
                        title: 'Link Berhasil Disalin!',
                        text: 'Silakan bagikan link produk ini ke teman Anda.',
                        timer: 2000,
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                }
            } catch (err) {
                console.error('Gagal berbagi:', err);
            }
        },
            currentIndex: 0,
            totalFotos: {{ $produk->fotos->count() }},
            fotos: [
                @foreach($produk->fotos as $foto)
                    "{{ asset('asset/produk/'.$foto->path_foto) }}",
                @endforeach
            ],
            showLightbox: false,
            currentLightboxFoto: '',
            autoSwipeInterval: null,
            showAddress: false,
            selectedVariant: null,
            qty: 1,
            isAdding: false,
            showToast: false,

            // Ulasan State
            showReviewForm: false,
            showEditModal: false,
            loadingUlasan: false,
            newUlasan: {
                rating: 5,
                komentar: '',
                files: []
            },
            editingData: {
                id: null,
                rating: 5,
                komentar: ''
            },

            init() {
                this.startAutoSwipe();
            },

            startAutoSwipe() {
                clearInterval(this.autoSwipeInterval);
                this.autoSwipeInterval = setInterval(() => {
                    if (!this.showLightbox) {
                        this.next();
                    }
                }, 5000);
            },

            next() {
                this.currentIndex = (this.currentIndex + 1) % this.totalFotos;
            },

            prev() {
                this.currentIndex = (this.currentIndex - 1 + this.totalFotos) % this.totalFotos;
            },

            changeFoto(index) {
                this.currentIndex = index;
                this.startAutoSwipe();
            },

            openLightbox() {
                this.currentLightboxFoto = this.fotos[this.currentIndex];
                this.showLightbox = true;
            },

            openLightboxReview(url) {
                this.currentLightboxFoto = url;
                this.showLightbox = true;
            },

            handleBuyNow(type) {
                if ({{ $produk->variants->count() }} > 0 && !this.selectedVariant) {
                    Swal.fire({
                        title: 'Pilih Varian Dahulu',
                        text: 'Silakan pilih ukuran atau model produk sebelum melanjutkan.',
                        icon: 'warning',
                        confirmButtonColor: '#EA580C',
                        confirmButtonText: 'OKE',
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then(() => {
                        document.querySelector('#variant-section').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    });
                    return;
                }
                
                if(type === 'mobile') this.$refs.buyNowFormMobile.submit();
                else this.$refs.buyNowForm.submit();
            },

            async addToCart() {
                if ({{ $produk->variants->count() }} > 0 && !this.selectedVariant) {
                    Swal.fire({
                        title: 'Varian Belum Dipilih',
                        text: 'Pilih varian produk terlebih dahulu.',
                        icon: 'info',
                        confirmButtonColor: '#EA580C'
                    });
                    return;
                }

                this.isAdding = true;
                try {
                    const response = await fetch("{{ route('keranjang.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            produk_id: {{ $produk->id }},
                            qty: this.qty,
                            variant_id: this.selectedVariant ? this.selectedVariant.id : null
                        })
                    });

                    if (response.ok) {
                        this.showToast = true;
                        setTimeout(() => { this.showToast = false; }, 3000);
                    }
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    this.isAdding = false;
                }
            },

            // LOGIKA ULASAN
            handleFiles(event) {
                this.newUlasan.files = event.target.files;
            },

            async submitUlasan() {
                this.loadingUlasan = true;
                let formData = new FormData();
                formData.append('produk_id', {{ $produk->id }});
                formData.append('rating', this.newUlasan.rating);
                formData.append('komentar', this.newUlasan.komentar);
                
                for (let i = 0; i < this.newUlasan.files.length; i++) {
                    formData.append('foto[]', this.newUlasan.files[i]);
                }

                try {
                    const response = await fetch("{{ route('ulasan.store') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: formData
                    });
                    const result = await response.json();
                    
                    if (response.ok) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Berhasil!', 
                            text: result.message,
                            customClass: { popup: 'rounded-[2rem]' }
                        }).then(() => location.reload());
                    }
                } catch (error) {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengirim ulasan.', 'error');
                } finally {
                    this.loadingUlasan = false;
                }
            },

            prepareEdit(ulasan) {
                this.editingData = {
                    id: ulasan.id,
                    rating: ulasan.rating,
                    komentar: ulasan.komentar
                };
                this.showEditModal = true;
            },

            async updateUlasan() {
                try {
                    const response = await fetch(`/ulasan/${this.editingData.id}`, {
                        method: 'PUT',
                        headers: { 
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                        },
                        body: JSON.stringify(this.editingData)
                    });
                    
                    if (response.ok) {
                        Swal.fire({ 
                            icon: 'success', 
                            title: 'Diperbarui!', 
                            text: 'Ulasan berhasil diubah.',
                            customClass: { popup: 'rounded-[2rem]' }
                        }).then(() => location.reload());
                    }
                } catch (error) {
                    console.error(error);
                }
            },

            async deleteUlasan(id) {
                const res = await Swal.fire({
                    title: 'Hapus Ulasan?',
                    text: "Tindakan ini tidak bisa dibatalkan.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-[2rem]' }
                });

                if (res.isConfirmed) {
                    try {
                        const response = await fetch(`/ulasan/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                        });
                        if (response.ok) {
                            location.reload();
                        }
                    } catch (error) {
                        console.error(error);
                    }
                }
            }
        }
    }
    </script>
</body>
</html>