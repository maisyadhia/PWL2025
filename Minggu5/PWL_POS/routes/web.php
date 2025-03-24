<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WelcomeController;



Route::get('/products', [ProductController::class, 'index'])->name('products.index');



Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'user'], function () {
    Route::get ('/', [UserController::class, 'index']);// menampilkan halaman awal user
    Route::post('/list', [UserController::class,'list' ]);// menampilkan data user dalam bentuk json untuk datatables
    Route::get ('/create', [UserController::class, 'create']);// menampilkan halaman form tambah user
    Route::post('/', [UserController::class,'store']);// menyimpan data user baru
    Route::get('/{id}', [UserController::class, 'show']);// menampilkan detail user
    Route::get('/{id}/edit', [UserController::class,'edit']); // menampilkan halaman form edit user
    Route::put ('/{id}', [UserController::class, 'update']);// menyimpan perubahan data user
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


// Route::resource('kategori', KategoriController::class);
// Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
// Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');

Route::get('/', [WelcomeController:: class, 'index']);

Route::get('/', [WelcomeController::class, 'index'])->name('home');
Route::resource('levels', LevelController::class)->except(['create', 'edit']); 
Route::get('/levels/data', [LevelController::class, 'getData'])->name('levels.data');

Route::resource('kategori', KategoriController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('product', ProductController::class);


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





