@extends('layouts.app')
@include('layouts.favicon')

@section('title', 'Program Affiliate | SUPERMURA.ID')

@section('content')
<section class="py-20 bg-orange-50/50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col md:flex-row items-center gap-12 bg-white p-8 md:p-16 rounded-[3rem] shadow-sm border border-orange-100">
            
            <div class="flex-1">
                <span class="inline-block px-4 py-1.5 bg-orange-100 text-orange-600 rounded-full text-xs font-black uppercase tracking-widest mb-6">
                    Join Our Community
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-gray-900 leading-tight mb-6">
                    Program Affiliate <br>
                    <span class="text-orange-600">SUPERMURA.ID</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    Dapatkan komisi dari setiap transaksi yang dilakukan melalui link unik Anda. Kami menangani pengiriman dan stok, Anda cukup berbagi kebahagiaan berbelanja!
                </p>

                <div class="bg-orange-600 text-white p-6 rounded-2xl mb-10 inline-flex items-center gap-6 shadow-xl shadow-orange-200">
                    <div class="w-12 h-12 bg-orange-500 rounded-xl flex items-center justify-center">
                        <i class="bi bi-wallet2 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase opacity-80 tracking-widest">Biaya Registrasi</p>
                        <p class="text-2xl font-black">Rp {{ number_format($biaya, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div>
                    <a href="{{ route('affiliate.register') }}" class="group bg-gray-900 text-white px-10 py-5 rounded-2xl font-black uppercase tracking-widest hover:bg-orange-600 transition-all flex items-center justify-center md:inline-flex shadow-xl shadow-gray-200">
                        Daftar Sekarang
                        <i class="bi bi-arrow-right ms-3 group-hover:translate-x-2 transition-transform"></i>
                    </a>
                </div>
            </div>

            <div class="flex-1 relative">
                <div class="relative z-10 rounded-[2rem] overflow-hidden shadow-2xl rotate-3 hover:rotate-0 transition-transform duration-500">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?auto=format&fit=crop&w=800" alt="Affiliate Program" class="w-full">
                </div>
                <div class="absolute inset-0 bg-orange-200 rounded-[2rem] -rotate-3 z-0"></div>
            </div>

        </div>
    </div>
</section>

<section class="py-20 max-w-7xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-orange-100 transition-shadow">
            <i class="bi bi-lightning-charge-fill text-4xl text-orange-500"></i>
            <h3 class="text-xl font-bold mt-6 mb-3">Proses Cepat</h3>
            <p class="text-gray-500 text-sm">Akun aktif segera setelah verifikasi pembayaran berhasil dilakukan oleh sistem.</p>
        </div>
        <div class="p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-orange-100 transition-shadow">
            <i class="bi bi-graph-up-arrow text-4xl text-orange-500"></i>
            <h3 class="text-xl font-bold mt-6 mb-3">Komisi Tinggi</h3>
            <p class="text-gray-500 text-sm">Nikmati pembagian hasil yang kompetitif untuk setiap produk UMKM yang terjual.</p>
        </div>
        <div class="p-8 bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-orange-100 transition-shadow">
            <i class="bi bi-shield-check text-4xl text-orange-500"></i>
            <h3 class="text-xl font-bold mt-6 mb-3">Terpercaya</h3>
            <p class="text-gray-500 text-sm">Laporan transparan melalui dashboard affiliator yang bisa dipantau secara real-time.</p>
        </div>
    </div>
</section>
@endsection