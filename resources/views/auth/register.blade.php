<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengunjung | SUPERMURA.ID</title>
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
    
    <div class="absolute top-[-5%] right-[-5%] w-80 h-80 bg-orange-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>
    <div class="absolute bottom-[0%] left-[-5%] w-80 h-80 bg-orange-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30"></div>

    <div class="w-full max-w-md relative py-8">
        <a href="/" class="inline-flex items-center gap-2 text-orange-600 font-bold text-sm mb-6 hover:gap-3 transition-all">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="glass-effect p-8 md:p-10 rounded-[2.5rem] shadow-2xl shadow-orange-200/50 border border-white">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-black text-gray-900 tracking-tighter uppercase">Daftar <span class="text-orange-600">Akun</span></h1>
                <p class="text-gray-500 text-sm mt-2">Gabung sebagai pengunjung SUPERMURA.ID</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Nama Lengkap</label>
                    <div class="relative">
                        <i class="bi bi-person absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm @error('name') border-red-500 @enderror">
                    </div>
                    @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold px-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Email</label>
                    <div class="relative">
                        <i class="bi bi-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm @error('email') border-red-500 @enderror">
                    </div>
                    @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold px-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Password</label>
                        <div class="relative">
                            <i class="bi bi-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm @error('password') border-red-500 @enderror">
                        </div>
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Konfirmasi Password</label>
                        <div class="relative">
                            <i class="bi bi-shield-check absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="password" name="password_confirmation" required 
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 focus:border-orange-500 outline-none transition-all font-medium text-sm">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full bg-orange-600 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] transition-all mt-4">
                    Daftar Sekarang <i class="bi bi-person-plus ms-2"></i>
                </button>
            </form>

            <p class="mt-8 text-center text-sm font-medium text-gray-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-600 font-bold hover:underline">Masuk</a>
            </p>
        </div>
    </div>
</body>
</html>