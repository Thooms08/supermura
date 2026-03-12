<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil | SUPERMURA.ID</title>
    @include('partials.favicon')
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .success-animation {
            animation: scaleIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes scaleIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .floating {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full success-animation">
        <div class="bg-white rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden relative">
            
            <div class="h-2 bg-orange-500 w-full"></div>

            <div class="p-8 md:p-10 text-center">
                <div class="relative mb-6 flex justify-center">
                    <div class="w-24 h-24 bg-green-50 rounded-full flex items-center justify-center floating">
                        <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-200">
                            <i class="bi bi-check-lg text-white text-4xl"></i>
                        </div>
                    </div>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 mb-2 tracking-tight">Yess! Berhasil.</h1>
                <p class="text-gray-500 font-medium mb-8 leading-relaxed">
                    Pembayaran Anda telah kami terima. Pesanan akan segera diproses oleh toko.
                </p>

                <div class="bg-gray-50 rounded-3xl p-6 mb-8 border border-gray-100">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nomor Pesanan</span>
                            <span class="text-sm font-bold text-gray-800 bg-white px-3 py-1 rounded-full shadow-sm border border-gray-100">
                                #{{ $pesanan->nomor_pesanan }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-dashed border-gray-200">
                            <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Pembayaran</span>
                            <span class="text-xl font-black text-orange-600">
                                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <a href="/" class="group relative flex items-center justify-center gap-3 w-full bg-orange-500 hover:bg-orange-600 text-white font-extrabold py-5 px-6 rounded-2xl shadow-xl shadow-orange-100 transition-all duration-300 active:scale-[0.98]">
                        <span>KEMBALI BERBELANJA</span>
                        <i class="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </a>
                    
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-tighter">
                        Konfirmasi pesanan dikirim otomatis ke WhatsApp/Email Anda
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-gray-400 font-bold text-sm tracking-widest uppercase">
                <span class="text-orange-500">SUPERMURA</span>.ID
            </p>
        </div>
    </div>

</body>
</html>