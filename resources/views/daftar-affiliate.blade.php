@extends('layouts.app')
@include('layouts.favicon')

@section('title', 'Gabung Affiliator | SUPERMURA.ID')

@section('content')
<div class="bg-orange-50/50 min-h-screen py-16">
    <div class="max-w-2xl mx-auto px-4">
        
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-gray-900 uppercase">Pendaftaran Affiliator</h2>
            <p class="text-gray-500 mt-2 font-medium">Lengkapi formulir di bawah untuk mulai berjualan bersama kami.</p>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-orange-100/50 overflow-hidden border border-orange-50">
            <div class="h-2 bg-orange-600"></div>

            <div class="p-8 md:p-12">
                @if(session('error'))
                    <div class="bg-red-50 text-red-600 p-4 rounded-2xl mb-6 text-sm font-bold flex items-center">
                        <i class="bi bi-exclamation-triangle-fill me-3"></i> {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('affiliate.register.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-black text-orange-600 uppercase tracking-widest block mb-2">
                                <i class="bi bi-fingerprint"></i> ID Affiliator (Max 8 Karakter)
                            </label>
                            <input type="text" name="id_unik" maxlength="8" required 
                                   class="w-full px-5 py-4 bg-orange-50/50 border border-orange-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none font-bold text-orange-600 uppercase placeholder:text-orange-200 @error('id_unik') border-red-500 @enderror" 
                                   placeholder="Contoh: SM01" value="{{ old('id_unik') }}">
                            @error('id_unik') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">
                                <i class="bi bi-ticket-perforated"></i> Kode Referral (Opsional)
                            </label>
                            <input type="text" name="kode_referral" maxlength="8" 
                                value="{{ request()->query('ref') ?? old('kode_referral') }}"
                                class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold text-orange-600 uppercase placeholder:text-gray-300" 
                                placeholder="Contoh: REF99">
                            <p class="text-[9px] text-gray-400 mt-1 px-1">*Terisi otomatis jika menggunakan link referral.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Nama Lengkap</label>
                                <input type="text" name="nama" required value="{{ old('nama') }}"
                                       class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Alamat Email</label>
                                <input type="email" name="email" required value="{{ old('email') }}"
                                       class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold @error('email') border-red-500 @enderror">
                                @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">No. WhatsApp</label>
                                <input type="text" name="no_whatsapp" placeholder="08xxx" required value="{{ old('no_whatsapp') }}"
                                       class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Domisili (Kota)</label>
                                <input type="text" name="domisili" required value="{{ old('domisili') }}"
                                       class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div x-data="{ show: false }">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Password</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password" required
                   class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">
            
            <button type="button" @click="show = !show" 
                    class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-orange-500 transition-colors">
                <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
            </button>
        </div>
    </div>

    <div x-data="{ show: false }">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2 px-1">Konfirmasi Password</label>
        <div class="relative">
            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                   class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl focus:ring-4 focus:ring-orange-500/10 outline-none text-sm font-bold shadow-sm">
            
            <button type="button" @click="show = !show" 
                    class="absolute inset-y-0 right-0 pr-5 flex items-center text-gray-400 hover:text-orange-500 transition-colors">
                <i class="bi" :class="show ? 'bi-eye-slash-fill' : 'bi-eye-fill'"></i>
            </button>
        </div>
    </div>
</div>

                        <hr class="border-dashed border-gray-200 my-8">

                        {{-- Bagian Pembayaran Langsung Muncul --}}
                        @if($biaya > 0)
                        <div class="space-y-4" x-data="{ metode: '{{ $qris ? '' : 'transfer' }}' }">

                            {{-- Info biaya --}}
                            <div class="bg-orange-50 p-4 rounded-2xl border border-orange-100">
                                <p class="text-[10px] font-black text-orange-600 uppercase tracking-widest mb-1">Total Biaya Pendaftaran</p>
                                <p class="text-xl font-black text-orange-700">Rp {{ number_format($biaya, 0, ',', '.') }}</p>
                            </div>

                            {{-- Pilih metode —  QRIS hanya muncul jika admin sudah upload --}}
                            <div>
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block mb-2">Metode Pembayaran</label>
                                <div class="grid grid-cols-{{ $qris ? '2' : '1' }} gap-3">

                                    {{-- Opsi Transfer --}}
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="transfer"
                                               x-model="metode" class="sr-only peer" {{ !$qris ? 'checked' : '' }}>
                                        <div class="flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                            <div class="w-8 h-8 bg-blue-100 peer-checked:bg-blue-500 rounded-lg flex items-center justify-center">
                                                <i class="bi bi-bank text-blue-500 peer-checked:text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-700">Transfer Bank</p>
                                                <p class="text-[9px] text-gray-400">Upload bukti transfer</p>
                                            </div>
                                        </div>
                                    </label>

                                    {{-- Opsi QRIS — hanya jika ada --}}
                                    @if($qris)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="metode_pembayaran" value="qris"
                                               x-model="metode" class="sr-only peer">
                                        <div class="flex items-center gap-3 p-4 rounded-2xl border-2 border-gray-100 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                                <i class="bi bi-qr-code text-green-500 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-gray-700">QRIS</p>
                                                <p class="text-[9px] text-gray-400">Scan & bayar</p>
                                            </div>
                                        </div>
                                    </label>
                                    @endif
                                </div>
                            </div>

                            {{-- Panel Transfer: nomor rekening + upload bukti --}}
                            <div x-show="metode === 'transfer'" x-cloak
                                 class="p-6 bg-blue-50 rounded-3xl border border-blue-100 space-y-4 shadow-inner shadow-blue-200/50">
                                @if($no_rek)
                                <div class="flex items-start gap-3 p-4 bg-white rounded-2xl border border-blue-100">
                                    <div class="w-9 h-9 bg-blue-500 rounded-xl flex items-center justify-center text-white shrink-0">
                                        <i class="bi bi-bank text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-[9px] font-black text-blue-400 uppercase tracking-widest mb-0.5">Rekening Tujuan Transfer</p>
                                        <p class="text-sm font-black text-blue-700">{{ $no_rek }}</p>
                                    </div>
                                </div>
                                @endif
                                <div>
                                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest block mb-2">
                                        <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Upload Bukti Transfer
                                    </label>
                                    <input type="file" name="bukti_transfer" :required="metode === 'transfer'"
                                           accept="image/jpeg,image/png,image/jpg"
                                           class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-500 file:text-white hover:file:bg-blue-600 w-full">
                                    <p class="text-[9px] text-blue-400 font-bold mt-1">*Format: JPG, JPEG, PNG (Maks 2MB)</p>
                                </div>
                            </div>

                            {{-- Panel QRIS: tampilkan gambar QR --}}
                            @if($qris)
                            <div x-show="metode === 'qris'" x-cloak
                                 class="p-6 bg-green-50 rounded-3xl border border-green-100 shadow-inner shadow-green-200/50 text-center space-y-3">
                                <p class="text-[10px] font-black text-green-600 uppercase tracking-widest">Scan QRIS Berikut</p>
                                <div class="flex justify-center">
                                    <img src="{{ asset('storage/qris/' . $qris) }}" alt="QRIS Pembayaran"
                                         class="w-48 h-48 object-contain rounded-2xl border-2 border-green-200 shadow-md bg-white p-2">
                                </div>
                                <p class="text-xs text-green-600 font-bold">
                                    Nominal: <span class="font-black">Rp {{ number_format($biaya, 0, ',', '.') }}</span>
                                </p>
                                <p class="text-[9px] text-green-500 font-medium">Pastikan nominal pembayaran sesuai. Simpan bukti pembayaran untuk konfirmasi admin.</p>
                            </div>
                            @endif

                        </div>
                        @endif

                        <button type="submit" class="w-full bg-orange-600 text-white py-5 rounded-[1.5rem] font-black uppercase tracking-widest shadow-xl shadow-orange-200 hover:bg-orange-700 hover:scale-[1.02] transition-all mt-8">
                            Daftar Affiliator <i class="bi bi-check-circle-fill ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <p class="text-center mt-8 text-sm font-bold text-gray-400 uppercase tracking-widest">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-600 hover:underline">Masuk Disini</a>
        </p>
    </div>
</div>
@endsection