<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SUPERMURA.ID | Solusi Kuliner & Fashion Terpercaya')</title>
    <meta name="description" content="@yield('meta_description', 'SUPERMURA.ID adalah platform e-commerce terpercaya yang menyediakan berbagai produk fashion berkualitas dan kuliner dengan harga terjangkau oleh PT BRYCLO MEGA JAYA.')">
    <meta name="keywords" content="@yield('meta_keywords', 'supermura, supermura.id, e-commerce indonesia, fashion murah, pt bryclo mega jaya, belanja online aman')">
    <meta name="author" content="PT BRYCLO MEGA JAYA">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'SUPERMURA.ID | Solusi Kuliner & Fashion Terpercaya')">
    <meta property="og:description" content="@yield('meta_description', 'SUPERMURA.ID adalah platform e-commerce terpercaya yang menyediakan berbagai produk fashion berkualitas dengan harga terjangkau.')">
    <meta property="og:image" content="{{ asset('assets/images/logo-supermura.jpg') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/logo-supermura.jpg') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    @stack('styles')
    @include('layouts.favicon')
</head>
<body class="bg-gray-50 font-sans text-gray-900 flex flex-col min-h-screen">

    <nav x-data="{ mobileMenuOpen: false }" class="bg-white border-b sticky top-0 z-50 shadow-sm">
        @auth
            @php
                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();

                // Hitung pesanan yang butuh perhatian user dan belum dilihat
                $pengunjungId = \App\Models\Pengunjung::where('user_id', auth()->id())->value('id');
                $unreadOrderCount = $pengunjungId ? \App\Models\Pesanan::where('pengunjung_id', $pengunjungId)
                    ->whereNull('seen_at')
                    ->where(function($q) {
                        $q->where('status', 'pending')
                          ->orWhere(function($q2) {
                              $q2->where('status', 'fail')->where('cancelled_before_payment', false);
                          })
                          ->orWhereHas('refund', fn($q3) => $q3->where('status', 'fail'));
                    })
                    ->count() : 0;
            @endphp
        @endauth

        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3">
                <img src="{{ asset('assets/images/logo-supermura.jpg') }}" alt="Logo SUPERMURA.ID" class="w-10 h-10 rounded-xl object-cover shadow-sm border border-gray-100">
                <span class="text-2xl font-black text-orange-600 tracking-tighter hidden sm:block">
                    SUPERMURA<span class="text-gray-800">.ID</span>
                </span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('home') }}" 
                    class="text-sm font-bold transition-all flex items-center {{ request()->routeIs('affiliate.program') ? 'text-orange-600' : 'text-gray-600 hover:text-orange-600' }}">
                    <i class="bi bi-house me-1"></i> Beranda
                </a>

                <a href="{{ route('affiliate.program') }}" 
                    class="text-sm font-bold transition-all flex items-center {{ request()->routeIs('affiliate.program') ? 'text-orange-600' : 'text-gray-600 hover:text-orange-600' }}">
                    <i class="bi bi-megaphone me-1"></i> Program Affiliate
                </a>

                @auth
                    <a href="{{ route('pengunjung.pesanan') }}" 
                        class="text-sm font-bold transition-all flex items-center gap-1 relative {{ request()->routeIs('pengunjung.pesanan') ? 'text-orange-600' : 'text-gray-600 hover:text-orange-600' }}">
                        <i class="bi bi-box-seam"></i> Pesanan
                        @if($unreadOrderCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-black min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center leading-none">
                                {{ $unreadOrderCount > 9 ? '9+' : $unreadOrderCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('keranjang.index') }}" class="text-sm font-bold text-gray-600 hover:text-orange-600 transition-all relative">
                        <i class="bi bi-cart3 me-1"></i> Keranjang
                        @if(isset($cartCount) && $cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-orange-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center border-2 border-white">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    <a href="{{ route('pengunjung.profile') }}" class="flex items-center gap-2 bg-orange-50 text-orange-600 px-4 py-2 rounded-xl font-bold text-sm transition-all hover:bg-orange-100">
                        <i class="bi bi-person-circle text-lg"></i> {{ auth()->user()->name }}
                    </a>

                    <form id="logout-form-nav" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                    <button type="button" onclick="confirmLogout('logout-form-nav')" class="text-gray-400 hover:text-red-500 transition-colors" title="Keluar">
                        <i class="bi bi-box-arrow-right text-xl"></i>
                    </button>
                @else
                    <div class="flex items-center gap-4">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-orange-600 transition-all">Masuk</a>
                        <a href="{{ route('register') }}" class="bg-orange-600 text-white px-6 py-2.5 rounded-xl font-bold text-sm shadow-lg shadow-orange-100 hover:bg-orange-700 transition-all">Daftar Sekarang</a>
                    </div>
                @endauth
            </div>

            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-gray-600 hover:text-orange-600 focus:outline-none p-2">
                    <i class="bi" :class="mobileMenuOpen ? 'bi-x-lg text-2xl' : 'bi-list text-3xl'"></i>
                </button>
            </div>
        </div>

        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="md:hidden bg-white border-t p-6 space-y-5 shadow-xl absolute w-full left-0">
             
             <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-4">
                 <img src="{{ asset('assets/images/logo-supermura.jpg') }}" alt="Logo" class="w-10 h-10 rounded-xl">
                 <span class="text-xl font-black text-orange-600 tracking-tighter">
                     SUPERMURA<span class="text-gray-800">.ID</span>
                 </span>
             </div>

             <a href="{{ route('affiliate.program') }}" class="block font-bold text-gray-600 hover:text-orange-600"><i class="bi bi-megaphone me-2"></i> Program Affiliate</a>
             
             @auth
                <a href="{{ route('keranjang.index') }}" class="block font-bold text-gray-600 hover:text-orange-600"><i class="bi bi-cart3 me-2"></i> Keranjang</a>
                <a href="{{ route('pengunjung.pesanan') }}" class="font-bold text-gray-600 flex items-center gap-2 hover:text-orange-600">
                    <i class="bi bi-box-seam me-1"></i> Pesanan
                    @if(isset($unreadOrderCount) && $unreadOrderCount > 0)
                        <span class="bg-red-500 text-white text-[10px] font-black min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center leading-none">
                            {{ $unreadOrderCount > 9 ? '9+' : $unreadOrderCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('pengunjung.profile') }}" class="block font-bold text-gray-600 hover:text-orange-600"><i class="bi bi-person-circle me-2"></i> Profil Akun</a>
                <button type="button" onclick="confirmLogout('logout-form-nav')" class="font-bold text-red-500 flex items-center w-full text-left mt-4 pt-4 border-t border-gray-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
             @else
                <a href="{{ route('login') }}" class="block font-bold text-gray-600 hover:text-orange-600"><i class="bi bi-box-arrow-in-right me-2"></i> Masuk</a>
                <a href="{{ route('register') }}" class="block text-center bg-orange-600 text-white px-4 py-3 rounded-xl font-bold text-sm shadow-md mt-2">Daftar Sekarang</a>
             @endauth
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-gray-950 text-white pt-16 pb-8 mt-auto border-t-[6px] border-orange-500">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                
                <div>
                    <a href="/" class="flex items-center gap-3 mb-6">
                        <img src="{{ asset('assets/images/logo-supermura.jpg') }}" alt="Logo" class="w-12 h-12 rounded-xl border border-gray-800">
                        <span class="text-3xl font-black text-orange-500 tracking-tighter">
                            SUPERMURA<span class="text-white">.ID</span>
                        </span>
                    </a>
                    <p class="text-gray-400 text-sm leading-relaxed mb-6">
                        Platform e-commerce terpercaya yang menyediakan berbagai produk fashion berkualitas dengan harga yang terjangkau untuk seluruh lapisan masyarakat Indonesia.
                    </p>
                    <div class="flex items-start gap-3 text-gray-400 text-sm">
                        <i class="bi bi-geo-alt-fill text-orange-500 mt-1"></i>
                        <p class="leading-relaxed">
                            <strong class="text-white">PT BRYCLO MEGA JAYA</strong><br>
                            Komplek Parahyangan kencana BLOK L 3<br>
                            Jln Langsat 3 no 14 RT 1 RW 12<br>
                            Kecamatan Cangkuang, Desa Pananjung<br>
                            Kode Pos 40238, Jawa Barat
                        </p>
                    </div>
                </div>

                <div class="md:pl-8">
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-6">Informasi & Bantuan</h3>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li><a href="{{ route('tentang') }}" class="hover:text-orange-500 transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Tentang Kami</a></li>
                        <li><a href="{{ route('faq') }}" class="hover:text-orange-500 transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> FAQ (Tanya Jawab)</a></li>
                        <li><a href="{{ route('ketentuan') }}" class="hover:text-orange-500 transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Syarat & Ketentuan</a></li>
                        <li><a href="{{ route('privasi') }}" class="hover:text-orange-500 transition-colors flex items-center gap-2"><i class="bi bi-chevron-right text-[10px]"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-white uppercase tracking-wider mb-6">Hubungi Kami</h3>
                    <ul class="space-y-4 text-sm font-medium text-gray-400">
                        <li>
                            <a href="https://wa.me/6282218335957" target="_blank" class="hover:text-orange-500 transition-colors flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-green-500"><i class="bi bi-whatsapp"></i></div>
                                +62 822 1833 5957
                            </a>
                        </li>
                        <li>
                            <a href="mailto:ptbrycloe@gmail.com" class="hover:text-orange-500 transition-colors flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center text-orange-500"><i class="bi bi-envelope-fill"></i></div>
                                ptbrycloe@gmail.com
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-gray-800 pt-8 mt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-gray-500 text-xs font-medium uppercase tracking-widest text-center md:text-left">
                    &copy; {{ date('Y') }} SUPERMURA.ID by PT BRYCLO MEGA JAYA.<br class="md:hidden"> Hak Cipta Dilindungi Undang-Undang.
                </p>
                <div class="flex gap-4 text-gray-500">
                    <a href="#" class="hover:text-orange-500 transition-colors"><i class="bi bi-instagram text-lg"></i></a>
                    <a href="#" class="hover:text-orange-500 transition-colors"><i class="bi bi-tiktok text-lg"></i></a>
                    <a href="#" class="hover:text-orange-500 transition-colors"><i class="bi bi-facebook text-lg"></i></a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <script>
    function confirmLogout(formId) {
        Swal.fire({
            title: 'Keluar dari Akun?',
            text: 'Anda akan keluar dari sesi ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ea580c',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Keluar',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-3xl' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
    </script>
</body>
</html>