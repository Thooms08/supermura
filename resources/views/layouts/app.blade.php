<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SUPERMURA.ID | Solusi Kuliner & Fashion')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans text-gray-900">

    <nav x-data="{ mobileMenuOpen: false }" class="bg-white border-b sticky top-0 z-50 shadow-sm">
        @auth
            @php
                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
            @endphp
        @endauth

        <div class="max-w-7xl mx-auto px-4 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl font-black text-orange-600 tracking-tighter">
                SUPERMURA<span class="text-gray-800">.ID</span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('affiliate.program') }}" 
                    class="text-sm font-bold transition-all flex items-center {{ request()->routeIs('affiliate.program') ? 'text-orange-600' : 'text-gray-600 hover:text-orange-600' }}">
                    <i class="bi bi- megaphone me-1"></i> Program Affiliate
                </a>

                @auth
                    <a href="{{ route('pengunjung.pesanan') }}" 
                        class="text-sm font-bold transition-all flex items-center {{ request()->routeIs('pengunjung.pesanan') ? 'text-orange-600' : 'text-gray-600 hover:text-orange-600' }}">
                        <i class="bi bi-box-seam me-1"></i> Pesanan
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

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf 
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors">
                            <i class="bi bi-box-arrow-right text-xl"></i>
                        </button>
                    </form>
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

        <div x-show="mobileMenuOpen" class="md:hidden bg-white border-t p-4 space-y-4">
             <a href="{{ route('affiliate.program') }}" class="block font-bold text-gray-600">Program Affiliate</a>
             @auth
                <a href="{{ route('keranjang.index') }}" class="block font-bold text-gray-600">Keranjang</a>
                <a href="{{ route('pengunjung.pesanan') }}" class="block font-bold text-gray-600">Pesanan</a>
             @else
                <a href="{{ route('login') }}" class="block font-bold text-gray-600">Masuk</a>
             @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-12 text-center mt-auto">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-xl font-bold text-orange-500 mb-2">DEVELOPER WEB</h2>
            <p class="text-gray-500 text-sm font-medium">&copy; 2026 Marketplace. All Rights Reserved.</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>