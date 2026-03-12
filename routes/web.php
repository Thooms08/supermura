<?php

use App\Http\Controllers\AdminAffiliatorController;
use App\Http\Controllers\AdminAlatPromosiController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminHistoryKomisiController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminTokoController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminShippingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\AffiliateDashboardController;
use App\Http\Controllers\AffiliateProfileController;
use App\Http\Controllers\AdminKomisiController;
use App\Http\Controllers\AdminPengajuanKomisiController;
use App\Http\Controllers\AffiliateAlatPromosiController;
use App\Http\Controllers\AffiliateKomisiController;
use App\Http\Controllers\AffiliateNotifikasiController;
use App\Http\Controllers\AffiliateProdukAfiliasiController;
use App\Http\Controllers\DaftarAffiliateController;
use App\Http\Controllers\ProgramAffiliateController;
// Public Routes
Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/product/{produk}', [PublicController::class, 'show'])->name('product.show');
Route::get('/search-products', [PublicController::class, 'search'])->name('product.search');

Route::get('/program-affiliate', [ProgramAffiliateController::class, 'index'])->name('affiliate.program');


Route::get('/daftar-affiliate', [DaftarAffiliateController::class, 'index'])->name('affiliate.register');
Route::post('/daftar-affiliate', [DaftarAffiliateController::class, 'store'])->name('affiliate.register.store');

Route::post('/xendit/callback', [\App\Http\Controllers\PaymentCallbackController::class, 'handleXenditCallback']);
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

    Route::get('/admin', function () { return view('admin.index'); })->middleware('role:admin');
    Route::get('/profile-pengunjung', [PengunjungController::class, 'profile'])->name('pengunjung.profile');
    Route::post('/profile-pengunjung/update', [PengunjungController::class, 'update'])->name('pengunjung.profile.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/buy-now', [PublicController::class, 'buyNow'])->name('buy.now');
    Route::get('/pesanan-saya', [App\Http\Controllers\PengunjungPesananController::class, 'index'])->name('pengunjung.pesanan');
    Route::post('/pesanan-saya/batalkan/{id}', [App\Http\Controllers\PengunjungPesananController::class, 'batalkan'])->name('pengunjung.pesanan.batalkan');
    Route::get('/daftar-alamat', [PengunjungController::class, 'daftarAlamat'])->name('pengunjung.alamat.index');
    Route::get('/tambah-alamat', [PengunjungController::class, 'tambahAlamat'])->name('pengunjung.alamat.create');
    Route::post('/tambah-alamat', [PengunjungController::class, 'storeAlamat'])->name('pengunjung.alamat.store');
    Route::post('/pilih-alamat/{id}', [PengunjungController::class, 'pilihAlamat'])->name('pengunjung.alamat.select');
    Route::get('/alamat/{id}/edit', [PengunjungController::class, 'editAlamat'])->name('pengunjung.alamat.edit');
    Route::put('/alamat/{id}', [PengunjungController::class, 'updateAlamat'])->name('pengunjung.alamat.update');
    Route::delete('/alamat/{id}', [PengunjungController::class, 'destroyAlamat'])->name('pengunjung.alamat.destroy');
    Route::get('/keranjang', [App\Http\Controllers\CartController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/add', [App\Http\Controllers\CartController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang/update/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/delete/{id}', [App\Http\Controllers\CartController::class, 'destroy'])->name('keranjang.destroy');
    Route::post('/keranjang/checkout', [App\Http\Controllers\CartController::class, 'processToCheckout'])->name('keranjang.checkout.process');
    Route::post('/product/ulasan', [App\Http\Controllers\UlasanController::class, 'store'])->name('ulasan.store');
    Route::put('/ulasan/{id}', [UlasanController::class, 'update'])->name('ulasan.update');
    Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');
});

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('profile-toko', AdminTokoController::class);
    Route::put('/profile/update', [AdminDashboardController::class, 'updateProfile'])->name('admin.profile.update');
    Route::resource('produk', AdminProdukController::class);
    Route::delete('produk-foto/{foto}', [AdminProdukController::class, 'destroyFoto'])->name('produk-foto.destroy');
    Route::get('/orders', [App\Http\Controllers\AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::patch('/orders/{id}/status', [App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::get('/orders-process', [App\Http\Controllers\AdminOrderProcessController::class, 'index'])->name('admin.orders.process');
    Route::patch('/orders-process/{id}/fail', [App\Http\Controllers\AdminOrderProcessController::class, 'markAsFail'])->name('admin.orders.markAsFail');
    Route::patch('/orders-process/{id}/success', [App\Http\Controllers\AdminOrderProcessController::class, 'markAsSuccess'])->name('admin.orders.markAsSuccess');
    Route::get('/orders-success', [App\Http\Controllers\AdminOrderSuccessController::class, 'index'])->name('admin.orders.success');
    Route::get('/orders-fail', [App\Http\Controllers\AdminOrderFailController::class, 'index'])->name('admin.orders.fail');
    Route::get('/orders-pending-count', [App\Http\Controllers\AdminOrderController::class, 'getPendingCount'])->name('admin.orders.pendingCount');
    Route::get('/refunds-pending', [App\Http\Controllers\AdminRefundController::class, 'index'])->name('admin.refunds.pending');
    Route::patch('/refunds/{id}/update', [App\Http\Controllers\AdminRefundController::class, 'updateStatus'])->name('admin.refunds.update');
    Route::get('/refunds-count', [App\Http\Controllers\AdminRefundController::class, 'getPendingCount'])->name('admin.refunds.count');
    Route::get('/refunds-success', [App\Http\Controllers\AdminRefundController::class, 'successIndex'])->name('admin.refunds.success');
    Route::get('/refunds-success/search', [App\Http\Controllers\AdminRefundController::class, 'successSearch'])->name('admin.refunds.search');
    Route::get('/refunds-fail', [App\Http\Controllers\AdminRefundController::class, 'failIndex'])->name('admin.refunds.fail');
    Route::get('/refunds-fail/search', [App\Http\Controllers\AdminRefundController::class, 'failSearch'])->name('admin.refunds.search');
    Route::get('/ulasan', [App\Http\Controllers\AdminUlasanController::class, 'index'])->name('admin.ulasan.index');
    Route::delete('/ulasan/{id}', [App\Http\Controllers\AdminUlasanController::class, 'destroy'])->name('admin.ulasan.destroy');
    Route::get('/laporan', [App\Http\Controllers\AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/penjualan', [App\Http\Controllers\AdminLaporanController::class, 'penjualan'])->name('admin.laporan.penjualan');
    Route::get('/laporan/refund', [App\Http\Controllers\AdminLaporanController::class, 'refund'])->name('admin.laporan.refund');
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
    Route::get('/history-komisi', [AdminHistoryKomisiController::class, 'index'])->name('admin.history-komisi.index');
    Route::get('/alat-promosi', [AdminAlatPromosiController::class, 'index'])->name('admin.alat-promosi.index');
    Route::post('/alat-promosi', [AdminAlatPromosiController::class, 'store'])->name('admin.alat-promosi.store');
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