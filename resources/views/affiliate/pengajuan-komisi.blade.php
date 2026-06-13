<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cairkan Komisi | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = (window.innerWidth >= 1024)">
    <div class="flex h-screen overflow-hidden">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }" class="transition-all duration-300 transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative">
            <div class="w-64 h-full">@include('affiliate.sidebar')</div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600"><i class="bi bi-list text-2xl"></i></button>
                <h2 class="text-lg font-bold">Cairkan <span class="text-orange-500">Komisi</span></h2>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 flex justify-center items-start">
                <div class="w-full max-w-md bg-white rounded-[2rem] shadow-xl shadow-orange-100 p-8 border border-orange-50">
                    <div class="mb-8 text-center">
                        <div class="w-16 h-16 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-bank2 text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-black text-gray-800">Tarik Dana</h1>
                        <p class="text-gray-400 text-sm">Saldo Tersedia: <span class="text-orange-600 font-bold text-lg">Rp {{ number_format($saldo, 0, ',', '.') }}</span></p>
                    </div>

                    <form action="{{ route('affiliate.komisi.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-2">Nominal Penarikan</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-gray-400">Rp</span>
                                <input type="number" name="nominal" placeholder="Minimal 50.000" class="w-full pl-12 pr-4 py-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 focus:bg-white rounded-2xl outline-none transition-all font-bold text-gray-800" value="{{ old('nominal') }}">
                            </div>
                            @error('nominal') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-2">Nomor Rekening / E-Wallet</label>
                            <input type="text" name="nomor_pembayaran" placeholder="Contoh: BCA - 12345678 a/n Nama" class="w-full px-4 py-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 focus:bg-white rounded-2xl outline-none transition-all text-sm" value="{{ old('nomor_pembayaran') }}">
                            @error('nomor_pembayaran') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl font-bold shadow-lg shadow-orange-200 hover:scale-[1.02] active:scale-95 transition-all">
                            Ajukan Penarikan Sekarang
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>
</html>