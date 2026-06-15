@include('layouts.favicon')
<div class="flex flex-col h-screen min-h-0 bg-white border-r border-gray-100 shadow-sm relative">
    <button @click="sidebarOpen = false" class="lg:hidden absolute right-4 top-4 text-gray-400 hover:text-orange-500">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    
    <div class="flex items-center px-6 py-5">
        <div class="bg-orange-500 p-1.5 rounded-lg mr-3 shadow-orange-200 shadow-md">
            <i class="bi bi-shop text-white text-base"></i>
        </div>
        <span class="text-lg font-bold tracking-tight text-gray-800">
            SUPERMURA<span class="text-orange-500">.ID</span>
        </span>
    </div>

    <div class="flex-1 min-h-0 px-4 overflow-y-auto custom-scrollbar pb-4">
        <ul class="space-y-1.5 font-medium">
            <li class="px-3 pb-2 pt-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Menu Utama</span>
            </li>
            
            <li>
                <a href="{{ route('affiliate.dashboard') }}" 
                   class="flex items-center p-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('affiliate.dashboard') ? 'bg-orange-50 text-orange-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-grid-fill w-5 h-5 {{ request()->routeIs('affiliate.dashboard') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm">Dashboard</span>
                </a>
            </li>

            <li>
    <a href="{{ route('affiliate.profile.edit') }}" 
       class="flex items-center p-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('affiliate.profile.*') ? 'bg-orange-50 text-orange-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
        <i class="bi bi-person-circle w-5 h-5 transition-colors {{ request()->routeIs('affiliate.profile.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
        <span class="ms-3 text-sm font-medium">Profile</span>
    </a>
</li>

            <li>
    <a href="{{ route('affiliate.notifikasi.index') }}" 
       class="flex items-center p-3 rounded-xl transition-all duration-200 group {{ request()->routeIs('affiliate.notifikasi.index') ? 'bg-orange-50 text-orange-600 shadow-sm' : 'text-gray-700 hover:bg-gray-50' }}">
        <div class="relative">
            <i class="bi bi-bell-fill w-5 h-5 transition-colors {{ request()->routeIs('affiliate.notifikasi.index') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
            {{-- Badge Notifikasi --}}
            <span id="notifBadge" 
                  class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[9px] font-black w-4 h-4 rounded-full flex items-center justify-center border-2 border-white" 
                  style="display: none;">0</span>
        </div>
        <span class="ms-3 text-sm font-medium">Notifikasi</span>
    </a>
</li>
            <li class="px-3 pb-2 pt-8">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Affiliate Program</span>
            </li>

            <li>
                <a href="{{ route('affiliate.produk.index') }}" 
                class="flex items-center p-3 rounded-xl group transition-all {{ request()->routeIs('affiliate.produk.index') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-people-fill w-5 h-5 {{ request()->routeIs('affiliate.produk.index') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm font-medium">Produk Afiliasi</span>
                </a>
            </li>

            <li>
                <a href="{{ route('affiliate.komisi.index') }}" 
                class="flex items-center p-3 rounded-xl group transition-all {{ request()->routeIs('affiliate.komisi.index') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-wallet2 w-5 h-5 {{ request()->routeIs('affiliate.komisi.index') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm font-medium">Laporan Komisi</span>
                </a>
            </li>

           <li>
    <a href="{{ route('affiliate.alat-promosi.index') }}" 
       class="flex items-center p-3 rounded-xl transition-all group 
       {{ request()->routeIs('affiliate.alat-promosi.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
        <i class="bi bi-megaphone w-5 h-5 {{ request()->routeIs('affiliate.alat-promosi.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
        <span class="ms-3 text-sm {{ request()->routeIs('affiliate.alat-promosi.*') ? 'font-bold' : '' }}">Alat Promosi</span>
    </a>
</li>
        </ul>
    </div>

    <div class="shrink-0 p-4 border-t border-gray-100 bg-gray-50/50 sticky bottom-0">
        <form id="logout-form-affiliate" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <button type="button" onclick="confirmLogout('logout-form-affiliate')"
                class="flex items-center w-full p-3 text-red-500 font-bold text-sm transition-all duration-200 rounded-xl hover:bg-red-50 group">
            <i class="bi bi-box-arrow-left text-lg group-hover:scale-110 transition-transform"></i>
            <span class="ms-3">Log Out</span>
        </button>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 10px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #fb923c; }
    [x-cloak] { display: none !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout(formId) {
    Swal.fire({
        title: 'Keluar dari Akun?',
        text: 'Anda akan keluar dari sesi ini.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal',
        customClass: { popup: 'rounded-3xl' }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}
</script>