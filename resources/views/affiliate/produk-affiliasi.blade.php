<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Afiliasi | Affiliatord</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ sidebarOpen: window.innerWidth >= 1024 }" 
      @resize.window="sidebarOpen = (window.innerWidth >= 1024)">

    <div class="flex h-screen overflow-hidden relative">
        <aside 
            :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
            class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('affiliate.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="h-16 flex items-center justify-between px-4 md:px-6 bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors focus:outline-none">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold text-gray-800 tracking-tight hidden sm:block">
                        Produk<span class="text-orange-500">Afiliasi</span>
                    </h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex items-center justify-between">
                            <h5 class="text-xs font-black text-gray-400 uppercase tracking-widest">Katalog Tersedia</h5>
                            <span class="text-[10px] bg-orange-100 text-orange-600 px-3 py-1 rounded-full font-bold">LIVE UPDATE</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($produks as $p)
                                @if($p->variants->count() > 0)
                                    {{-- LOOPING PER VARIAN — hanya tampilkan varian yang punya data komisi --}}
                                    @foreach($p->variants as $variant)
                                        @php
                                            $komisiVarian = $p->komisis->where('produk_variant_id', $variant->id)->first();
                                        @endphp
                                        @if(!$komisiVarian)
                                            @continue
                                        @endif
                                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-500">
                                        <div class="relative h-44 bg-gray-100 overflow-hidden">
                                            <img src="{{ asset('storage/produk/' . ($p->fotos->first()->path_foto ?? '')) }}" 
                                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-2xl shadow-sm border border-orange-100">
                                                <p class="text-[10px] font-black text-orange-600 uppercase">
                                                    Komisi Rp {{ number_format($komisiVarian->nominal_komisi, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <div class="mb-4">
                                                <h6 class="font-black text-gray-800 leading-tight mb-1 truncate">{{ $p->nama_produk }}</h6>
                                                <span class="inline-block px-3 py-1 bg-orange-50 text-orange-600 text-[9px] font-black rounded-lg uppercase border border-orange-100">
                                                    {{ $variant->size }} - {{ $variant->model }}
                                                </span>
                                            </div>
                                            
                                            <form action="{{ route('affiliate.produk.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                                <button class="w-full py-4 bg-gray-900 hover:bg-orange-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg hover:-translate-y-1">
                                                    Promosikan Varian Ini
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    {{-- PRODUK TANPA VARIAN — hanya tampilkan jika ada data komisi --}}
                                    @php
                                        $komisiProduk = $p->komisis->where('produk_variant_id', null)->first();
                                    @endphp
                                    @if(!$komisiProduk)
                                        @continue
                                    @endif
                                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl transition-all">
                                        <div class="relative h-44 bg-gray-100">
                                            <img src="{{ asset('storage/produk/' . ($p->fotos->first()->path_foto ?? '')) }}" class="w-full h-full object-cover">
                                            <div class="absolute top-3 right-3 bg-white/90 backdrop-blur px-3 py-1.5 rounded-2xl shadow-sm">
                                                <p class="text-[10px] font-black text-orange-600 uppercase">
                                                    Komisi Rp {{ number_format($komisiProduk->nominal_komisi, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="p-6">
                                            <h6 class="font-black text-gray-800 mb-4 truncate">{{ $p->nama_produk }}</h6>
                                            <form action="{{ route('affiliate.produk.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="produk_id" value="{{ $p->id }}">
                                                <button class="w-full py-4 bg-gray-900 hover:bg-orange-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all">
                                                    Promosikan Produk
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h5 class="text-xs font-black text-gray-400 uppercase tracking-widest">Link Promosi Saya</h5>
                        
                        @forelse($selectedProducts as $sp)
                        <div class="bg-white p-6 rounded-[2.5rem] border-2 border-orange-100 shadow-sm hover:border-orange-500 transition-all duration-300">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h6 class="text-sm font-black text-gray-800 leading-tight">{{ $sp->produk->nama_produk }}</h6>
                                    <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">Status: Siap Bagikan</p>
                                </div>
                                <form action="{{ route('affiliate.produk.destroy', $sp->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 rounded-full bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center">
                                        <i class="bi bi-trash-fill text-xs"></i>
                                    </button>
                                </form>
                            </div>

                            <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100 mb-4 relative group/link">
                                <p class="text-[8px] font-black text-orange-400 uppercase mb-2">Unique Referral Link</p>
                                <code class="text-[10px] text-orange-600 break-all font-mono block leading-relaxed" id="link-{{ $sp->id }}">
                                    {{ url('/product/'.$sp->produk->slug.'?ref='.$affiliator->id_unik) }}
                                </code>
                            </div>

                            <button onclick="copyLink('link-{{ $sp->id }}')" class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-100 transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-copy"></i> Salin Link Share
                            </button>
                        </div>
                        @empty
                        <div class="text-center py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="bi bi-box2 text-2xl text-gray-300"></i>
                            </div>
                            <p class="text-xs text-gray-400 font-medium px-8 italic">Pilih produk di katalog untuk mulai menghasilkan komisi.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        function copyLink(id) {
            const text = document.getElementById(id).innerText;
            navigator.clipboard.writeText(text).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Link Disalin!',
                    text: 'Gunakan link ini untuk promosi di media sosial.',
                    timer: 2000,
                    showConfirmButton: false,
                    borderRadius: '2rem',
                    customClass: {
                        popup: 'rounded-[2rem]'
                    }
                });
            });
        }
    </script>
</body>
</html>