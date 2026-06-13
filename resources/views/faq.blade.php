@extends('layouts.app')

@section('title', 'FAQ - Pusat Bantuan | SUPERMURA.ID')

@section('content')
    <!-- HEADER SECTION -->
    <header class="bg-white py-10 md:py-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-[3rem] p-8 md:p-20 text-white relative overflow-hidden shadow-2xl shadow-orange-200">
                <!-- Efek Blur Dekoratif -->
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-48 h-48 bg-black/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 max-w-3xl mx-auto text-center">
                    <span class="inline-block bg-white/20 backdrop-blur-md text-white text-xs font-black tracking-[0.2em] uppercase px-5 py-2 rounded-full mb-6 border border-white/10 shadow-sm">
                        Pusat Bantuan
                    </span>
                    <h1 class="text-3xl md:text-5xl font-black leading-tight mb-6 uppercase tracking-tighter">
                        FAQ (Frequently Asked Questions)
                    </h1>
                    <p class="text-orange-50 text-base md:text-lg font-medium opacity-90 leading-relaxed">
                        Pertanyaan yang Sering Diajukan. Halaman ini berisi jawaban atas pertanyaan yang paling sering ditanyakan oleh pelanggan SUPERMURA.ID.
                    </p>
                </div>
                
                <!-- Ikon Dekoratif -->
                <i class="bi bi-question-circle absolute -left-10 -bottom-10 text-[18rem] text-white/10 -rotate-12 hidden lg:block"></i>
            </div>
        </div>
    </header>

    <!-- MAIN CONTENT -->
    <main class="max-w-4xl mx-auto px-4 py-4 md:py-8 mb-20">
        
        <!-- Accordion FAQ List -->
        <div class="space-y-4">

            <!-- FAQ 1 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">1. Apa itu SUPERMURA.ID?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    SUPERMURA.ID adalah platform e-commerce fashion yang menyediakan berbagai produk fashion berkualitas dengan harga terjangkau untuk pelanggan di seluruh Indonesia.
                </div>
            </details>

            <!-- FAQ 2 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">2. Apakah semua produk di SUPERMURA.ID original?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Kami berkomitmen menyediakan produk fashion berkualitas sesuai dengan deskripsi yang tercantum pada halaman produk. Setiap produk telah melalui proses seleksi sebelum dipasarkan kepada pelanggan.
                </div>
            </details>

            <!-- FAQ 3 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">3. Apakah ada biaya ongkos kirim?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <span class="font-bold text-green-600">Tidak.</span> Seluruh produk yang dijual di SUPERMURA.ID mendapatkan <span class="font-bold">Gratis Ongkir</span> sesuai dengan ketentuan yang berlaku di platform kami.
                </div>
            </details>

            <!-- FAQ 4 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">4. Metode pembayaran apa saja yang tersedia?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Pembayaran pesanan diproses melalui platform pembayaran pihak ketiga yang aman dan terpercaya, yaitu <span class="font-bold text-gray-800">Mayar.id</span>. Metode pembayaran yang tersedia dapat berbeda tergantung layanan yang disediakan oleh Mayar.id.
                </div>
            </details>

            <!-- FAQ 5 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">5. Kapan pesanan saya diproses?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Pesanan akan diproses setelah pembayaran berhasil diverifikasi. Waktu pemrosesan dapat berbeda tergantung jumlah pesanan dan ketersediaan produk.
                </div>
            </details>

            <!-- FAQ 6 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">6. Berapa lama pengiriman pesanan?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Estimasi pengiriman bergantung pada lokasi tujuan dan layanan ekspedisi yang digunakan. Setelah pesanan dikirim, pelanggan akan menerima nomor resi untuk melakukan pelacakan paket.
                </div>
            </details>

            <!-- FAQ 7 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">7. Bagaimana cara melacak pesanan saya?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <p>Anda dapat melacak status pengiriman menggunakan nomor resi melalui layanan pelacakan pihak ketiga <span class="font-bold text-gray-800">17TRACK</span>.</p>
                    <p class="mt-2">Status pengiriman akan diperbarui secara otomatis sesuai informasi yang diberikan oleh perusahaan logistik terkait.</p>
                </div>
            </details>

            <!-- FAQ 8 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">8. Apakah saya bisa membatalkan pesanan?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <p class="mb-2"><span class="font-bold text-gray-800">Ya.</span> Pembatalan pesanan hanya dapat dilakukan selama pesanan masih berada pada tahap:</p>
                    <ul class="space-y-1 mb-3">
                        <li class="flex items-center gap-2"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Menunggu Pembayaran</li>
                        <li class="flex items-center gap-2"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Menunggu Verifikasi</li>
                        <li class="flex items-center gap-2"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Sedang Diproses</li>
                    </ul>
                    <div class="bg-red-50 text-red-700 p-3 rounded-xl text-sm">
                        Jika pesanan sudah dikirim, pembatalan tidak dapat dilakukan.
                    </div>
                </div>
            </details>

            <!-- FAQ 9 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">9. Apakah saya bisa meminta refund?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <p class="mb-2">Refund dapat dilakukan apabila:</p>
                    <ul class="space-y-2">
                        <li class="flex items-start gap-3"><i class="bi bi-check text-green-500 text-xl leading-none"></i> Pesanan belum dikirim.</li>
                        <li class="flex items-start gap-3"><i class="bi bi-check text-green-500 text-xl leading-none"></i> Terjadi kesalahan sistem yang menyebabkan pesanan tidak dapat diproses.</li>
                        <li class="flex items-start gap-3"><i class="bi bi-check text-green-500 text-xl leading-none"></i> Produk tidak tersedia sehingga pesanan tidak dapat dipenuhi.</li>
                    </ul>
                </div>
            </details>

            <!-- FAQ 10 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">10. Apakah pesanan yang sudah dikirim bisa direfund?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed space-y-3">
                    <p class="font-bold text-red-600 text-xl">Tidak.</p>
                    <p>Pesanan yang sudah diserahkan kepada jasa ekspedisi, sedang dalam perjalanan, atau telah diterima oleh pelanggan tidak dapat dibatalkan maupun direfund.</p>
                    <p class="text-sm italic text-gray-500">* Kami menyarankan pelanggan untuk memastikan ukuran, warna, jumlah pesanan, dan alamat pengiriman sebelum menyelesaikan transaksi.</p>
                </div>
            </details>

            <!-- FAQ 11 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">11. Saya salah memasukkan alamat pengiriman. Apa yang harus dilakukan?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Segera hubungi layanan pelanggan kami sebelum pesanan dikirim. Jika pesanan masih dalam tahap pemrosesan, kami akan berusaha membantu melakukan perubahan data pengiriman.
                </div>
            </details>

            <!-- FAQ 12 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">12. Apakah saya perlu membuat akun untuk berbelanja?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Kebijakan mengenai akun pengguna dapat berubah sesuai pengembangan platform. Silakan ikuti petunjuk yang tersedia saat melakukan pemesanan.
                </div>
            </details>

            <!-- FAQ 13 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">13. Mengapa pembayaran saya belum terverifikasi?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    Beberapa metode pembayaran memerlukan waktu verifikasi tertentu. Jika pembayaran berhasil namun status pesanan belum berubah dalam waktu yang wajar, silakan hubungi tim layanan pelanggan dengan menyertakan bukti pembayaran.
                </div>
            </details>

            <!-- FAQ 14 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">14. Apakah data pribadi saya aman?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <span class="font-bold text-gray-800">Ya.</span> Kami berkomitmen menjaga keamanan informasi pelanggan dan hanya menggunakan data yang diperlukan untuk memproses pesanan, pengiriman, serta layanan pelanggan.
                </div>
            </details>

            <!-- FAQ 15 -->
            <details class="group bg-white rounded-2xl border border-gray-100 shadow-sm p-6 open:ring-2 open:ring-orange-500 open:shadow-md transition-all duration-300">
                <summary class="flex justify-between items-center font-black text-gray-800 text-lg cursor-pointer list-none uppercase tracking-tight">
                    <span class="pr-6">15. Bagaimana cara menghubungi SUPERMURA.ID?</span>
                    <span class="transition group-open:rotate-180 bg-orange-50 text-orange-500 w-8 h-8 rounded-full flex items-center justify-center shrink-0">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </summary>
                <div class="text-gray-600 font-medium mt-4 pt-4 border-t border-gray-100 leading-relaxed">
                    <p class="mb-4">Anda dapat menghubungi kami melalui:</p>
                    <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 mb-4 inline-block w-full md:w-auto">
                        <p class="font-bold text-gray-800 mb-3">PT BRYCLO MEGA JAYA</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <i class="bi bi-geo-alt-fill text-orange-500"></i> Bandung, Jawa Barat, Indonesia
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="bi bi-whatsapp text-green-500"></i> 
                                <a href="https://wa.me/6282218335957" target="_blank" class="hover:text-orange-600 transition-colors">+62 822 1833 5957</a>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="bi bi-envelope-fill text-orange-500"></i>
                                <a href="mailto:ptbrycloe@gmail.com" class="hover:text-orange-600 transition-colors">ptbrycloe@gmail.com</a>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm">Tim kami akan berusaha memberikan bantuan secepat mungkin pada jam operasional yang berlaku.</p>
                </div>
            </details>

        </div>

        <!-- Footer / Contact CTA -->
        <div class="mt-16 bg-gray-900 rounded-[3rem] p-10 md:p-14 text-center relative overflow-hidden shadow-2xl">
            <!-- Dekorasi -->
            <div class="absolute -top-20 -left-20 w-48 h-48 bg-orange-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-48 h-48 bg-orange-600/30 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="w-16 h-16 bg-gray-800 text-orange-500 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">
                    <i class="bi bi-headset"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-white uppercase tracking-tighter mb-4">Masih Memiliki Pertanyaan?</h2>
                <p class="text-gray-300 font-medium leading-relaxed max-w-2xl mx-auto mb-8">
                    Jika pertanyaan Anda tidak ditemukan pada halaman FAQ ini, jangan ragu untuk menghubungi tim layanan pelanggan SUPERMURA.ID. Kami siap membantu Anda mendapatkan pengalaman berbelanja yang nyaman, aman, dan menyenangkan.
                </p>
                <a href="https://wa.me/6282218335957" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold px-8 py-4 rounded-xl hover:-translate-y-1 hover:shadow-lg hover:shadow-orange-500/30 transition-all duration-300">
                    <i class="bi bi-whatsapp"></i> Hubungi Customer Service
                </a>
            </div>
        </div>

    </main>

    <!-- Hapus class default list (marker) pada summary browser berbasis webkit -->
    <style>
        details > summary {
            list-style: none;
        }
        details > summary::-webkit-details-marker {
            display: none;
        }
    </style>
@endsection