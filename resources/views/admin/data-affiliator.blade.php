<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Affiliator | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" 
      x-data="{ 
          sidebarOpen: window.innerWidth >= 1024,
          modalAdd: false,
          modalFee: false,
          modalKomisi: false,
          metode: '',
          nominalInput: 0,
          biayaPendaftaran: {{ $biaya }}
      }" 
      @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = true } else { sidebarOpen = false }">

    <div class="flex h-screen overflow-hidden relative">
        <aside 
            :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
            class="transition-all duration-300 ease-in-out transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative shadow-2xl lg:shadow-none overflow-hidden">
            <div class="w-64 h-full">
                @include('admin.sidebar')
            </div>
        </aside>

        <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-black/50 z-40 lg:hidden transition-opacity" x-cloak></div>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            
            <header class="sticky top-0 z-30 bg-white border-b border-gray-200 px-4 py-3 md:px-6 md:py-0 md:h-16 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-3 md:gap-4 shrink-0">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors focus:outline-none shrink-0">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-base sm:text-lg font-bold text-gray-800 tracking-tight leading-none whitespace-nowrap">
                        Data<span class="text-orange-500">Affiliator</span>
                    </h2>
                </div>

                <div class="order-3 md:order-2 w-full md:flex-1 md:max-w-xs md:mx-4">
                    <div class="relative w-full">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <i class="bi bi-search text-gray-400 text-sm"></i>
                        </span>
                        <input type="text" id="searchAffiliator" placeholder="Cari nama, WA, atau status..." 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                </div>

                <div class="order-2 md:order-3 flex w-full flex-col sm:flex-row gap-2 md:w-auto md:flex-none">
                    <button @click="modalKomisi = true" class="w-full sm:w-auto px-4 py-2 bg-orange-100 text-orange-700 rounded-xl text-xs font-bold hover:bg-orange-200 transition-all whitespace-nowrap">
                        <i class="bi bi-gift-fill mr-1"></i> KOMISI REKRUT
                    </button>
                    <button @click="modalFee = true" class="w-full sm:w-auto px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition-all whitespace-nowrap">
                        <i class="bi bi-gear-fill mr-1"></i> BIAYA
                    </button>
                    <button @click="modalAdd = true" class="w-full sm:w-auto px-4 py-2 bg-orange-500 text-white rounded-xl text-xs font-bold shadow-lg shadow-orange-200 hover:bg-orange-600 transition-all whitespace-nowrap">
                        <i class="bi bi-plus-lg mr-1"></i> AFFILIATOR
                    </button>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                
                <div class="mb-6 p-4 bg-orange-50 border border-orange-100 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                            <i class="bi bi-cash-stack"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-orange-400 uppercase tracking-widest">Biaya Pendaftaran Aktif</p>
                            <h4 class="text-lg font-black text-orange-600">Rp {{ number_format($biaya, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                    @if($biaya == 0)
                        <span class="px-3 py-1 bg-green-100 text-green-600 text-[10px] font-black rounded-full">GRATIS</span>
                    @endif
                </div>
                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center text-white shadow-sm">
                <i class="bi bi-gift"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Komisi Rekrut Affiliator</p>
                <h4 class="text-lg font-black text-blue-600">Rp {{ number_format($komisi_rekrut, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50/50 border-b border-gray-100">
                                     <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Id Affiliator</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Affiliator</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kontak & Domisili</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Email</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pembayaran</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="affiliatorTableBody" class="divide-y divide-gray-50">
                                @include('admin.partials.affiliator-list')
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div x-show="modalFee" x-cloak class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
        <div @click.away="modalFee = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
            <div class="bg-orange-500 p-6 text-white flex justify-between items-center">
                <h3 class="text-lg font-black uppercase tracking-tight">Atur Biaya Pendaftaran</h3>
                <button @click="modalFee = false" class="text-2xl hover:scale-110 transition-transform">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <form action="{{ route('admin.affiliator.fee') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-8 space-y-5">

                    {{-- Nominal Biaya --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">
                            <i class="bi bi-cash-stack mr-1 text-orange-500"></i> Nominal Biaya (Rp)
                        </label>
                        <input type="number" name="biaya" value="{{ $biaya }}" required
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-bold">
                        <p class="text-[9px] text-gray-400 mt-1">Isi 0 jika pendaftaran gratis.</p>
                    </div>

                    {{-- Nomor Rekening (wajib) --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">
                            <i class="bi bi-bank mr-1 text-orange-500"></i> Nomor Rekening <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="no_rek" value="{{ $no_rek }}" required
                               placeholder="Contoh: BCA 1234567890 a/n Toko"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                        <p class="text-[9px] text-gray-400 mt-1">Wajib diisi. Akan ditampilkan ke calon affiliator saat transfer.</p>
                    </div>

                    {{-- Upload QRIS (opsional) --}}
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">
                            <i class="bi bi-qr-code mr-1 text-orange-500"></i> QRIS <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>

                        @if($qris)
                        <div class="mb-3 p-3 bg-orange-50 border border-orange-100 rounded-2xl flex items-center gap-3">
                            <img src="{{ asset('storage/qris/' . $qris) }}" alt="QRIS" class="w-16 h-16 object-contain rounded-xl border border-orange-200">
                            <div>
                                <p class="text-[9px] font-black text-orange-500 uppercase">QRIS Aktif</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">Upload baru untuk mengganti.</p>
                            </div>
                        </div>
                        @endif

                        <input type="file" name="qris" accept="image/jpeg,image/png,image/jpg"
                               class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-orange-500 file:text-white hover:file:bg-orange-600">
                        <p class="text-[9px] text-gray-400 mt-1">Format: JPG, PNG. Maks 2MB.</p>
                    </div>

                </div>
                <div class="px-8 pb-8 flex gap-3">
                    <button type="button" @click="modalFee = false" class="flex-1 py-3 text-sm font-bold text-gray-400 hover:text-gray-600">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-orange-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-orange-200 hover:bg-orange-600 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="modalAdd" x-cloak class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
    <div @click.away="modalAdd = false" 
         class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
        
        <div class="bg-orange-500 p-6 text-white flex justify-between items-center shrink-0">
            <h3 class="text-lg font-black uppercase tracking-tight">+ Affiliator Baru</h3>
            <button @click="modalAdd = false" class="text-2xl hover:scale-110 transition-transform">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.affiliator.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
            @csrf
            
            <div class="p-6 overflow-y-auto custom-scrollbar space-y-6 max-h-[60vh]">
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
        <label class="text-[10px] font-black text-orange-500 uppercase tracking-widest block mb-2">
            <i class="bi bi-fingerprint mr-1"></i> ID AFFILIATOR (UNIK)
        </label>
        <input type="text" name="id_unik" required maxlength="8" 
               placeholder="Contoh: FL001"
               class="w-full px-4 py-3 bg-orange-50 border border-orange-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-bold text-orange-600 uppercase placeholder:text-orange-200">
        <p class="text-[9px] text-gray-400 mt-1">*Maksimal 8 karakter, contoh: AFF01, FLV88, dll.</p>
    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Nama Lengkap</label>
                        <input type="text" name="nama" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">No. WhatsApp</label>
                        <input type="text" name="no_whatsapp" required placeholder="08xxxx" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                    </div>
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Domisili</label>
                        <input type="text" name="domisili" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                    </div>
                </div>

                @if($biaya > 0)
                <div class="pt-4 border-t border-dashed border-gray-100">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-3">Metode Pembayaran</label>
                    <select name="metode_pembayaran" x-model="metode" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-bold text-orange-600 mb-4">
                        <option value="">-- Pilih Metode --</option>
                        <option value="tunai">Tunai / Cash</option>
                        <option value="transfer">Transfer Bank</option>
                        @if($qris)
                        <option value="qris">QRIS</option>
                        @endif
                    </select>

                    {{-- Transfer: tampilkan nomor rekening --}}
                    <div x-show="metode == 'transfer'" x-cloak class="p-4 bg-blue-50 border border-blue-100 rounded-2xl shadow-inner mb-4 space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white shrink-0">
                                <i class="bi bi-bank text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-0.5">Nomor Rekening Tujuan</p>
                                <p class="text-sm font-black text-blue-700">{{ $no_rek ?: '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest block mb-2">Upload Bukti Transfer</label>
                            <input type="file" name="bukti_transfer" :required="metode == 'transfer'"
                                   accept="image/jpeg,image/png,image/jpg"
                                   class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-500 file:text-white hover:file:bg-blue-600 w-full">
                        </div>
                    </div>

                    {{-- QRIS: tampilkan gambar QRIS --}}
                    @if($qris)
                    <div x-show="metode == 'qris'" x-cloak class="p-4 bg-green-50 border border-green-100 rounded-2xl shadow-inner mb-4">
                        <p class="text-[9px] font-black text-green-500 uppercase tracking-widest mb-3">Scan QRIS Berikut</p>
                        <div class="flex justify-center">
                            <img src="{{ asset('storage/qris/' . $qris) }}" alt="QRIS"
                                 class="w-40 h-40 object-contain rounded-2xl border-2 border-green-200 shadow">
                        </div>
                        <p class="text-[9px] text-center text-green-500 font-medium mt-2">Pastikan pembayaran sesuai nominal Rp <span x-text="new Intl.NumberFormat('id-ID').format(biayaPendaftaran)"></span></p>
                    </div>
                    @endif

                    {{-- Tunai: kalkulasi kembalian --}}
                    <div x-show="metode == 'tunai'" x-cloak class="p-4 bg-orange-50 border border-orange-100 rounded-2xl shadow-inner">
                        <label class="text-[10px] font-black text-orange-400 uppercase tracking-widest block mb-2">Nominal Uang (Rp)</label>
                        <input type="number" name="nominal_tunai" x-model="nominalInput" :required="metode == 'tunai'"
                               class="w-full px-4 py-3 bg-white border border-orange-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none font-black text-orange-600 mb-3">
                        
                        <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-orange-100">
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Kalkulasi</p>
                                <template x-if="nominalInput >= biayaPendaftaran">
                                    <p class="text-xs font-black text-green-600">KEMBALIAN: Rp <span x-text="new Intl.NumberFormat('id-ID').format(nominalInput - biayaPendaftaran)"></span></p>
                                </template>
                                <template x-if="nominalInput < biayaPendaftaran">
                                    <p class="text-xs font-black text-red-500">KURANG: Rp <span x-text="new Intl.NumberFormat('id-ID').format(biayaPendaftaran - nominalInput)"></span></p>
                                </template>
                            </div>
                            <i class="bi bi-calculator-fill text-orange-300 text-lg"></i>
                        </div>
                    </div>
                </div>
                @endif

                <div class="pt-4 border-t border-dashed border-gray-100 space-y-4">
                    <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest">Informasi Akun Login</p>
                    
                    <div>
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Email Login</label>
                        <input type="email" name="email" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                    </div>

                    <div x-data="{ show: false }">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                    </div>

                    <div x-data="{ show: false }">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none text-sm font-medium">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400">
                                <i class="bi" :class="show ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-100 shrink-0">
                <button type="submit" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-200 hover:bg-orange-600 hover:-translate-y-1 transition-all">
                    Simpan Data Affiliator
                </button>
            </div>
        </form>
    </div>
</div>
<div x-show="modalKomisi" x-cloak class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-transition>
    <div @click.away="modalKomisi = false" class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl">
        <div class="p-8">
            <h3 class="text-xl font-black text-gray-800 mb-2 uppercase tracking-tighter">Komisi Rekrut Affiliator</h3>
            <p class="text-xs text-gray-400 mb-6 font-medium">Atur nominal komisi yang didapat affiliator saat berhasil mengajak orang baru.</p>
            
            <form action="{{ route('admin.affiliator.komisi_rekrut') }}" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Nominal Komisi Rekrut (Rp)</label>
                    <input type="number" name="komisi_rekrut" value="{{ $komisi_rekrut }}" required 
                           class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none font-bold text-blue-600">
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="modalKomisi = false" class="flex-1 py-3 text-sm font-bold text-gray-400">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-blue-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
        let searchInput = document.getElementById('searchAffiliator');
        let tableBody = document.getElementById('affiliatorTableBody');
        let debounceTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimeout);
            
            // Debounce 300ms agar tidak terlalu sering hit server saat mengetik
            debounceTimeout = setTimeout(() => {
                let query = searchInput.value;
                tableBody.style.opacity = '0.5';

                fetch("{{ route('admin.affiliator.search') }}?query=" + query, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(data => {
                    tableBody.innerHTML = data;
                    tableBody.style.opacity = '1';
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.style.opacity = '1';
                });
            }, 300);
        });
    </script>

    @if(session('success'))
    <script>
        Swal.fire({ 
            icon: 'success', 
            title: 'Berhasil!', 
            text: "{{ session('success') }}", 
            timer: 3000, 
            showConfirmButton: false,
            border: 'none',
            borderRadius: '2rem'
        });
    </script>
    @endif
</body>
</html>