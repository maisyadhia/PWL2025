<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;

Route::pattern('id', '[0-9]+');

// Auth routes
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'postlogin']);
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/register',[AuthController::class, 'register'])->name('register');
Route::post('/register',[AuthController::class, 'store_user'])->name('store_user');


// Protected routes
Route::middleware(['auth'])->group(function () {

    Route::get('/', [WelcomeController:: class, 'index'])->name('dashboard');;

    // Route::get('/', function () {
    //     return view('dashboard'); // atau view lain yang kamu gunakan
    // })->name('dashboard');
    
     Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
     Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
     
    Route::middleware(['authorize:ADM,MNG,STF'])->group(function () {
        // Stok Barang
        Route::prefix('stok')->group(function () {
            Route::get('/', [StokController::class, 'index'])->name('stok.index');
            Route::post('/list', [StokController::class, 'getStok'])->name('stok.list');
            Route::post('/ajax', [StokController::class, 'store_ajax'])->name('stok.store_ajax');
            Route::get('/create_ajax', [StokController::class, 'create_ajax']);
            Route::get('/{id}/edit_ajax', [StokController::class, 'edit_ajax']);
            Route::put('/{id}/update_ajax', [StokController::class, 'update_ajax'])->name('stok.update_ajax');
            Route::get('/{id}/show_ajax', [StokController::class, 'show_ajax']);
            Route::delete('/{id}/delete_ajax', [StokController::class, 'delete_ajax'])->name('stok.delete_ajax');
            Route::get('/import', [StokController::class, 'import']);
            Route::post('/import_ajax', [StokController::class, 'import_ajax']);
            Route::get('/export_excel', [StokController::class, 'export_excel']);
            Route::get('/export_pdf', [StokController::class, 'export_pdf']);
        });
        // Transaksi Penjualan
        Route::prefix('transaksi')->group(function() {
            Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index');
           Route::post('/list', [TransaksiController::class, 'list'])->name('transaksi.list');
            Route::get('/create_ajax', [TransaksiController::class, 'create_ajax'])->name('transaksi.create_ajax');
            Route::post('/ajax', [TransaksiController::class, 'store_ajax'])->name('transaksi.store_ajax');
            Route::get('/{id}/show_ajax', [TransaksiController::class, 'show_ajax'])->name('transaksi.show_ajax');
            Route::get('/{id}/edit_ajax', [TransaksiController::class, 'edit_ajax'])->name('transaksi.edit_ajax');
            Route::put('/{id}/update_ajax', [TransaksiController::class, 'update_ajax'])->name('transaksi.update_ajax');
            Route::delete('/{id}/delete_ajax', [TransaksiController::class, 'delete_ajax'])->name('transaksi.delete_ajax');
            Route::post('/transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
            Route::post('/transaksi/store-ajax', [TransaksiController::class, 'storeAjax'])->name('transaksi.store_ajax');
            Route::get('/api/barang/{id}', function($id) {
                return App\Models\BarangModel::findOrFail($id);
            });

        });
    });
    // Route::middleware(['authorize:ADM'])->prefix('level')->group(function () {
    //     Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan daftar level
    //     Route::post('/list', [LevelController::class, 'getLevels'])->name('level.list'); // DataTables JSON
    //     Route::get('/create', [LevelController::class, 'create'])->name('level.create'); // Form tambah
    //     Route::post('/', [LevelController::class, 'store'])->name('level.store'); // Simpan data baru
    //     Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
    //     Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
    //     Route::get('/{id}', [LevelController::class, 'show'])->name('level.show'); // Menampilkan detail level
    //     Route::get('/{id}/show_ajax', [LevelController::class, 'show_ajax']);
    //     Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax'])->name('level.edit_ajax'); // Form edit (AJAX)
    //     Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax'); // Simpan perubahan (AJAX)
    //     Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax'); 
    //     Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax');
    //     Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit'); // Form edit
    //     Route::put('/{id}', [LevelController::class, 'update'])->name('level.update'); // Simpan perubahan
    //     Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy'); // Hapus level
    //     Route::get('/import', [LevelController::class, 'import']);
    //     Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
    //     Route::get('/export_excel', [LevelController::class, 'export_excel']);
    //     Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    // });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('level')->group(function () {
        Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan daftar level
        Route::post('/list', [LevelController::class, 'getLevels'])->name('level.list'); // DataTables JSON
        Route::get('/create', [LevelController::class, 'create'])->name('level.create'); // Form tambah
        Route::post('/', [LevelController::class, 'store'])->name('level.store'); // Simpan data baru
        Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
        Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
        Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');
        Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax'])->name('level.edit_ajax');
        Route::put('/{id}', [LevelController::class, 'update'])->name('level.update');
        Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax');
        Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax'); 
        Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax');
        Route::get('/{id}', [LevelController::class, 'show'])->name('level.show'); // Menampilkan detail level
        Route::put('/{id}', [LevelController::class, 'update'])->name('level.update'); // Simpan perubahan
        Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy'); // Hapus level
        Route::get('/import', [LevelController::class, 'import']);
        Route::post('/import_ajax', [LevelController::class, 'import_ajax']);
        Route::get('/export_excel', [LevelController::class, 'export_excel']);
        Route::get('/export_pdf', [LevelController::class, 'export_pdf']);
    });
    
    // Route::middleware(['auth', 'authorize:ADM'])->group(function () {

    //     Route::prefix('level')->group(function () {

    //     Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan daftar level
    //     Route::post('/list', [LevelController::class, 'getLevels'])->name('level.list'); // DataTables JSON
    //     Route::get('/create', [LevelController::class, 'create'])->name('level.create'); // Form tambah
    //     Route::post('/', [LevelController::class, 'store'])->name('level.store'); // Simpan data baru
    //     Route::get('/create_ajax', [LevelController::class, 'create_ajax']); // Menampilkan halaman form tambah level Ajax
    //     Route::post('/ajax', [LevelController::class, 'store_ajax']); // Menyimpan data level baru Ajax
    //     Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit');
    //     Route::get('/{id}/edit_ajax', [LevelController::class, 'edit_ajax'])->name('level.edit_ajax');
    //     Route::put('/{id}', [LevelController::class, 'update'])->name('level.update');
    //     Route::put('/{id}/update_ajax', [LevelController::class, 'update_ajax'])->name('level.update_ajax');
    //     Route::get('/{id}/delete_ajax', [LevelController::class, 'confirm_ajax'])->name('level.confirm_ajax'); 
    //     Route::delete('/{id}/delete_ajax', [LevelController::class, 'delete_ajax'])->name('level.delete_ajax');
    //     Route::get('/{id}', [LevelController::class, 'show'])->name('level.show'); // Menampilkan detail level
    //     Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit'); // Form edit
    //     Route::put('/{id}', [LevelController::class, 'update'])->name('level.update'); // Simpan perubahan
    //     Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy'); // Hapus level
    // });

    // });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('user')->group(function (){
    //Route::group(['prefix' => 'user'], function () {
        Route::get ('/', [UserController::class, 'index']);// menampilkan halaman awal user
        Route::post('/list', [UserController::class,'list' ]);// menampilkan data user dalam bentuk json untuk datatables
        Route::get ('/create', [UserController::class, 'create']);// menampilkan halaman form tambah user
        Route::post('/', [UserController::class,'store']);// menyimpan data user baru
        Route::get('/create_ajax', [UserController::class, 'create_ajax']);// Menampilkan halaman form tambah user Ajax
        Route::post('/ajax', [UserController::class, 'store_ajax']);// Menyimpan data user baru Ajax
        Route::get('/{id}', [UserController::class, 'show']);// menampilkan detail user
        Route::get('/{id}/edit', [UserController::class,'edit']); // menampilkan halaman form edit user
        Route::put ('/{id}', [UserController::class, 'update']);// menyimpan perubahan data user
        Route::get('/{id}/edit_ajax', [UserController::class, 'edit_ajax']);// Menampilkan halaman form edit user dengan Ajax
        Route::put('/{id}/update_ajax', [UserController::class, 'update_ajax']);// Menyimpan perubahan data user dengan Ajax
        Route::get('/{id}/delete_ajax', [UserController::class, 'confirm_ajax']); // Tampilkan konfirmasi delete
        Route::delete('/{id}/delete_ajax', [UserController::class, 'delete_ajax']); // Hapus user AJAX
        Route::delete('/{id}', [UserController::class, 'destroy']); // menghapus data user
        Route::get('/import', [UserController::class, 'import']);
        Route::post('/import_ajax', [UserController::class, 'import_ajax']);
        Route::get('/export_excel', [UserController::class, 'export_excel']);
        Route::get('/export_pdf', [UserController::class, 'export_pdf']);
    });

    
    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('kategori')->group(function () {
    //Route::group(['prefix' => 'kategori'], function () {
        Route::get('/', [KategoriController::class, 'index'])->name('kategori.index'); // Menampilkan daftar kategori
        Route::post('/list', [KategoriController::class, 'getKategori'])->name('kategori.list'); // Data JSON untuk DataTables
        Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create'); // Form tambah kategori
        Route::post('/', [KategoriController::class, 'store'])->name('kategori.store'); // Simpan kategori baru
        Route::get('/create_ajax', [KategoriController::class, 'create_ajax'])->name('kategori.create_ajax');// Form tambah kategori (AJAX)
        Route::post('/ajax', [KategoriController::class, 'store_ajax'])->name('kategori.store_ajax'); // Simpan kategori baru (AJAX)
        Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show'); // Detail kategori
        Route::get('/{id}/edit_ajax', [KategoriController::class, 'edit_ajax'])->name('kategori.edit_ajax'); // Form edit kategori (AJAX)
        Route::put('/{id}/update_ajax', [KategoriController::class, 'update_ajax'])->name('kategori.update_ajax'); // Simpan perubahan kategori (AJAX)
        Route::get('/{id}/delete_ajax', [KategoriController::class, 'confirm_ajax'])->name('kategori.confirm_ajax'); // Konfirmasi hapus (AJAX)
        Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show'); // Detail kategori
        Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit'); // Form edit kategori
        Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update'); // Simpan perubahan kategori
        Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy'); // Hapus kategori
        Route::get('/import', [KategoriController::class, 'import']);
        Route::post('/import_ajax', [KategoriController::class, 'import_ajax']);
        Route::get('/export_excel', [KategoriController::class, 'export_excel']);
        Route::get('/export_pdf', [KategoriController::class, 'export_pdf']);
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('barang')->group(function () {
    //Route::group(['prefix'=>'barang'],function(){
        Route::get('/',[BarangController::class,'index']);
        Route::post('/list',[BarangController::class,'list']);
        Route::get('/create',[BarangController::class,'create']);
        Route::post('/',[BarangController::class,'store']);
        Route::get('/create_ajax', [BarangController::class, 'create_ajax']);
        Route::post('/ajax', [BarangController::class, 'store_ajax']);
        Route::get('/{id}', [BarangController::class, 'show'])->name('barang.show');
        Route::get('/{id}/edit_ajax', [BarangController::class, 'edit_ajax']);
        Route::put('/{id}/update_ajax', [BarangController::class, 'update_ajax']);
        Route::get('/{id}/delete_ajax', [BarangController::class, 'confirm_ajax']);
        Route::get('/{id}/detail', [BarangController::class, 'showAjax']);
        Route::get('/{id}/edit', [BarangController::class,'edit']);
        Route::put('/{id}', [BarangController::class,'update']);
        Route::delete('/{id}',[BarangController::class,'destroy']);
        Route::get('/import', [BarangController::class, 'import']);
        Route::post('/import_ajax', [BarangController::class, 'import_ajax']);
        Route::get('/export_excel', [BarangController::class, 'export_excel']);
        Route::get('/export_pdf', [BarangController::class, 'export_pdf']);
        
    });

    Route::middleware(['authorize:ADM,MNG,STF'])->prefix('supplier')->group(function () {
    //Route::group(['prefix' => 'supplier'], function () {
        Route::get('/', [SupplierController::class, 'index'])->name('supplier.index'); // Menampilkan daftar supplier
        Route::post('supplier/list', [SupplierController::class, 'getSuppliers'])->name('supplier.list'); // Data JSON untuk DataTables
        Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create'); // Form tambah supplier
        Route::post('/', [SupplierController::class, 'store'])->name('supplier.store'); // Simpan supplier baru
        Route::get('/create_ajax', [SupplierController::class, 'create_ajax'])->name('supplier.create_ajax'); // Form tambah supplier (AJAX)
        Route::post('/ajax', [SupplierController::class, 'store_ajax'])->name('supplier.store_ajax'); // Simpan supplier baru (AJAX)
        Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show'); // Detail supplier
        Route::get('/{id}/edit_ajax', [SupplierController::class, 'edit_ajax'])->name('supplier.edit_ajax'); // Form edit supplier (AJAX)
        Route::put('/{id}/update_ajax', [SupplierController::class, 'update_ajax'])->name('supplier.update_ajax'); // Simpan perubahan supplier (AJAX)
        Route::get('/{id}/delete_ajax', [SupplierController::class, 'confirm_ajax'])->name('supplier.confirm_ajax'); // Konfirmasi hapus (AJAX)
        Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show'); // Detail supplier
        Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit'); // Form edit supplier
        Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update'); // Simpan perubahan supplier
        Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy'); // Hapus supplier
        Route::get('/import', [SupplierController::class, 'import']);
        Route::post('/import_ajax', [SupplierController::class, 'import_ajax']);
        Route::get('/export_excel', [SupplierController::class, 'export_excel']);
        Route::get('/export_pdf', [SupplierController::class, 'export_pdf']);
    });
});

