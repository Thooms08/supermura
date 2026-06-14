<?php

use App\Http\Controllers\Admin\AdminAffiliatorController;
use App\Http\Controllers\Admin\AdminAlatPromosiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHistoryKomisiController;
use App\Http\Controllers\Admin\AdminKomisiController;
use App\Http\Controllers\Admin\AdminLaporanController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminOrderFailController;
use App\Http\Controllers\Admin\AdminOrderProcessController;
use App\Http\Controllers\Admin\AdminOrderSuccessController;
use App\Http\Controllers\Admin\AdminOrderSendController;
use App\Http\Controllers\Admin\AdminPengajuanKomisiController;
use App\Http\Controllers\Admin\AdminProdukController;
use App\Http\Controllers\Admin\AdminRefundController;
use App\Http\Controllers\Admin\AdminShippingController;
use App\Http\Controllers\Admin\AdminTokoController;
use App\Http\Controllers\Admin\AdminUlasanController;
use App\Http\Controllers\Affiliator\AffiliateAlatPromosiController;
use App\Http\Controllers\Affiliator\AffiliateDashboardController;
use App\Http\Controllers\Affiliator\AffiliateKomisiController;
use App\Http\Controllers\Affiliator\AffiliateNotifikasiController;
use App\Http\Controllers\Affiliator\AffiliateProdukAfiliasiController;
use App\Http\Controllers\Affiliator\AffiliateProfileController;
use App\Http\Controllers\pengunjung\CartController;
use App\Http\Controllers\pengunjung\PengunjungController;
use App\Http\Controllers\pengunjung\PengunjungPesananController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DaftarAffiliateController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\PaymentCallbackController;
use App\Http\Controllers\ProgramAffiliateController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UlasanController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/product/{produk:slug}', [PublicController::class, 'show'])->name('product.show');
Route::get('/search-products', [PublicController::class, 'search'])->name('product.search');

Route::get('/program-affiliate', [ProgramAffiliateController::class, 'index'])->name('affiliate.program');

Route::get('/tentang-kami', function () {return view('tentang');})->name('tentang');

Route::get('/syarat-dan-ketentuan', function () {return view('ketentuan-syarat');})->name('ketentuan');

Route::get('/faq', function () {return view('faq');})->name('faq');

Route::get('/kebijakan-privasi-dan-keamanan', function () {return view('privasi-keamanan');})->name('privasi');


Route::get('/daftar-affiliate', [DaftarAffiliateController::class, 'index'])->name('affiliate.register');
Route::post('/daftar-affiliate', [DaftarAffiliateController::class, 'store'])->name('affiliate.register.store');

// Sitemap dinamis
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('sitemap');

Route::post('/mayar/callback', [PaymentCallbackController::class, 'handleMayarCallback'])->name('mayar.callback');
// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Google Login
    Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Role Based Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return match (Auth::user()?->role) {
            'admin' => redirect('/admin'),
           'affiliator' => redirect()->route('affiliate.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    Route::get('/admin', [AdminDashboardController::class, 'index'])->middleware('role:admin');
    Route::get('/profile-pengunjung', [PengunjungController::class, 'profile'])->name('pengunjung.profile');
    Route::post('/profile-pengunjung/update', [PengunjungController::class, 'update'])->name('pengunjung.profile.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/buy-now', [PublicController::class, 'buyNow'])->name('buy.now');
    Route::get('/pesanan-saya', [PengunjungPesananController::class, 'index'])->name('pengunjung.pesanan');
    Route::post('/pesanan-saya/batalkan/{id}', [PengunjungPesananController::class, 'batalkan'])->name('pengunjung.pesanan.batalkan');
    Route::post('/pesanan-saya/konfirmasi-terima/{id}', [PengunjungPesananController::class, 'konfirmasiTerima'])->name('pengunjung.pesanan.konfirmasi');
    Route::get('/daftar-alamat', [PengunjungController::class, 'daftarAlamat'])->name('pengunjung.alamat.index');
    Route::get('/tambah-alamat', [PengunjungController::class, 'tambahAlamat'])->name('pengunjung.alamat.create');
    Route::post('/tambah-alamat', [PengunjungController::class, 'storeAlamat'])->name('pengunjung.alamat.store');
    Route::post('/pilih-alamat/{id}', [PengunjungController::class, 'pilihAlamat'])->name('pengunjung.alamat.select');
    Route::get('/alamat/{id}/edit', [PengunjungController::class, 'editAlamat'])->name('pengunjung.alamat.edit');
    Route::put('/alamat/{id}', [PengunjungController::class, 'updateAlamat'])->name('pengunjung.alamat.update');
    Route::delete('/alamat/{id}', [PengunjungController::class, 'destroyAlamat'])->name('pengunjung.alamat.destroy');
    Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/add', [CartController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang/update/{id}', [CartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/delete/{id}', [CartController::class, 'destroy'])->name('keranjang.destroy');
    Route::post('/keranjang/checkout', [CartController::class, 'processToCheckout'])->name('keranjang.checkout.process');
    Route::post('/product/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
    Route::put('/ulasan/{id}', [UlasanController::class, 'update'])->name('ulasan.update');
    Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('profile-toko', AdminTokoController::class);
    Route::put('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::resource('produk', AdminProdukController::class);
    Route::delete('produk-foto/{foto}', [AdminProdukController::class, 'destroyFoto'])->name('produk-foto.destroy');
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/orders-process', [AdminOrderProcessController::class, 'index'])->name('admin.orders.process');
    Route::get('/orders-process-count', [AdminOrderProcessController::class, 'getProcessCount'])->name('admin.orders.processCount');
    Route::patch('/orders-process/{id}/fail', [AdminOrderProcessController::class, 'markAsFail'])->name('admin.orders.markAsFail');
    Route::patch('/orders-process/{id}/success', [AdminOrderProcessController::class, 'markAsSuccess'])->name('admin.orders.markAsSuccess');
    Route::get('/orders-send', [AdminOrderSendController::class, 'index'])->name('admin.orders.send');
    Route::patch('/orders-send/{id}/arrived', [AdminOrderSendController::class, 'markAsArrived'])->name('admin.orders.markAsArrived');
    Route::get('/orders-send-count', [AdminOrderSendController::class, 'getSendCount'])->name('admin.orders.sendCount');
    Route::get('/orders-success', [AdminOrderSuccessController::class, 'index'])->name('admin.orders.success');
    Route::get('/orders-fail', [AdminOrderFailController::class, 'index'])->name('admin.orders.fail');
    Route::get('/orders-pending-count', [AdminOrderController::class, 'getPendingCount'])->name('admin.orders.pendingCount');
    Route::get('/refunds-pending', [AdminRefundController::class, 'index'])->name('admin.refunds.pending');
    Route::patch('/refunds/{id}/update', [AdminRefundController::class, 'updateStatus'])->name('admin.refunds.update');
    Route::get('/refunds-count', [AdminRefundController::class, 'getPendingCount'])->name('admin.refunds.count');
    Route::get('/refunds-success', [AdminRefundController::class, 'successIndex'])->name('admin.refunds.success');
    Route::get('/refunds-success/search', [AdminRefundController::class, 'successSearch'])->name('admin.refunds.search');
    Route::get('/refunds-fail', [AdminRefundController::class, 'failIndex'])->name('admin.refunds.fail');
    Route::get('/refunds-fail/search', [AdminRefundController::class, 'failSearch'])->name('admin.refunds.search');
    Route::get('/ulasan', [AdminUlasanController::class, 'index'])->name('admin.ulasan.index');
    Route::delete('/ulasan/{id}', [AdminUlasanController::class, 'destroy'])->name('admin.ulasan.destroy');
    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/penjualan', [AdminLaporanController::class, 'penjualan'])->name('admin.laporan.penjualan');
    Route::get('/laporan/refund', [AdminLaporanController::class, 'refund'])->name('admin.laporan.refund');
    Route::get('/affiliator', [AdminAffiliatorController::class, 'index'])->name('admin.affiliator.index');
    Route::post('/affiliator', [AdminAffiliatorController::class, 'store'])->name('admin.affiliator.store');
    Route::post('/affiliator/update-status/{id}', [AdminAffiliatorController::class, 'updateStatus'])->name('admin.affiliator.status');
    Route::post('/affiliator/update-fee', [AdminAffiliatorController::class, 'updateFee'])->name('admin.affiliator.fee');
    Route::get('/affiliator/search', [AdminAffiliatorController::class, 'search'])->name('admin.affiliator.search');
    Route::resource('shipping-methods', AdminShippingController::class);
    Route::get('/atur-komisi', [AdminKomisiController::class, 'index'])->name('admin.komisi.atur');
    Route::post('/atur-komisi', [AdminKomisiController::class, 'store'])->name('admin.komisi.store');
    Route::delete('/atur-komisi/{id}', [AdminKomisiController::class, 'destroy'])->name('admin.komisi.destroy');
    Route::get('/pengajuan-komisi', [AdminPengajuanKomisiController::class, 'index'])->name('admin.pengajuan-komisi.index');
    Route::patch('/pengajuan-komisi/{id}/status', [AdminPengajuanKomisiController::class, 'updateStatus'])->name('admin.pengajuan-komisi.update');
    Route::get('/pengajuan-komisi-count', [AdminPengajuanKomisiController::class, 'getPendingCount'])->name('admin.pengajuan-komisi.count');
    Route::get('/history-komisi', [AdminHistoryKomisiController::class, 'index'])->name('admin.history-komisi.index');
    Route::get('/alat-promosi', [AdminAlatPromosiController::class, 'index'])->name('admin.alat-promosi.index');
    Route::post('/alat-promosi', [AdminAlatPromosiController::class, 'store'])->name('admin.alat-promosi.store');
    Route::post('/alat-promosi/{id}', [AdminAlatPromosiController::class, 'update'])->name('admin.alat-promosi.update');
    Route::delete('/alat-promosi/{id}', [AdminAlatPromosiController::class, 'destroy'])->name('admin.alat-promosi.destroy');
    Route::post('/admin/affiliator/update-komisi-rekrut', [AdminAffiliatorController::class, 'updateKomisiRekrut'])->name('admin.affiliator.komisi_rekrut');
});

Route::middleware(['auth', 'role:affiliator'])->name('affiliate.')->group(function () {
    Route::get('/affiliate/dashboard', [AffiliateDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [AffiliateProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [AffiliateProfileController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/update-password', [AffiliateProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/produk-afiliasi', [AffiliateProdukAfiliasiController::class, 'index'])->name('produk.index');
    Route::post('/produk-afiliasi', [AffiliateProdukAfiliasiController::class, 'store'])->name('produk.store');
    Route::delete('/produk-afiliasi/{id}', [AffiliateProdukAfiliasiController::class, 'destroy'])->name('produk.destroy');
    Route::get('/komisi', [AffiliateKomisiController::class, 'index'])->name('komisi.index');
    Route::get('/komisi/pengajuan', [AffiliateKomisiController::class, 'createPengajuan'])->name('komisi.pengajuan');
    Route::post('/komisi/pengajuan', [AffiliateKomisiController::class, 'storePengajuan'])->name('komisi.store');
    Route::get('/komisi/konfirmasi', [AffiliateKomisiController::class, 'konfirmasi'])->name('komisi.konfirmasi');
    Route::get('/alat-promosi', [AffiliateAlatPromosiController::class, 'index'])->name('alat-promosi.index');
    Route::get('/alat-promosi/download/{filename}', [AffiliateAlatPromosiController::class, 'download'])->name('alat-promosi.download');
    Route::get('/notifikasi', [AffiliateNotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('/notifikasi/count', [AffiliateNotifikasiController::class, 'getCount'])->name('notifikasi.count');
});