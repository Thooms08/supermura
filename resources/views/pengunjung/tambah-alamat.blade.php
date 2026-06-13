<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Alamat | SUPERMURA.ID</title>
    @include('layouts.favicon')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-xl mx-auto px-4 py-10">
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-orange-100">
            <h1 class="text-2xl font-black text-orange-600 mb-6 uppercase tracking-tight">Alamat Baru</h1>
            
            <form action="{{ route('pengunjung.alamat.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Nama Penerima</label>
                    <input type="text" name="nama_lengkap" required class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Nomor WhatsApp</label>
                    <input type="number" name="no_whatsapp" required class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all" placeholder="08xxxx">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Alamat Lengkap</label>
                    <textarea name="alamat_lengkap" required rows="3" class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Kota / Kabupaten</label>
                        <input type="text" name="kota_kabupaten" required class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Provinsi</label>
                        <input type="text" name="provinsi" required class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1 ml-2">Kode Pos</label>
                    <input type="number" name="kode_pos" required class="w-full p-4 bg-gray-50 border rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="history.back()" class="flex-1 py-4 border rounded-2xl font-bold text-gray-500 hover:bg-gray-50 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-[2] py-4 bg-orange-600 text-white font-black rounded-2xl hover:bg-orange-700 shadow-lg shadow-orange-200 transition-all uppercase tracking-widest text-xs">
                        Simpan Alamat
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>