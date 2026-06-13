<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Alamat | SUPERMURA.ID</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto px-4 py-10">
        <div class="flex justify-between items-center mb-8">
            <a href="{{ route('checkout') }}" class="bg-white border p-3 rounded-2xl hover:bg-gray-100 transition-all">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <h1 class="text-2xl font-black text-orange-600 uppercase">Pilih Alamat</h1>
        </div>

        <a href="{{ route('pengunjung.alamat.create') }}" class="block w-full text-center py-4 border-2 border-dashed border-orange-300 rounded-3xl text-orange-600 font-bold hover:bg-orange-50 transition-all mb-6">
            <i class="bi bi-plus-circle-fill"></i> Tambah Alamat Baru
        </a>

        <div class="space-y-4">
            @forelse($alamat as $item)
            <div class="bg-white p-5 rounded-3xl border-2 {{ session('selected_alamat_id') == $item->id ? 'border-orange-500' : 'border-transparent' }} shadow-sm hover:shadow-md transition-all relative group">
                
                <div class="absolute top-5 right-5 flex gap-2">
                    <a href="{{ route('pengunjung.alamat.edit', $item->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('pengunjung.alamat.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus alamat ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>

                <form action="{{ route('pengunjung.alamat.select', $item->id) }}" method="POST">
                    @csrf
                    <label class="cursor-pointer block">
                        <div class="flex items-start gap-4">
                            <input type="radio" name="alamat_pilihan" onchange="this.form.submit()" 
                                {{ (session('selected_alamat_id') == $item->id || $loop->first && !session('selected_alamat_id')) ? 'checked' : '' }}
                                class="mt-1 w-5 h-5 text-orange-600 focus:ring-orange-500 border-gray-300">
                            <div class="text-sm pr-20"> <p class="font-bold text-lg text-gray-900">{{ $item->nama_lengkap }}</p>
                                <p class="text-orange-600 font-medium mb-2">{{ $item->no_whatsapp }}</p>
                                <p class="text-gray-500 leading-relaxed">
                                    {{ $item->alamat_lengkap }}<br>
                                    {{ $item->kota_kabupaten }}, {{ $item->provinsi }}<br>
                                    <span class="font-mono text-gray-400">{{ $item->kode_pos }}</span>
                                </p>
                            </div>
                        </div>
                    </label>
                </form>
            </div>
            @empty
            <div class="text-center py-10">
                <i class="bi bi-geo-alt text-5xl text-gray-200"></i>
                <p class="text-gray-400 mt-2">Belum ada alamat tersimpan.</p>
            </div>
            @endforelse
        </div>
    </div>

    @if(session('success_alert'))
    <div id="alert" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-2xl shadow-2xl z-50">
        {{ session('success_alert') }}
    </div>
    <script>
        setTimeout(() => document.getElementById('alert').remove(), 3000);
    </script>
    @endif
</body>
</html>