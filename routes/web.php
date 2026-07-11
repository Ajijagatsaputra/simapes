<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Pelanggan;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ── Landing / Login ──────────────────────────────────────────────────────
Route::get('/', fn() => view('auth.login'));

Route::get('/dashboard', function () {
    $role = auth()->user()->role ?? 'pelanggan';
    if ($role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('pelanggan.dashboard');
})->middleware(['auth'])->name('dashboard');

// ══════════════════════════════════════════════════════════════════════════
// ADMIN ROUTES — Prefix: /admin
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Manajemen Pelanggan
        Route::get('/pelanggan', [Admin\PelangganController::class, 'index'])->name('pelanggan.index');
        Route::delete('/pelanggan/bulk-delete', [Admin\PelangganController::class, 'bulkDestroy'])->name('pelanggan.bulkDestroy');
        Route::delete('/pelanggan/{id}', [Admin\PelangganController::class, 'destroy'])->name('pelanggan.destroy');

        // Manajemen Produk
        Route::get('/produk', [Admin\ProdukController::class, 'index'])->name('produk.index');
        Route::post('/produk', [Admin\ProdukController::class, 'store'])->name('produk.store');
        Route::put('/produk/{id}', [Admin\ProdukController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{id}', [Admin\ProdukController::class, 'destroy'])->name('produk.destroy');

        // Manajemen Supplier
        Route::get('/supplier', [Admin\SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/supplier', [Admin\SupplierController::class, 'store'])->name('supplier.store');
        Route::put('/supplier/{id}', [Admin\SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/supplier/{id}', [Admin\SupplierController::class, 'destroy'])->name('supplier.destroy');

        // Manajemen Pesanan
        Route::get('/pesanan', [Admin\PesananController::class, 'index'])->name('pesanan.index');
        Route::post('/pesanan', [Admin\PesananController::class, 'store'])->name('pesanan.store');
        Route::get('/pesanan/{id}/nota', [Admin\PesananController::class, 'nota'])->name('pesanan.nota');
        Route::put('/pesanan/{id}', [Admin\PesananController::class, 'update'])->name('pesanan.update');
        Route::patch('/pesanan/{id}/status', [Admin\PesananController::class, 'updateStatus'])->name('pesanan.updateStatus');
        Route::delete('/pesanan/{id}', [Admin\PesananController::class, 'destroy'])->name('pesanan.destroy');

        // Pembayaran (Termin / DP)
        Route::get('/pesanan/{id}/pembayaran', [Admin\PembayaranController::class, 'show'])->name('pesanan.pembayaran');
        Route::post('/pesanan/{id}/pembayaran', [Admin\PembayaranController::class, 'store'])->name('pesanan.pembayaran.store');
        Route::delete('/pesanan/{id}/pembayaran/{pembayaranId}', [Admin\PembayaranController::class, 'destroy'])->name('pesanan.pembayaran.destroy');

        // Prediksi (Holt-Winters)
        Route::get('/prediksi', [Admin\PrediksiController::class, 'index'])->name('prediksi.index');
        Route::get('/prediksi/print-po', [Admin\PrediksiController::class, 'printPo'])->name('prediksi.printPo');

        // Laporan
        Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/cetak', [Admin\LaporanController::class, 'cetak'])->name('laporan.cetak');
        Route::get('/laporan/excel', [Admin\LaporanController::class, 'excel'])->name('laporan.excel');
    });

// ══════════════════════════════════════════════════════════════════════════
// PELANGGAN ROUTES — Prefix: /pelanggan
// ══════════════════════════════════════════════════════════════════════════
Route::middleware(['auth', 'role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [Pelanggan\DashboardController::class, 'index'])->name('dashboard');

        // Katalog Produk
        Route::get('/katalog', [Pelanggan\KatalogController::class, 'index'])->name('katalog');

        // Pesanan
        Route::get('/pesanan', [Pelanggan\PesananController::class, 'index'])->name('pesanan.index');
        Route::get('/pesanan/buat', [Pelanggan\PesananController::class, 'create'])->name('pesanan.create');
        Route::post('/pesanan', [Pelanggan\PesananController::class, 'store'])->name('pesanan.store');
        // PENTING: route statis harus sebelum wildcard {id}
        Route::get('/pesanan/template-excel', [Pelanggan\PesananController::class, 'downloadTemplate'])->name('pesanan.template');
        Route::post('/pesanan/upload-excel', [Pelanggan\PesananController::class, 'uploadExcel'])->name('pesanan.upload');
        Route::get('/pesanan/{id}', [Pelanggan\PesananController::class, 'show'])->name('pesanan.show');

        // Riwayat Pesanan
        Route::get('/riwayat', [Pelanggan\RiwayatController::class, 'index'])->name('riwayat');

        // Profil Akun
        Route::get('/profil', [Pelanggan\ProfilController::class, 'edit'])->name('profil.edit');
        Route::patch('/profil', [Pelanggan\ProfilController::class, 'update'])->name('profil.update');
    });

// ── Authenticated (shared) ───────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
