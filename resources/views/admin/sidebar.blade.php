@include('layouts.favicon')
<div class="flex flex-col h-screen bg-white border-r border-gray-100 shadow-sm relative">
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

    <div class="flex-1 px-4 overflow-y-auto custom-scrollbar">
        <ul class="space-y-1.5 font-medium">
            
            <li class="px-3 pb-2 pt-4">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Bagian Operasional</span>
            </li>
            
            <li>
                <a href="{{ url('admin') }}" 
                   class="flex items-center p-3 text-gray-700 rounded-xl transition-all duration-200 group {{ request()->is('admin') ? 'bg-orange-50 text-orange-600 shadow-sm' : 'hover:bg-gray-50' }}">
                    <i class="bi bi-grid-fill w-5 h-5 transition duration-75 {{ request()->is('admin') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('profile-toko.index') }}" 
                class="flex items-center p-3 rounded-xl transition-all group {{ request()->routeIs('profile-toko.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-person-badge-fill w-5 h-5 {{ request()->routeIs('profile-toko.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm font-medium">Profile Toko</span>
                </a>
            </li>

            <li>
                <a href="{{ route('produk.index') }}" 
                class="flex items-center p-3 rounded-xl transition-all group {{ request()->routeIs('produk.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-box-seam-fill w-5 h-5 {{ request()->routeIs('produk.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm font-medium">Produk</span>
                </a>
            </li>
            <li>
                <a href="{{ route('shipping-methods.index') }}" 
                class="flex items-center p-3 rounded-xl transition-all group {{ request()->routeIs('shipping-methods.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-truck w-5 h-5 {{ request()->routeIs('shipping-methods.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm">Metode Pengiriman</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.laporan.index') }}" 
                class="flex items-center p-3 rounded-xl group transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    <i class="bi bi-graph-up-arrow w-5 h-5 transition-colors {{ request()->routeIs('admin.laporan.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="ms-3 text-sm {{ request()->routeIs('admin.laporan.*')}}">Laporan Penjualan</span>        
                </a>
            </li>

           <li>
            <a href="{{ route('admin.ulasan.index') }}" 
            class="flex items-center p-3 rounded-xl group transition-all {{ request()->routeIs('admin.ulasan.index') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                <i class="bi bi-chat-left-heart-fill w-5 h-5 transition-colors {{ request()->routeIs('admin.ulasan.index') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                <span class="ms-3 text-sm {{ request()->routeIs('admin.ulasan.index')}}">Ulasan</span>
            </a>
        </li>

                    <li x-data="{ open: {{ request()->routeIs('admin.orders.*') ? 'true' : 'false' }} }">
                <button @click="open = !open" type="button" 
                        class="flex items-center w-full p-3 text-gray-700 transition-all duration-200 rounded-xl group hover:bg-gray-50 {{ request()->routeIs('admin.orders.*') ? 'bg-orange-50 text-orange-600 shadow-sm' : '' }}">
                    <i class="bi bi-cart-fill w-5 h-5 {{ request()->routeIs('admin.orders.*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    <span class="flex-1 ms-3 text-left text-sm font-medium">Pesanan</span>
                    
                    <span id="pending-badge" class="hidden mr-2 bg-orange-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
                        0
                    </span>

                    <i class="bi text-[10px] transition-transform duration-200" :class="open ? 'bi-chevron-up rotate-180' : 'bi-chevron-down'"></i>
                </button>

                <ul x-show="open" 
                    x-cloak 
                    x-transition:enter="transition ease-out duration-100" 
                    x-transition:enter-start="opacity-0 -translate-y-2" 
                    class="mt-1 space-y-1 px-2">
                    
                    <li>
    <a href="{{ route('admin.orders.index') }}" 
       class="flex items-center justify-between w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.orders.index') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        <div class="flex items-center">
            <i class="bi bi-inbox mr-2"></i> Daftar Pesanan
        </div>
        
        <span id="pending-badge-sub" class="hidden bg-orange-100 text-orange-600 text-[10px] px-2 py-0.5 rounded-full font-bold">
            0
        </span>
    </a>
</li>

                    <li>
                        <a href="{{ route('admin.orders.process') }}" 
                        class="flex items-center justify-between w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
                        {{ request()->routeIs('admin.orders.process') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
                            <span class="flex items-center"><i class="bi bi-gear-wide-connected mr-2"></i> Proses</span>
                            <span id="process-badge-sub" class="hidden bg-blue-100 text-blue-600 text-[10px] px-2 py-0.5 rounded-full font-bold">0</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.orders.send') }}"
                        class="flex items-center justify-between w-full p-2.5 text-xs rounded-lg pl-10 transition-all
                        {{ request()->routeIs('admin.orders.send') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
                            <span class="flex items-center"><i class="bi bi-send-fill mr-2"></i> Dikirim</span>
                            <span id="send-badge-sub" class="hidden bg-indigo-100 text-indigo-600 text-[10px] px-2 py-0.5 rounded-full font-bold">0</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.orders.success') }}" 
                        class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
                        {{ request()->routeIs('admin.orders.success') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="bi bi-check2-circle mr-2"></i> Sukses
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.orders.fail') }}" 
                        class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
                        {{ request()->routeIs('admin.orders.fail') ? 'text-orange-600 font-bold' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
                            <i class="bi bi-x-circle mr-2"></i> Gagal
                        </a>
                    </li>
                </ul>
            </li>
            <li x-data="{ open: {{ request()->routeIs('admin.refunds.*') ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button" 
            class="flex items-center w-full p-3 text-gray-700 transition duration-75 rounded-xl group hover:bg-gray-50 {{ request()->routeIs('admin.refunds.*') ? 'bg-orange-50 text-orange-600' : '' }}">
        <i class="bi bi-arrow-counterclockwise w-5 h-5 {{ request()->routeIs('admin.refunds.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
        <span class="flex-1 ms-3 text-left text-sm font-medium">Refund</span>
        
        <span id="refund-badge-main" class="hidden mr-2 bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">
            0
        </span>

        <i class="bi text-[10px] transition-transform duration-200" :class="open ? 'bi-chevron-up rotate-180' : 'bi-chevron-down'"></i>
    </button>

    <ul x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 -translate-y-2" class="mt-1 space-y-1 px-2">
        <li>
            <a href="{{ route('admin.refunds.pending') }}" 
               class="flex items-center justify-between w-full p-2.5 text-xs rounded-lg pl-10 transition-all {{ request()->routeIs('admin.refunds.pending') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
                <span>Pending</span>
                
                <span id="refund-badge-sub" class="hidden bg-orange-100 text-orange-600 text-[10px] px-2 py-0.5 rounded-full font-bold">
                    0
                </span>
            </a>
        </li>
                   <li>
    <a href="{{ route('admin.refunds.success') }}" 
       class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.refunds.success') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        Sukses
    </a>
</li>
<li>
    <a href="{{ route('admin.refunds.fail') }}" 
       class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.refunds.fail') ? 'text-orange-600 font-bold bg-orange-50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        Gagal
    </a>
</li>
                </ul>
            </li>

            <li class="px-3 pb-2 pt-8">
                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">Bagian Affiliators</span>
            </li>

            <li>
                <a href="{{ route('admin.affiliator.index') }}" 
                class="flex items-center p-3 rounded-xl transition-all group {{ request()->routeIs('admin.affiliator.index') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
                    
                    <i class="bi bi-people-fill w-5 h-5 {{ request()->routeIs('admin.affiliator.index') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
                    
                    <span class="ms-3 text-sm {{ request()->routeIs('admin.affiliator.index') ? 'font-bold' : '' }}">
                        Data Affiliator
                    </span>
                </a>
            </li>
           <li x-data="{ open: {{ request()->is('admin/komisi*') ? 'true' : 'false' }} }">
    <button @click="open = !open" type="button" 
            class="flex items-center w-full p-3 text-gray-700 transition-all duration-200 rounded-xl group hover:bg-gray-50 {{ request()->is('admin/komisi*') ? 'bg-orange-50 text-orange-600 shadow-sm' : '' }}">
        <i class="bi bi-wallet2 w-5 h-5 {{ request()->is('admin/komisi*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
        <span class="flex-1 ms-3 text-left text-sm font-medium">Komisi Affiliator</span>
        
        <i class="bi text-[10px] transition-transform duration-200" 
           :class="open ? 'bi-chevron-up rotate-180' : 'bi-chevron-down'"></i>
    </button>

    <ul x-show="open" 
        x-cloak 
        x-transition:enter="transition ease-out duration-100" 
        x-transition:enter-start="opacity-0 -translate-y-2" 
        class="mt-1 space-y-1 px-2">
        
        <li>
    <a href="{{ route('admin.komisi.atur') }}" 
       class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.komisi.atur') ? 'text-orange-600 font-bold bg-orange-50/50' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        <i class="bi bi-sliders2-vertical mr-2"></i> Atur Komisi
    </a>
</li>

        <li>
    <a href="{{ route('admin.pengajuan-komisi.index') }}" 
       class="flex items-center justify-between w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.pengajuan-komisi.*') ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        <span class="flex items-center"><i class="bi bi-send-check mr-2"></i> Pengajuan Komisi</span>
        <span id="pengajuan-komisi-badge" class="hidden bg-orange-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm animate-pulse">0</span>
    </a>
</li>

        <li>
    <a href="{{ route('admin.history-komisi.index') }}" 
       class="flex items-center w-full p-2.5 text-xs rounded-lg pl-10 transition-all 
       {{ request()->routeIs('admin.history-komisi.*') ? 'bg-orange-50 text-orange-600 font-bold' : 'text-gray-500 hover:bg-orange-50 hover:text-orange-600' }}">
        <i class="bi bi-clock-history mr-2"></i> Riwayat Komisi
    </a>
</li>
    </ul>
</li>
            <li>
    <a href="{{ route('admin.alat-promosi.index') }}" 
       class="flex items-center p-3 rounded-xl transition-all group 
       {{ request()->routeIs('admin.alat-promosi.*') ? 'bg-orange-50 text-orange-600' : 'text-gray-700 hover:bg-gray-50' }}">
        <i class="bi bi-megaphone w-5 h-5 {{ request()->routeIs('admin.alat-promosi.*') ? 'text-orange-500' : 'text-gray-400 group-hover:text-orange-500' }}"></i>
        <span class="ms-3 text-sm {{ request()->routeIs('admin.alat-promosi.*') ? 'font-bold' : '' }}">Alat Promosi</span>
    </a>
</li>
        </ul>
    </div>

    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
        <form id="logout-form-admin" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
        <button type="button" onclick="confirmLogout('logout-form-admin')"
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

    function checkPendingOrders() {
        fetch("{{ route('admin.orders.pendingCount') }}")
            .then(response => response.json())
            .then(data => {
                const badgeMain = document.getElementById('pending-badge');
                const badgeSub  = document.getElementById('pending-badge-sub');

                if (data.count > 0) {
                    badgeMain.innerText = data.count;
                    badgeMain.classList.remove('hidden');
                    if (badgeSub) { badgeSub.innerText = data.count; badgeSub.classList.remove('hidden'); }
                } else {
                    badgeMain.classList.add('hidden');
                    if (badgeSub) badgeSub.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error fetching pending count:', error));
    }

    function checkProcessOrders() {
        fetch("{{ route('admin.orders.processCount') }}")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('process-badge-sub');
                if (!badge) return;
                if (data.count > 0) { badge.innerText = data.count; badge.classList.remove('hidden'); }
                else { badge.classList.add('hidden'); }
            })
            .catch(() => {});
    }

    function checkSendOrders() {
        fetch("{{ route('admin.orders.sendCount') }}")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('send-badge-sub');
                if (!badge) return;
                if (data.count > 0) { badge.innerText = data.count; badge.classList.remove('hidden'); }
                else { badge.classList.add('hidden'); }
            })
            .catch(() => {});
    }

    function updateRefundCount() {
        fetch("{{ route('admin.refunds.count') }}")
            .then(response => response.json())
            .then(data => {
                const badgeMain = document.getElementById('refund-badge-main');
                const badgeSub  = document.getElementById('refund-badge-sub');

                if (data.count > 0) {
                    badgeMain.innerText = data.count;
                    badgeMain.classList.remove('hidden');
                    if (badgeSub) { badgeSub.innerText = data.count; badgeSub.classList.remove('hidden'); }
                } else {
                    badgeMain.classList.add('hidden');
                    if (badgeSub) badgeSub.classList.add('hidden');
                }
            })
            .catch(error => console.error('Error updating refund count:', error));
    }

    function checkPengajuanKomisi() {
        fetch("{{ route('admin.pengajuan-komisi.count') }}")
            .then(res => res.json())
            .then(data => {
                const badge = document.getElementById('pengajuan-komisi-badge');
                if (!badge) return;
                if (data.count > 0) {
                    badge.innerText = data.count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            })
            .catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkPendingOrders();
        checkProcessOrders();
        checkSendOrders();
        updateRefundCount();
        checkPengajuanKomisi();
        setInterval(checkPendingOrders, 10000);
        setInterval(checkProcessOrders, 10000);
        setInterval(checkSendOrders, 10000);
        setInterval(updateRefundCount, 10000);
        setInterval(checkPengajuanKomisi, 10000);
    });
</script>