<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile | Affiliator</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #fb923c; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <div class="flex h-screen overflow-hidden">
        <aside :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full w-0'" class="transition-all duration-300 ease-in-out bg-white border-r border-gray-200 fixed inset-y-0 left-0 lg:relative z-50 overflow-hidden">
            <div class="w-64 h-full">
                @include('affiliate.sidebar')
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600 transition-colors">
                    <i class="bi bi-list text-2xl"></i>
                </button>
                <h2 class="text-lg font-bold text-gray-800">Pengaturan <span class="text-orange-500">Profil</span></h2>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8 custom-scrollbar">
                <div class="max-w-4xl mx-auto space-y-8">
                    
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-orange-500 flex items-center justify-between">
                            <h3 class="text-white font-bold flex items-center gap-2">
                                <i class="bi bi-person-circle text-xl"></i> Informasi Pribadi
                            </h3>
                        </div>

                        @if(session('success'))
                            <div class="m-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('affiliate.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                            @csrf
                            <div class="flex flex-col md:flex-row gap-8 items-start">
                                <div class="w-full md:w-1/3 flex flex-col items-center">
                                    <div class="relative group">
                                        <div class="w-40 h-40 rounded-3xl overflow-hidden border-4 border-orange-100 shadow-md">
                                            @if($affiliator->foto_profile)
                                                <img id="preview" src="{{ asset('storage/profile-affiliator/' . $affiliator->foto_profile) }}" class="w-full h-full object-cover">
                                            @else
                                                <div id="placeholder" class="w-full h-full bg-orange-50 flex items-center justify-center text-orange-300">
                                                    <i class="bi bi-camera text-4xl"></i>
                                                </div>
                                                <img id="preview" class="w-full h-full object-cover hidden">
                                            @endif
                                        </div>
                                        <label class="absolute -bottom-2 -right-2 bg-orange-600 text-white p-3 rounded-2xl cursor-pointer shadow-lg hover:bg-orange-700 transition-colors">
                                            <i class="bi bi-pencil-square"></i>
                                            <input type="file" name="foto_profile" class="hidden" onchange="previewImage(this)">
                                        </label>
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-4 uppercase font-bold tracking-widest">JPG, PNG, JPEG (Max 2MB)</p>
                                    @error('foto_profile') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="w-full md:w-2/3 grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                        <input type="text" name="nama" value="{{ old('nama', $affiliator->nama) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                                        @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Domisili</label>
                                            <input type="text" name="domisili" value="{{ old('domisili', $affiliator->domisili) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                                            @error('domisili') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $affiliator->no_whatsapp) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all">
                                            @error('no_whatsapp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <button type="submit" class="mt-4 bg-orange-500 text-white font-bold py-3 px-8 rounded-2xl shadow-lg shadow-orange-200 hover:bg-orange-600 transition-all flex items-center justify-center gap-2">
                                        <i class="bi bi-cloud-arrow-up-fill"></i> Simpan Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 bg-gray-800 flex items-center gap-2">
                            <h3 class="text-white font-bold flex items-center gap-2">
                                <i class="bi bi-shield-lock text-xl"></i> Keamanan Akun
                            </h3>
                        </div>

                        @if(session('success_password'))
                            <div class="m-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center gap-3">
                                <i class="bi bi-check-circle-fill"></i> {{ session('success_password') }}
                            </div>
                        @endif

                        <form action="{{ route('affiliate.profile.update-password') }}" method="POST" class="p-6 md:p-8">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Password Baru</label>
                                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all" placeholder="Minimal 6 karakter">
                                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition-all" placeholder="Ulangi password baru">
                                </div>
                            </div>
                            <button type="submit" class="mt-8 bg-gray-800 text-white font-bold py-3 px-8 rounded-2xl shadow-lg hover:bg-black transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-key-fill"></i> Perbarui Password
                            </button>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const preview = document.getElementById('preview');
            const placeholder = document.getElementById('placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>