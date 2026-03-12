<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | SUPERMURA.ID</title>
     @include('partials.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-effect { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-orange-50 min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">
    
    <div class="absolute top-[-10%] left-[-5%] w-72 h-72 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute bottom-[-10%] right-[-5%] w-72 h-72 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="w-full max-w-md relative">
        <a href="/" class="inline-flex items-center gap-2 text-orange-600 font-bold text-sm mb-6 hover:gap-3 transition-all">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="glass-effect p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-orange-200/50 border border-white">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter">MASUK <span class="text-orange-600">AKUN</span></h1>
                <p class="text-gray-500 text-sm mt-2">Selamat datang kembali di SUPERMURA.ID</p>
            </div>
            
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-xs font-bold flex items-center border border-red-100">
                    <i class="bi bi-exclamation-circle-fill me-2"></i> {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Alamat Email</label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm" 
                               placeholder="email@anda.com">
                    </div>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2 px-1">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Password</label>
                        <a href="#" class="text-[10px] font-bold text-orange-600 uppercase tracking-widest hover:underline">Lupa?</a>
                    </div>
                    <div class="relative">
                        <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="password" name="password" required 
                               class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm" 
                               placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] transition-all">
                    Masuk Sekarang <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>
            </form>

            <div class="my-8 flex items-center gap-4">
                <div class="h-[1px] bg-gray-100 flex-grow"></div>
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Atau</span>
                <div class="h-[1px] bg-gray-100 flex-grow"></div>
            </div>

            <a href="{{ route('google.login') }}" class="flex items-center justify-center gap-3 w-full bg-white border border-gray-100 py-4 rounded-2xl hover:bg-gray-50 transition-all shadow-sm">
                <img src="https://www.svgrepo.com/show/355037/google.svg" class="w-5 h-5">
                <span class="text-sm font-bold text-gray-700">Masuk dengan Google</span>
            </a>

            <p class="mt-8 text-center text-sm font-medium text-gray-500">
                Baru di sini? <a href="{{ route('register') }}" class="text-orange-600 font-bold hover:underline">Buat Akun</a>
            </p>
        </div>
    </div>
</body>
</html>