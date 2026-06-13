@extends('layouts.app')

@section('title', 'Kebijakan Privasi & Keamanan | SUPERMURA.ID')

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
                        Kebijakan Keamanan & Privasi
                    </h1>
                    <p class="text-orange-50 text-base md:text-lg font-medium opacity-90">
                        Terakhir diperbarui: <span class="font-bold">Juni 2026</span>
                    </p>
                </div>
                
                <i class="bi bi-shield-lock absolute -left-10 -bottom-10 text-[18rem] text-white/10 -rotate-12 hidden lg:block"></i>
            </div>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 py-4 md:py-8 mb-20">
        <div class="bg-white rounded-[3rem] p-8 md:p-14 shadow-2xl shadow-gray-100/50 border border-gray-100 relative">
            
            <div class="mb-12 pb-8 border-b border-gray-100 text-center md:text-left">
                <p class="text-gray-600 font-medium leading-relaxed text-lg mb-4">
                    <span class="font-black text-orange-600">SUPERMURA.ID</span> menghargai dan melindungi privasi setiap pelanggan yang menggunakan layanan kami. Kebijakan Keamanan dan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi informasi yang Anda berikan saat menggunakan platform kami.
                </p>
                <div class="bg-orange-50 text-orange-800 p-4 rounded-2xl text-sm font-semibold inline-block">
                    Dengan mengakses atau menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui kebijakan yang tercantum pada halaman ini.
                </div>
            </div>

            <div class="space-y-12">
                
                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            1
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Informasi yang Kami Kumpulkan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-4">Untuk memberikan layanan yang optimal, kami dapat mengumpulkan beberapa informasi yang diberikan secara langsung oleh pengguna, termasuk namun tidak terbatas pada:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                                <div class="flex items-center gap-3"><i class="bi bi-person-badge text-orange-500"></i> Nama lengkap</div>
                                <div class="flex items-center gap-3"><i class="bi bi-telephone text-orange-500"></i> Nomor telepon</div>
                                <div class="flex items-center gap-3"><i class="bi bi-envelope text-orange-500"></i> Alamat email</div>
                                <div class="flex items-center gap-3"><i class="bi bi-geo-alt text-orange-500"></i> Alamat pengiriman</div>
                                <div class="flex items-center gap-3"><i class="bi bi-receipt text-orange-500"></i> Informasi transaksi</div>
                                <div class="flex items-center gap-3"><i class="bi bi-person-circle text-orange-500"></i> Data akun pengguna</div>
                            </div>
                            <p class="text-sm italic text-gray-500">* Serta informasi lain yang diperlukan untuk memproses pesanan. Kami hanya mengumpulkan data yang relevan dan diperlukan.</p>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Penggunaan Informasi</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Informasi yang dikumpulkan digunakan untuk:</p>
                            <ul class="space-y-2 mb-4">
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Memproses pesanan pelanggan</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Mengirimkan produk ke alamat tujuan</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Memberikan informasi terkait status pesanan</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Menyediakan layanan pelanggan</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Mengelola akun pengguna</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Meningkatkan kualitas layanan dan pengalaman pengguna</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Mencegah aktivitas penipuan dan penyalahgunaan sistem</li>
                                <li class="flex items-start gap-3"><i class="bi bi-check2 text-green-500 text-xl leading-none"></i> Memenuhi kewajiban hukum yang berlaku</li>
                            </ul>
                            <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-xl text-gray-700 text-sm">
                                Kami <span class="font-bold">tidak menjual, menyewakan, atau memperdagangkan</span> data pribadi pelanggan kepada pihak lain.
                            </div>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Keamanan Data</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">SUPERMURA.ID menerapkan langkah-langkah keamanan yang wajar untuk melindungi informasi pelanggan dari:</p>
                            <div class="flex flex-wrap gap-2 mb-4">
                                <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm border border-gray-200">Akses tanpa izin</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm border border-gray-200">Penggunaan yang tidak sah</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm border border-gray-200">Perubahan data</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm border border-gray-200">Kehilangan data</span>
                                <span class="bg-gray-100 px-3 py-1 rounded-lg text-sm border border-gray-200">Penyalahgunaan informasi</span>
                            </div>
                            <p>Meskipun kami berupaya menjaga keamanan sistem, tidak ada metode transmisi data melalui internet yang dapat dijamin 100% aman. Oleh karena itu, pengguna juga bertanggung jawab menjaga kerahasiaan akun dan informasi login mereka.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            4
                        </div>
                    </div>
                    <div class="w-full">
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Keamanan Transaksi Pembayaran</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-4">Untuk memberikan pengalaman transaksi yang aman dan nyaman, seluruh proses pembayaran di SUPERMURA.ID diproses melalui penyedia layanan pembayaran pihak ketiga yang terpercaya, yaitu <span class="font-bold text-gray-800">Mayar.id</span>.</p>
                            
                            <div class="bg-green-50 border border-green-100 rounded-2xl p-5 md:p-6 shadow-sm">
                                <h3 class="text-green-800 font-bold mb-3 flex items-center gap-2">
                                    <i class="bi bi-shield-fill-check"></i> Data Anda Aman
                                </h3>
                                <p class="text-sm text-green-700 mb-3">SUPERMURA.ID <span class="font-bold">TIDAK MENYIMPAN</span> informasi sensitif terkait pembayaran seperti:</p>
                                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm text-green-800">
                                    <li class="flex items-center gap-2"><i class="bi bi-x-circle text-red-500"></i> Nomor kartu kredit</li>
                                    <li class="flex items-center gap-2"><i class="bi bi-x-circle text-red-500"></i> Kode CVV</li>
                                    <li class="flex items-center gap-2"><i class="bi bi-x-circle text-red-500"></i> PIN rekening</li>
                                    <li class="flex items-center gap-2"><i class="bi bi-x-circle text-red-500"></i> Kata sandi layanan perbankan</li>
                                </ul>
                                <p class="text-xs mt-4 text-green-600 italic">Informasi pembayaran diproses langsung melalui sistem keamanan yang dimiliki oleh penyedia layanan pembayaran.</p>
                            </div>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Penggunaan Cookies</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Website SUPERMURA.ID dapat menggunakan <i>cookies</i> dan teknologi serupa untuk:</p>
                            <ul class="space-y-2 mb-3">
                                <li class="flex items-center gap-3"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Mengingat preferensi pengguna</li>
                                <li class="flex items-center gap-3"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Meningkatkan performa website</li>
                                <li class="flex items-center gap-3"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Menganalisis penggunaan layanan</li>
                                <li class="flex items-center gap-3"><i class="bi bi-circle-fill text-[6px] text-orange-500"></i> Memberikan pengalaman pengguna yang lebih baik</li>
                            </ul>
                            <p class="text-sm">Pengguna dapat mengatur penggunaan cookies melalui pengaturan browser masing-masing.</p>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pengungkapan Informasi ke Pihak Ketiga</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Dalam kondisi tertentu, informasi pelanggan dapat dibagikan kepada pihak ketiga yang terlibat dalam operasional layanan, seperti:</p>
                            <ul class="space-y-2 mb-3">
                                <li class="flex items-center gap-3"><i class="bi bi-arrow-right-short text-orange-500 text-xl"></i> Penyedia layanan pembayaran</li>
                                <li class="flex items-center gap-3"><i class="bi bi-arrow-right-short text-orange-500 text-xl"></i> Jasa ekspedisi dan logistik</li>
                                <li class="flex items-center gap-3"><i class="bi bi-arrow-right-short text-orange-500 text-xl"></i> Penyedia infrastruktur teknologi</li>
                                <li class="flex items-center gap-3"><i class="bi bi-arrow-right-short text-orange-500 text-xl"></i> Pihak berwenang sesuai ketentuan hukum</li>
                            </ul>
                            <p class="text-sm italic text-gray-500">* Informasi yang dibagikan hanya sebatas yang diperlukan untuk menjalankan layanan.</p>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Pelacakan Pengiriman</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p>Untuk memudahkan pelanggan memantau status pengiriman pesanan, SUPERMURA.ID menggunakan layanan pelacakan pihak ketiga, yaitu <span class="font-bold text-gray-800">17TRACK</span>.</p>
                            <p class="mt-2">Saat menggunakan fitur pelacakan tersebut, pelanggan dapat diarahkan ke layanan pihak ketiga yang memiliki kebijakan privasi tersendiri. Kami menyarankan pengguna untuk membaca kebijakan privasi yang berlaku pada layanan tersebut.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            8
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Hak Pengguna</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Pengguna memiliki hak untuk:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm"><i class="bi bi-eye text-orange-500 mr-2"></i> Mengakses informasi pribadi</div>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm"><i class="bi bi-pencil-square text-orange-500 mr-2"></i> Memperbarui data tidak akurat</div>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm"><i class="bi bi-eraser text-orange-500 mr-2"></i> Meminta koreksi data</div>
                                <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm"><i class="bi bi-headset text-orange-500 mr-2"></i> Menghubungi kami terkait data</div>
                            </div>
                            <p class="text-sm">Permintaan akan diproses sesuai dengan ketentuan yang berlaku dan kemampuan teknis sistem kami.</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 md:gap-6">
                    <div class="shrink-0">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl font-black border border-orange-100 shadow-sm">
                            9
                        </div>
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Perlindungan Akun Pengguna</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p class="mb-3">Untuk membantu menjaga keamanan akun, kami menyarankan pengguna untuk:</p>
                            <ul class="space-y-3 mb-4">
                                <li class="flex items-start gap-3"><i class="bi bi-key text-orange-500 text-xl leading-none"></i> Menggunakan kata sandi yang kuat</li>
                                <li class="flex items-start gap-3"><i class="bi bi-eye-slash text-orange-500 text-xl leading-none"></i> Tidak membagikan informasi login kepada siapa pun</li>
                                <li class="flex items-start gap-3"><i class="bi bi-box-arrow-right text-orange-500 text-xl leading-none"></i> Keluar (logout) setelah selesai menggunakan akun pada perangkat umum</li>
                                <li class="flex items-start gap-3"><i class="bi bi-exclamation-triangle text-orange-500 text-xl leading-none"></i> Segera menghubungi kami jika menemukan aktivitas mencurigakan pada akun</li>
                            </ul>
                            <div class="text-orange-600 text-sm font-bold">
                                Keamanan akun juga menjadi tanggung jawab pengguna sebagai pemilik akun.
                            </div>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Tautan ke Situs Pihak Ketiga</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p>Website SUPERMURA.ID dapat berisi tautan menuju website atau layanan pihak ketiga.</p>
                            <p class="mt-2">Kami tidak bertanggung jawab atas kebijakan privasi, isi, keamanan, maupun praktik yang dilakukan oleh website pihak ketiga tersebut. Pengguna disarankan untuk membaca kebijakan yang berlaku pada masing-masing website yang dikunjungi.</p>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Perubahan Kebijakan</h2>
                        <div class="text-gray-600 font-medium leading-relaxed">
                            <p>SUPERMURA.ID berhak mengubah, memperbarui, atau menyesuaikan Kebijakan Keamanan dan Privasi ini sewaktu-waktu sesuai kebutuhan operasional maupun perkembangan regulasi.</p>
                            <p class="mt-2 text-sm">Versi terbaru akan selalu tersedia pada halaman ini dan berlaku sejak tanggal dipublikasikan.</p>
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
                        <h2 class="text-xl md:text-2xl font-black text-gray-800 tracking-tight uppercase mb-3 mt-1">Hubungi Kami</h2>
                        <p class="text-gray-600 font-medium leading-relaxed mb-6">Jika Anda memiliki pertanyaan mengenai Kebijakan Keamanan dan Privasi ini, silakan menghubungi kami:</p>
                        
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 md:w-max mb-6">
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
                                    <a href="mailto:ptbrycloe@gmail.com" class="hover:text-orange-600 transition-colors">ptbrycloe@gmail.com</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-16 pt-8 border-t border-gray-100 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-orange-100 text-orange-500 rounded-full mb-4">
                    <i class="bi bi-heart-fill text-2xl"></i>
                </div>
                <p class="text-gray-800 font-black text-lg max-w-2xl mx-auto leading-relaxed">
                    Kami berkomitmen untuk menjaga keamanan data dan privasi pelanggan agar pengalaman berbelanja di SUPERMURA.ID tetap aman, nyaman, dan terpercaya.
                </p>
            </div>

        </div>
    </main>
@endsection