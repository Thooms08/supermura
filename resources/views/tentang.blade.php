@extends('layouts.app')

@section('title', 'Tentang Kami | SUPERMURA.ID')

@section('content')
    <header class="bg-white py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[3rem] p-8 md:p-20 text-white relative overflow-hidden shadow-2xl shadow-orange-200">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto text-center">
                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-black tracking-[0.2em] uppercase px-5 py-2 rounded-full mb-6 border border-white/10 shadow-sm">
                        Tentang Kami
                    </span>
                    <h1 class="text-4xl md:text-6xl font-black leading-tight mb-6 uppercase tracking-tighter">
                        SUPERMURA<span class="text-orange-200">.ID</span>
                    </h1>
                    <p class="text-orange-50 text-lg md:text-xl font-medium opacity-90 leading-relaxed">
                        Platform e-commerce fashion terpercaya dengan produk berkualitas dan harga yang terjangkau.
                    </p>
                </div>
                
                <i class="bi bi-info-circle absolute -left-10 -bottom-10 text-[18rem] text-white/10 -rotate-12 hidden lg:block"></i>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 md:py-12 space-y-20">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tighter uppercase">Siapa Kami?</h2>
                    <div class="h-1.5 w-20 bg-orange-500 rounded-full mt-4"></div>
                </div>
                <div class="text-gray-600 leading-relaxed text-lg space-y-4 font-medium">
                    <p>
                        Di era digital yang semakin berkembang, kebutuhan masyarakat terhadap produk fashion berkualitas terus meningkat. Namun, tidak semua orang ingin mengeluarkan biaya besar untuk mendapatkan pakaian yang nyaman, stylish, dan mengikuti tren terkini.
                    </p>
                    <p>
                        Inilah yang menjadi alasan hadirnya <span class="font-black text-orange-600 tracking-tight">SUPERMURA.ID</span>, platform e-commerce fashion yang menyediakan berbagai pilihan produk berkualitas dengan harga yang terjangkau.
                    </p>
                    <div class="bg-orange-50 p-6 rounded-[2rem] border border-orange-100 mt-6">
                        <p class="text-gray-700">
                            Kami berfokus pada penjualan produk fashion untuk berbagai kalangan. Dengan mengutamakan kualitas produk, kenyamanan pelanggan, serta harga yang ramah di kantong, SUPERMURA.ID berkomitmen menjadi solusi belanja online yang terpercaya bagi masyarakat Indonesia.
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5 bg-white rounded-[3rem] p-10 shadow-2xl shadow-gray-100 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-orange-50 rounded-full -mr-10 -mt-10"></div>
                <div class="w-16 h-16 bg-orange-100 rounded-2xl flex items-center justify-center text-orange-600 mb-6 font-black text-2xl">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h3 class="text-2xl font-black text-gray-800 tracking-tight uppercase mb-4">Komitmen Kami</h3>
                <p class="text-gray-600 font-medium leading-relaxed">
                    SUPERMURA.ID percaya bahwa setiap orang berhak mendapatkan produk fashion berkualitas dengan harga yang terjangkau. Oleh karena itu, kami terus berupaya menghadirkan produk terbaik, meningkatkan kualitas layanan, serta memberikan pengalaman berbelanja online yang memuaskan bagi seluruh pelanggan.
                </p>
            </div>
        </div>

        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <h2 class="text-3xl md:text-4xl font-black text-gray-800 tracking-tighter uppercase mb-4">Mengapa Memilih Kami?</h2>
                <div class="h-1.5 w-20 bg-orange-500 rounded-full mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-100/50 border border-gray-50 hover:border-orange-200 transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-orange-50 group-hover:bg-orange-500 group-hover:text-white transition-colors rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6">
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-3">Produk Berkualitas</h3>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed">
                        Bahan, jahitan, hingga desain dipilih dengan standar tinggi untuk memberikan kenyamanan maksimal.
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-100/50 border border-gray-50 hover:border-orange-200 transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-orange-50 group-hover:bg-orange-500 group-hover:text-white transition-colors rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6">
                        <i class="bi bi-tags-fill"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-3">Harga Terjangkau</h3>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed">
                        Tampil percaya diri dengan gaya masa kini tanpa perlu mengeluarkan biaya yang berlebihan.
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-100/50 border border-gray-50 hover:border-orange-200 transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-orange-50 group-hover:bg-orange-500 group-hover:text-white transition-colors rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-3">Tren Terkini</h3>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed">
                        Koleksi fashion kami selalu update mengikuti perkembangan gaya dan tren paling modern.
                    </p>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-100/50 border border-gray-50 hover:border-orange-200 transition-all duration-300 hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-orange-50 group-hover:bg-orange-500 group-hover:text-white transition-colors rounded-2xl flex items-center justify-center text-orange-500 text-2xl mb-6">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 uppercase tracking-tight mb-3">Mudah & Aman</h3>
                    <p class="text-gray-500 font-medium text-sm leading-relaxed">
                        Sistem belanja e-commerce yang praktis. Proses transaksi dirancang cepat, mudah, dan sangat aman.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-gray-900 rounded-[3rem] overflow-hidden shadow-2xl relative">
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-orange-600/20 rounded-full blur-3xl"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12">
                <div class="lg:col-span-5 bg-gradient-to-br from-orange-500 to-orange-600 p-10 md:p-16 flex flex-col justify-center">
                    <span class="text-orange-100 text-xs font-black uppercase tracking-[0.2em] mb-4">Arah Masa Depan</span>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter uppercase mb-6">Visi Kami</h2>
                    <p class="text-lg text-white font-medium leading-relaxed">
                        "Menjadi platform e-commerce fashion terpercaya di Indonesia yang menghadirkan produk berkualitas dengan harga terjangkau untuk semua kalangan."
                    </p>
                </div>

                <div class="lg:col-span-7 p-10 md:p-16 flex flex-col justify-center relative z-10">
                    <span class="text-orange-500 text-xs font-black uppercase tracking-[0.2em] mb-4">Langkah Nyata</span>
                    <h2 class="text-3xl md:text-4xl font-black text-white tracking-tighter uppercase mb-8">Misi Kami</h2>
                    
                    <ul class="space-y-5">
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 text-orange-500 flex items-center justify-center font-black text-sm shrink-0">1</div>
                            <span class="text-gray-300 font-medium text-lg">Menyediakan produk fashion berkualitas dengan harga terbaik.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 text-orange-500 flex items-center justify-center font-black text-sm shrink-0">2</div>
                            <span class="text-gray-300 font-medium text-lg">Memberikan pengalaman belanja online yang mudah, aman, dan nyaman.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 text-orange-500 flex items-center justify-center font-black text-sm shrink-0">3</div>
                            <span class="text-gray-300 font-medium text-lg">Menghadirkan koleksi fashion yang selalu mengikuti tren terkini.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 text-orange-500 flex items-center justify-center font-black text-sm shrink-0">4</div>
                            <span class="text-gray-300 font-medium text-lg">Mengutamakan kepuasan pelanggan melalui pelayanan yang profesional.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-8 h-8 rounded-full bg-gray-800 text-orange-500 flex items-center justify-center font-black text-sm shrink-0">5</div>
                            <span class="text-gray-300 font-medium text-lg">Mendukung gaya hidup masyarakat Indonesia dengan produk yang terjangkau.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch pb-12">
            <div class="bg-white rounded-[3rem] p-10 md:p-14 border border-gray-100 shadow-2xl shadow-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tighter uppercase mb-6">Profil Perusahaan</h2>
                    <div class="inline-block px-4 py-2 bg-orange-50 text-orange-600 rounded-xl text-sm font-black uppercase tracking-widest border border-orange-100 mb-6">
                        PT BRYCLO MEGA JAYA
                    </div>
                    <p class="text-gray-600 font-medium leading-relaxed">
                        SUPERMURA.ID dimiliki dan dikelola oleh PT BRYCLO MEGA JAYA, sebuah perusahaan yang berlokasi di Bandung, Jawa Barat. Dengan semangat inovasi dan pelayanan terbaik, kami berkomitmen untuk terus mengembangkan layanan e-commerce yang dapat menjangkau pelanggan di seluruh Indonesia.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-[3rem] p-10 md:p-14 border border-gray-100 shadow-2xl shadow-gray-100 flex flex-col justify-between">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tighter uppercase mb-8">Hubungi Kami</h2>
                    
                    <div class="space-y-6">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center text-xl text-gray-600">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Lokasi Pusat</p>
                                <p class="font-bold text-gray-800">Bandung, Jawa Barat, Indonesia</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-green-50 flex items-center justify-center text-xl text-green-600">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Telepon / WhatsApp</p>
                                <a href="https://wa.me/6282218335957" target="_blank" class="font-bold text-gray-800 hover:text-orange-500 transition-colors">+62 822 1833 5957</a>
                            </div>
                        </div>

                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-orange-50 flex items-center justify-center text-xl text-orange-600">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 font-black uppercase tracking-widest mb-1">Email Resmi</p>
                                <a href="mailto:bryclomegajaya@gmail.com" class="font-bold text-gray-800 hover:text-orange-500 transition-colors">bryclomegajaya@gmail.com</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection