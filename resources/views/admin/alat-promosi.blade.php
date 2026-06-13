<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alat Promosi | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 overflow-hidden"
      x-data="{
          sidebarOpen: window.innerWidth >= 1024,
          showEditModal: false,
          editItem: { id: null, poster: null, caption: '' },
          previewUrl: null,
          previewName: '',
          removePoster: false,
          openEdit(item) {
              this.editItem    = { ...item };
              this.previewUrl  = null;
              this.previewName = '';
              this.removePoster = false;
              this.showEditModal = true;
          },
          handleEditFile(event) {
              const file = event.target.files[0];
              if (!file) return;
              this.previewName  = file.name;
              this.removePoster = false;
              const reader = new FileReader();
              reader.onload = (e) => { this.previewUrl = e.target.result; };
              reader.readAsDataURL(file);
          },
          clearEditFile() {
              this.previewUrl  = null;
              this.previewName = '';
              this.$refs.editFileInput.value = '';
          }
      }"
      @resize.window="sidebarOpen = window.innerWidth >= 1024">

    <div class="flex h-screen overflow-hidden">
        <aside :class="{ 'translate-x-0 w-64': sidebarOpen, '-translate-x-full lg:translate-x-0 lg:w-0': !sidebarOpen }"
               class="transition-all duration-300 transform bg-white border-r border-gray-200 z-50 fixed inset-y-0 left-0 lg:relative">
            <div class="w-64 h-full">@include('admin.sidebar')</div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
            <header class="h-16 flex items-center justify-between px-6 bg-white border-b border-gray-200">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg text-gray-600 hover:bg-orange-50 hover:text-orange-600">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <h2 class="text-lg font-bold">Marketing <span class="text-orange-500">Tools</span></h2>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-4 md:p-8">

                @if(session('success'))
                    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-2xl font-bold text-sm">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-2xl font-bold text-sm">{{ session('error') }}</div>
                @endif

                {{-- ===== FORM TAMBAH ===== --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm mb-10">
                    <h3 class="text-xl font-black mb-6 flex items-center gap-2">
                        <i class="bi bi-plus-circle-fill text-orange-500"></i> Tambah Alat Promosi
                    </h3>
                    <form action="{{ route('admin.alat-promosi.store') }}" method="POST" enctype="multipart/form-data"
                          x-data="{
                              previewUrl: null,
                              previewName: '',
                              handleFile(event) {
                                  const file = event.target.files[0];
                                  if (!file) { this.previewUrl = null; this.previewName = ''; return; }
                                  this.previewName = file.name;
                                  const reader = new FileReader();
                                  reader.onload = (e) => { this.previewUrl = e.target.result; };
                                  reader.readAsDataURL(file);
                              },
                              clearFile() {
                                  this.previewUrl = null;
                                  this.previewName = '';
                                  this.$refs.fileInput.value = '';
                              }
                          }">
                        @csrf
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            {{-- Upload Poster --}}
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Upload Poster (Opsional)</label>
                                <div class="relative border-2 border-dashed border-gray-200 hover:border-orange-300 rounded-2xl transition-all"
                                     :class="previewUrl ? 'border-orange-400 bg-orange-50' : 'bg-gray-50'">
                                    <label x-show="!previewUrl" for="posterInput"
                                           class="flex flex-col items-center justify-center gap-3 cursor-pointer p-8 select-none">
                                        <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                                            <i class="bi bi-cloud-arrow-up text-2xl text-orange-500"></i>
                                        </div>
                                        <p class="text-sm font-bold text-gray-600">Klik untuk pilih gambar</p>
                                        <p class="text-[10px] text-gray-400">JPG, PNG — Maks 2MB</p>
                                    </label>
                                    <div x-show="previewUrl" class="relative p-3">
                                        <img :src="previewUrl" alt="Preview" class="w-full max-h-64 object-contain rounded-xl bg-white shadow-sm">
                                        <p class="text-[10px] font-bold text-gray-500 mt-2 truncate text-center px-2" x-text="previewName"></p>
                                        <button type="button" @click="clearFile()"
                                                class="absolute top-5 right-5 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-colors">
                                            <i class="bi bi-x-lg text-xs"></i>
                                        </button>
                                        <label for="posterInput" class="block mt-2 text-center text-[10px] font-bold text-orange-500 cursor-pointer hover:underline">Ganti gambar</label>
                                    </div>
                                    <input type="file" name="poster" id="posterInput" x-ref="fileInput"
                                           accept="image/jpeg,image/png,image/jpg" class="hidden" @change="handleFile($event)">
                                </div>
                            </div>
                            {{-- Caption --}}
                            <div>
                                <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Caption Iklan (Opsional)</label>
                                <textarea name="caption" rows="4"
                                          class="w-full p-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 focus:bg-white rounded-2xl outline-none transition-all text-sm"
                                          placeholder="Tulis caption promosi di sini..."></textarea>
                            </div>
                        </div>
                        <div class="mt-8 flex justify-end">
                            <button type="submit" class="px-10 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-2xl font-black shadow-lg shadow-orange-200 hover:scale-105 transition-all">
                                SIMPAN IKLAN
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ===== GRID KARTU ===== --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @forelse($promosi as $item)
                    <div class="bg-white rounded-[2rem] border border-gray-100 shadow-sm overflow-hidden group hover:shadow-xl hover:shadow-orange-100 transition-all duration-500">
                        <div class="h-56 bg-gray-100 overflow-hidden relative">
                            @if($item->poster)
                                <img src="{{ asset('storage/alat-promosi/'.$item->poster) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                    <i class="bi bi-image text-5xl mb-2"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-widest">Tidak ada poster</span>
                                </div>
                            @endif

                            {{-- Tombol Edit & Hapus (muncul saat hover) --}}
                            <div class="absolute top-4 right-4 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button"
                                        @click="openEdit({{ json_encode(['id' => $item->id, 'poster' => $item->poster, 'caption' => $item->caption]) }})"
                                        class="w-10 h-10 bg-white text-orange-500 rounded-xl shadow-lg flex items-center justify-center hover:bg-orange-500 hover:text-white transition-colors">
                                    <i class="bi bi-pencil-fill text-sm"></i>
                                </button>
                                <form action="{{ route('admin.alat-promosi.destroy', $item->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('Hapus konten ini?')"
                                            class="w-10 h-10 bg-red-500 text-white rounded-xl shadow-lg flex items-center justify-center hover:bg-red-600 transition-colors">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-[10px] font-bold text-orange-500 uppercase tracking-widest mb-3">Caption</p>
                            <div class="text-sm text-gray-600 leading-relaxed line-clamp-4 italic">
                                {!! $item->caption ? nl2br(e($item->caption)) : '<span class="text-gray-300">-</span>' !!}
                            </div>
                            <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                                <span class="text-[10px] font-medium text-gray-400">
                                    <i class="bi bi-calendar-event mr-1"></i> {{ $item->created_at->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                        <div class="col-span-full py-20 text-center opacity-30">
                            <i class="bi bi-megaphone text-6xl"></i>
                            <p class="mt-4 font-black uppercase tracking-widest">Belum ada alat promosi</p>
                        </div>
                    @endforelse
                </div>

            </main>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div x-show="showEditModal"
         class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm overflow-y-auto"
         x-cloak>
        <div @click.away="showEditModal = false"
             class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl flex flex-col my-auto"
             style="max-height: 90vh;">

            {{-- Header --}}
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center flex-shrink-0">
                <h3 class="text-xl font-black text-gray-800 flex items-center gap-2">
                    <i class="bi bi-pencil-fill text-orange-500"></i> Edit Alat Promosi
                </h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500 transition-colors">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>

            {{-- Form --}}
            <form :action="`{{ url('admin/alat-promosi') }}/${editItem.id}`"
                  method="POST" enctype="multipart/form-data"
                  class="p-8 space-y-6 overflow-y-auto flex-1">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Kolom Poster --}}
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Poster</label>

                        {{-- Tampilkan poster yang sudah ada --}}
                        <template x-if="editItem.poster && !previewUrl && !removePoster">
                            <div class="relative mb-3">
                                <img :src="`/asset/alat-promosi/${editItem.poster}`"
                                     class="w-full max-h-48 object-contain rounded-2xl bg-gray-50 border border-gray-100 shadow-sm">
                                <p class="text-[10px] text-gray-400 text-center mt-1">Poster saat ini</p>
                                <button type="button" @click="removePoster = true"
                                        class="absolute top-2 right-2 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow transition-colors"
                                        title="Hapus poster">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </button>
                            </div>
                        </template>

                        {{-- Notifikasi poster akan dihapus --}}
                        <template x-if="removePoster && !previewUrl">
                            <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-xl text-xs text-red-600 font-bold flex items-center justify-between">
                                <span><i class="bi bi-exclamation-triangle mr-1"></i> Poster akan dihapus</span>
                                <button type="button" @click="removePoster = false" class="underline ml-2">Batal</button>
                            </div>
                        </template>

                        <input type="hidden" name="remove_poster" :value="removePoster ? '1' : '0'">

                        {{-- Drop zone upload baru --}}
                        <div class="relative border-2 border-dashed rounded-2xl transition-all"
                             :class="previewUrl ? 'border-orange-400 bg-orange-50' : 'border-gray-200 bg-gray-50 hover:border-orange-300'">
                            <label x-show="!previewUrl" for="editPosterInput"
                                   class="flex flex-col items-center justify-center gap-2 cursor-pointer p-6 select-none">
                                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                                    <i class="bi bi-cloud-arrow-up text-lg text-orange-500"></i>
                                </div>
                                <p class="text-xs font-bold text-gray-500"
                                   x-text="(editItem.poster && !removePoster) ? 'Unggah poster baru (opsional)' : 'Klik untuk pilih gambar'"></p>
                                <p class="text-[10px] text-gray-400">JPG, PNG — Maks 2MB</p>
                            </label>
                            <div x-show="previewUrl" class="relative p-3">
                                <img :src="previewUrl" alt="Preview"
                                     class="w-full max-h-48 object-contain rounded-xl bg-white shadow-sm">
                                <p class="text-[10px] font-bold text-gray-500 mt-2 truncate text-center" x-text="previewName"></p>
                                <button type="button" @click="clearEditFile()"
                                        class="absolute top-5 right-5 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow transition-colors">
                                    <i class="bi bi-x-lg text-xs"></i>
                                </button>
                                <label for="editPosterInput" class="block mt-1 text-center text-[10px] font-bold text-orange-500 cursor-pointer hover:underline">
                                    Ganti gambar
                                </label>
                            </div>
                            <input type="file" name="poster" id="editPosterInput" x-ref="editFileInput"
                                   accept="image/jpeg,image/png,image/jpg" class="hidden"
                                   @change="handleEditFile($event)">
                        </div>
                    </div>

                    {{-- Kolom Caption --}}
                    <div>
                        <label class="block text-xs font-black uppercase text-gray-400 tracking-widest mb-3">Caption</label>
                        <textarea name="caption" rows="6"
                                  x-model="editItem.caption"
                                  class="w-full p-4 bg-gray-50 border-2 border-transparent focus:border-orange-500 focus:bg-white rounded-2xl outline-none transition-all text-sm"
                                  placeholder="Tulis caption promosi..."></textarea>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-4 pt-2 flex-shrink-0">
                    <button type="button" @click="showEditModal = false"
                            class="flex-1 py-3 bg-gray-100 text-gray-600 font-black rounded-2xl hover:bg-gray-200 transition-all">
                        Batal
                    </button>
                    <button type="submit"
                            class="flex-[2] py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-black rounded-2xl shadow-lg shadow-orange-200 hover:scale-105 transition-all">
                        SIMPAN PERUBAHAN
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
