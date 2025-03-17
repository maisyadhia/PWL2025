<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

Route::get('/products', [ProductController::class, 'index'])->name('products.index');



// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/level', [LevelController::class, 'index']);
// Route::get('/kategori', [KategoriController::class, 'index']);
// Route::get('/user', [UserController::class, 'index']);
// Route::get('/user/tambah', [UserController::class, 'tambah']) ;
// Route::post('/user/tambah_simpan', [UserController::class, 'tambah_simpan']);
// Route::get ('/user/ubah/{id}', [UserController::class, 'ubah']);
// Route::put ('/user/ubah_simpan/{id}', [UserController::class, 'ubah_simpan']);
// Route::get ('/user/hapus/{id}', [UserController:: class, 'hapus']);

Route::resource('kategori', KategoriController::class);
Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
Route::put('/kategori/{id}', [KategoriController::class, 'update'])->name('kategori.update');

// Route::get ('/kategori',[KategoriController::class,'index']);

// Route::get('/kategori/create',[KategoriController::class,'create']);
// Route::get('/kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
// Route::post('/kategori',[KategoriController::class,'store']);
// Route::post('/kategori', [KategoriController::class, 'store'])->name('kategori.store');



// Route::get('/kategori/data', [KategoriController::class, 'data'])->name('kategori.data');

// Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori.index');
// //Route::get('/kategori/data', [KategoriDataTable::class, 'ajax'])->name('kategori.data');
// Route::get('/kategori/{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');


