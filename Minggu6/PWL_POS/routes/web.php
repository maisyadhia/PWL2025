<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
//use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\BarangController;




// Route::get('/products', [ProductController::class, 'index'])->name('products.index');


// Route::resource('supplier', SupplierController::class);
// Route::post('/supplier/list', [SupplierController::class, 'getSuppliers'])->name('supplier.list');



Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [WelcomeController:: class, 'index']);

Route::get('/', [WelcomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'user'], function () {
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
});

Route::group(['prefix' => 'level'], function () {
    Route::get('/', [LevelController::class, 'index'])->name('level.index'); // Menampilkan daftar level
    Route::post('/list', [LevelController::class, 'getLevels'])->name('level.list'); // DataTables JSON
    Route::get('/create', [LevelController::class, 'create'])->name('level.create'); // Form tambah
    Route::post('/', [LevelController::class, 'store'])->name('level.store'); // Simpan data baru
    Route::get('/{id}', [LevelController::class, 'show'])->name('level.show'); // Menampilkan detail level
    Route::get('/{id}/edit', [LevelController::class, 'edit'])->name('level.edit'); // Form edit
    Route::put('/{id}', [LevelController::class, 'update'])->name('level.update'); // Simpan perubahan
    Route::delete('/{id}', [LevelController::class, 'destroy'])->name('level.destroy'); // Hapus level
});

Route::group(['prefix' => 'kategori'], function () {
    Route::get('/', [KategoriController::class, 'index'])->name('kategori.index'); // Menampilkan daftar kategori
    Route::post('/list', [KategoriController::class, 'getKategori'])->name('kategori.list'); // Data JSON untuk DataTables
    Route::get('/create', [KategoriController::class, 'create'])->name('kategori.create'); // Form tambah kategori
    Route::post('/', [KategoriController::class, 'store'])->name('kategori.store'); // Simpan kategori baru
    Route::get('/{id}', [KategoriController::class, 'show'])->name('kategori.show'); // Detail kategori
    Route::get('/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit'); // Form edit kategori
    Route::put('/{id}', [KategoriController::class, 'update'])->name('kategori.update'); // Simpan perubahan kategori
    Route::delete('/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy'); // Hapus kategori
});

Route::group(['prefix'=>'barang'],function(){
    Route::get('/',[BarangController::class,'index']);
    Route::post('/list',[BarangController::class,'list']);
    Route::get('/create',[BarangController::class,'create']);
    Route::post('/',[BarangController::class,'store']);
    Route::get('/{id}/edit', [BarangController::class,'edit']);
    Route::put('/{id}', [BarangController::class,'update']);
    Route::delete('/{id}',[BarangController::class,'destroy']);
});

Route::group(['prefix' => 'supplier'], function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index'); // Menampilkan daftar supplier
    Route::post('supplier/list', [SupplierController::class, 'getSuppliers'])->name('supplier.list'); // Data JSON untuk DataTables
    Route::get('/create', [SupplierController::class, 'create'])->name('supplier.create'); // Form tambah supplier
    Route::post('/', [SupplierController::class, 'store'])->name('supplier.store'); // Simpan supplier baru
    Route::get('/{id}', [SupplierController::class, 'show'])->name('supplier.show'); // Detail supplier
    Route::get('/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit'); // Form edit supplier
    Route::put('/{id}', [SupplierController::class, 'update'])->name('supplier.update'); // Simpan perubahan supplier
    Route::delete('/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy'); // Hapus supplier
});
//Route::post('/supplier/list', [SupplierController::class, 'getSuppliersList'])->name('supplier.list');

//Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');



// Route::resource('kategori', KategoriController::class);
// Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
// Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');


Route::resource('levels', LevelController::class)->except(['create', 'edit']); 
Route::get('/levels/data', [LevelController::class, 'getData'])->name('levels.data');

Route::resource('kategori', KategoriController::class);
Route::resource('supplier', SupplierController::class);
//Route::resource('product', ProductController::class);


// Route::resource('levels', LevelController::class); // Menambahkan resource route untuk level
// Route::resource('levels', LevelController::class);
// Route::resource('categories', CategoryController::class);
// Route::resource('suppliers', SupplierController::class);
// Route::resource('products', ProductController::class);

// Route::resource('product', ProductController::class);
// Route::resource('supplier', SupplierController::class);
// Route::resource('level', LevelController::class);
// Route::resource('levels', LevelController::class);

// Route::get('/levels', [LevelController::class, 'index'])->name('levels.index');
// Route::get('/levels/data', [LevelController::class, 'getData'])->name('levels.data');





