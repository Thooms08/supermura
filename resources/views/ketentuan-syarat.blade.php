@extends('layouts.app')

@section('title', 'Syarat & Ketentuan | SUPERMURA.ID')

@section('content')
    <header class="bg-white py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[3rem] p-8 md:p-20 text-white relative overflow-hidden shadow-2xl shadow-orange-200">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto text-center">
                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-black tracking-[0.2em] uppercase px-5 py-2 rounded-full mb-6 border border-white/10 shadow-sm">
                        Informasi Legal
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black leading-tight mb-6 uppercase tracking-tighter">
                        Syarat & Ketentuan
                    </h1>
                    <p class="text-orange-50 text-base md:text-lg font-medium opacity-90">
                        Terakhir diperbarui: <span class="font-bold">Juni 2026</span>
                    </p>
                </div>
                
                <i class="bi bi-shield-check absolute -left-10 -bottom-10 text-[18rem] text-white/10 -rotate-12 hidden lg:block"></i>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-4 md:py-8 mb-20">
        <div class="bg-white rounded-[3rem] p-8 md:p-14 shadow-2xl shadow-gray-100/50 border border-gray-100 relative">
            
            <div class="mb-12 pb-8 border-b border-gray-100 text-center md:text-left">
                <p class="text-gray-600 font-medium leading-relaxed text-lg">
                    Selamat datang di <span class="font-black text-orange-600">SUPERMURA.ID</span>. Dengan mengakses, menggunakan, atau melakukan transaksi melalui platform kami, Anda dianggap telah membaca, memahami, dan menyetujui seluruh Ketentuan dan Syarat yang berlaku di halaman ini.
                </p>
            </div>

            <div class="space-y-12">
                
                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            1
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Ketentuan Umum</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>SUPERMURA.ID adalah platform e-commerce yang menyediakan berbagai produk fashion berkualitas dengan harga terjangkau. Seluruh pengguna wajib menggunakan layanan ini secara sah dan sesuai dengan peraturan perundang-undangan yang berlaku di Indonesia.</p>
                            <p>Dengan menggunakan layanan SUPERMURA.ID, pengguna menyatakan bahwa data yang diberikan adalah benar, lengkap, dan dapat dipertanggungjawabkan.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            2
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Produk dan Ketersediaan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>Kami berupaya menampilkan informasi produk secara akurat, termasuk deskripsi, ukuran, warna, harga, dan ketersediaan stok.</p>
                            <p>Namun demikian, perbedaan warna produk dapat terjadi karena pengaturan layar perangkat masing-masing pengguna. SUPERMURA.ID berhak memperbarui informasi produk, harga, dan stok tanpa pemberitahuan sebelumnya.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            3
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Harga & Biaya Pengiriman</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-4">
                            <p>Seluruh harga yang tercantum pada website dinyatakan dalam Rupiah (IDR).</p>
                            <div class="bg-green-50 border border-green-100 rounded-2xl p-5">
                                <h3 class="text-green-700 font-bold flex items-center gap-2 mb-2">
                                    <i class="bi bi-truck"></i> Gratis Ongkir
                                </h3>
                                <p class="text-green-800 text-sm">
                                    SUPERMURA.ID memberikan gratis ongkos kirim (Gratis Ongkir) untuk seluruh produk yang tersedia di platform kami, kecuali jika terdapat ketentuan khusus yang diinformasikan secara terpisah pada halaman produk atau promosi tertentu.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            4
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pembayaran</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>Seluruh proses pembayaran pada SUPERMURA.ID diproses melalui penyedia layanan pembayaran pihak ketiga yang terpercaya, yaitu <span class="font-bold text-gray-800">Mayar.id</span>.</p>
                            <p>Dengan melakukan pembayaran, pengguna juga tunduk pada syarat dan ketentuan yang diberlakukan oleh Mayar.id sebagai penyedia layanan pembayaran. SUPERMURA.ID tidak menyimpan data kartu kredit, rekening bank, maupun informasi pembayaran sensitif milik pengguna.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            5
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pemrosesan & Pengiriman Pesanan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>Pesanan akan diproses setelah pembayaran berhasil diverifikasi. Waktu pemrosesan pesanan dapat bervariasi tergantung pada ketersediaan produk, volume pesanan, dan kondisi operasional.</p>
                            <p>Setelah pesanan diserahkan kepada jasa pengiriman, pengguna akan menerima informasi pengiriman yang dapat digunakan untuk memantau status paket.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            6
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pelacakan Paket</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>Untuk memudahkan pelanggan dalam memantau status pengiriman, SUPERMURA.ID menyediakan akses pelacakan paket melalui layanan pihak ketiga, yaitu <span class="font-bold text-gray-800">17TRACK</span>.</p>
                            <p>Informasi status pengiriman yang ditampilkan berasal dari sistem 17TRACK dan perusahaan logistik terkait. Oleh karena itu, SUPERMURA.ID tidak bertanggung jawab atas keterlambatan pembaruan data pelacakan yang disebabkan oleh pihak ketiga.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            7
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pembatalan Pesanan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed space-y-3">
                            <p>Pelanggan dapat mengajukan pembatalan pesanan selama status pesanan masih berada dalam tahap:</p>
                            <ul class="space-y-2 mt-2">
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-orange-500"></i> Menunggu Pembayaran
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-orange-500"></i> Menunggu Verifikasi
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-orange-500"></i> Sedang Diproses
                                </li>
                            </ul>
                            <div class="bg-red-50 border-l-4 border-red-500 p-4 mt-4 rounded-r-xl text-red-700 text-sm">
                                Setelah pesanan memasuki tahap pengiriman atau telah diserahkan kepada jasa ekspedisi, pembatalan pesanan tidak dapat dilakukan.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            8
                        </div>
                    </div>
                    <div class="w-full">
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-4 mt-1">Pengembalian Dana (Refund)</h2>
                        <p class="text-gray-600 font-medium mb-4">SUPERMURA.ID menerapkan kebijakan refund sebagai berikut:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                                <h3 class="font-bold text-green-600 flex items-center gap-2 mb-4">
                                    <i class="bi bi-check-circle-fill"></i> Refund Dapat Dilakukan Jika:
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600 font-medium">
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check text-green-500 text-lg leading-none"></i>
                                        <span>Pesanan masih dalam tahap pemrosesan dan belum dikirim.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check text-green-500 text-lg leading-none"></i>
                                        <span>Terjadi kegagalan sistem yang menyebabkan transaksi tidak dapat diproses.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check text-green-500 text-lg leading-none"></i>
                                        <span>Produk yang dipesan tidak tersedia sehingga pesanan tidak dapat dipenuhi.</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 shadow-sm">
                                <h3 class="font-bold text-red-500 flex items-center gap-2 mb-4">
                                    <i class="bi bi-x-circle-fill"></i> Refund Tidak Dapat Dilakukan Jika:
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600 font-medium">
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-x text-red-500 text-lg leading-none"></i>
                                        <span>Pesanan telah dikirim kepada jasa ekspedisi.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-x text-red-500 text-lg leading-none"></i>
                                        <span>Paket sedang dalam perjalanan menuju alamat penerima.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-x text-red-500 text-lg leading-none"></i>
                                        <span>Paket telah diterima oleh pelanggan.</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-x text-red-500 text-lg leading-none"></i>
                                        <span>Kesalahan pembelian akibat kelalaian pelanggan (kesalahan ukuran, warna, atau alamat).</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 font-medium italic mt-4">
                            * Dengan melakukan pembelian, pelanggan memahami dan menyetujui bahwa pesanan yang telah dikirim tidak dapat dibatalkan maupun direfund.
                        </p>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            9
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Tanggung Jawab Pengguna</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Pengguna bertanggung jawab untuk:</p>
                            <ul class="space-y-2">
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-gray-400"></i> Memberikan data yang benar dan akurat.
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-gray-400"></i> Menjaga kerahasiaan akun dan informasi pribadi.
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-gray-400"></i> Tidak menggunakan website untuk aktivitas yang melanggar hukum.
                                </li>
                                <li class="flex items-center gap-3">
                                    <i class="bi bi-circle-fill text-[8px] text-gray-400"></i> Tidak melakukan tindakan yang dapat mengganggu sistem atau keamanan platform.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            10
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Batasan Tanggung Jawab</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">SUPERMURA.ID tidak bertanggung jawab atas:</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-3">
                                    <i class="bi bi-dash text-orange-500 mt-1"></i> Keterlambatan pengiriman akibat kondisi cuaca, bencana alam, atau kendala operasional pihak logistik.
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="bi bi-dash text-orange-500 mt-1"></i> Kerugian yang timbul akibat kesalahan data yang diberikan oleh pelanggan.
                                </li>
                                <li class="flex items-start gap-3">
                                    <i class="bi bi-dash text-orange-500 mt-1"></i> Gangguan layanan yang disebabkan oleh pihak ketiga di luar kendali SUPERMURA.ID.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            11
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Perubahan Ketentuan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p>SUPERMURA.ID berhak mengubah, memperbarui, atau menyesuaikan Ketentuan dan Syarat ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu tersedia pada halaman ini dan berlaku sejak tanggal publikasi.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            12
                        </div>
                    </div>
                    <div class="w-full">
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Kontak</h2>
                        <p class="text-gray-600 font-medium leading-relaxed mb-6">Apabila Anda memiliki pertanyaan mengenai Ketentuan dan Syarat ini, silakan menghubungi kami:</p>
                        
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 md:w-max">
                            <p class="font-bold text-gray-800 mb-4">PT BRYCLO MEGA JAYA</p>
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 text-gray-600 font-medium">
                                    <i class="bi bi-geo-alt-fill text-orange-500"></i> Bandung, Jawa Barat, Indonesia
                                </div>
                                <div class="flex items-center gap-3 text-gray-600 font-medium">
                                    <i class="bi bi-whatsapp text-green-500"></i> 
                                    <a href="https://wa.me/6282218335957" target="_blank" class="hover:text-orange-600 transition-colors">+62 822 1833 5957</a>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600 font-medium">
                                    <i class="bi bi-envelope-fill text-orange-500"></i>
                                    <a href="mailto:bryclomegajaya@gmail.com" class="hover:text-orange-600 transition-colors">bryclomegajaya@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 text-center bg-orange-50 p-6 rounded-2xl">
                <p class="text-orange-800 font-semibold">
                    Dengan menggunakan layanan SUPERMURA.ID, Anda menyatakan telah membaca, memahami, dan menyetujui seluruh Ketentuan dan Syarat yang tercantum pada halaman ini.
                </p>
            </div>

        </div>
    </main>
@endsection