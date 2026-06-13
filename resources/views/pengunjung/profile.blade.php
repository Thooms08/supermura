@extends('layouts.app')
@include('layouts.favicon')

@section('title', 'Profil Saya | SUPERMURA.ID')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
    <div class="container max-w-4xl mx-auto px-4">
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-2xl font-black text-gray-800 uppercase tracking-tighter">
                    Pengaturan <span class="text-orange-500">Profil</span>
                </h2>
                <p class="text-sm text-gray-500 font-medium">Kelola informasi pribadi dan keamanan akun Anda</p>
            </div>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 rounded-2xl text-xs font-bold hover:bg-gray-50 transition-all w-fit shadow-sm">
                <i class="bi bi-arrow-left"></i> KEMBALI
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-100 text-green-600 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center shadow-sm">
                <i class="bi bi-check-circle-fill me-3 text-lg"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pengunjung.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white rounded-[2.5rem] p-8 border border-gray-100 shadow-sm text-center relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-2 bg-orange-500"></div>
                        
                        <div class="relative inline-block group">
                            @php
                                $fotoProfilUrl = $profil->foto_profile
                                    ? (str_starts_with($profil->foto_profile, 'asset/')
                                        ? asset(str_replace('asset/', 'storage/', $profil->foto_profile))
                                        : asset('storage/profile-pengunjung/' . $profil->foto_profile))
                                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=FF6600&color=fff';
                            @endphp
                            <img src="{{ $fotoProfilUrl }}" 
                                 alt="Profile" 
                                 class="w-32 h-32 rounded-[2rem] object-cover border-4 border-orange-50 shadow-lg mb-4 mx-auto transition-transform group-hover:scale-105 duration-300" 
                                 id="previewFoto">
                            
                            <label for="foto_profile" class="absolute bottom-6 right-0 bg-orange-600 text-white p-2 rounded-xl cursor-pointer shadow-lg hover:bg-orange-700 transition-colors">
                                <i class="bi bi-camera-fill"></i>
                            </label>
                            <input type="file" name="foto_profile" id="foto_profile" class="hidden" onchange="previewImage(this)">
                        </div>

                        <h3 class="font-black text-gray-800 text-lg uppercase leading-tight">{{ $profil->nama_lengkap ?? $user->name }}</h3>
                        <p class="text-xs font-bold text-orange-500 mb-4">{{ $user->email }}</p>
                        
                        <div class="pt-4 border-t border-dashed border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Status Akun</p>
                            <span class="mt-1 inline-block px-4 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-full uppercase">PENGUNJUNG AKTIF</span>
                        </div>
                    </div>

                    @if($user->google_id)
                    <div class="bg-blue-50 rounded-3xl p-6 border border-blue-100 flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-blue-600 shadow-sm">
                            <i class="bi bi-google"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-blue-400 uppercase tracking-widest leading-none">Terhubung dengan</p>
                            <p class="text-xs font-bold text-blue-700">Google Account</p>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="lg:col-span-8 space-y-6">
                    
                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-gray-100 shadow-sm">
                        <h5 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                            <i class="bi bi-person-lines-fill text-orange-500 text-lg"></i> Informasi Pribadi
                        </h5>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profil->nama_lengkap) }}"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold @error('nama_lengkap') border-red-500 @enderror shadow-sm">
                                @error('nama_lengkap') <p class="text-red-500 text-[10px] mt-1 font-bold px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">No. WhatsApp</label>
                                <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $profil->no_whatsapp) }}" placeholder="08xxxx"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold @error('no_whatsapp') border-red-500 @enderror shadow-sm">
                                @error('no_whatsapp') <p class="text-red-500 text-[10px] mt-1 font-bold px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Kota / Kabupaten</label>
                                <input type="text" name="kota_kabupaten" value="{{ old('kota_kabupaten', $profil->kota_kabupaten) }}"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Provinsi</label>
                                <input type="text" name="provinsi" value="{{ old('provinsi', $profil->provinsi) }}"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">
                            </div>

                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" rows="3"
                                          class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">{{ old('alamat_lengkap', $profil->alamat_lengkap) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] p-8 md:p-10 border border-gray-100 shadow-sm relative overflow-hidden">
                        <h5 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8 flex items-center gap-2">
                            <i class="bi bi-shield-lock-fill text-orange-500 text-lg"></i> Keamanan Akun
                        </h5>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Password Baru</label>
                                <input type="password" name="password" placeholder="Isi jika ingin ubah"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold @error('password') border-red-500 @enderror shadow-sm">
                                @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password"
                                       class="w-full px-5 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">
                            </div>
                        </div>

                        <div class="mt-10 flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-dashed border-gray-100">
                            <p class="text-xs text-gray-400 font-medium max-w-xs text-center md:text-left">
                                Pastikan data yang Anda masukkan sudah benar sebelum menekan tombol simpan.
                            </p>
                            <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-orange-500 to-orange-600 text-white px-10 py-4 rounded-2xl font-black uppercase tracking-widest text-xs shadow-xl shadow-orange-200 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2">
                                <i class="bi bi-check-circle-fill"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewFoto').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection