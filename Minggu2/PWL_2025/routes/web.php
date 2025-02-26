<?php

use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/greeting', function () {
//     return view('hello', ['name' => 'Andi']);
// });
// Route::get('/greeting', function () {
//     return view('blog.hello', ['name' => 'Andi']);
// });


use App\Http\Controllers\WelcomeController;

Route::get('/greeting', [WelcomeController::class, 'greeting']);


// Pastikan rute ini ada di bagian ATAS sebelum dipanggil di bagian lain
Route::get('/user/profile', [UserProfileController::class, 'show'])->name('profile');


Route::get('/hello', function () {
    return 'Hello World';
});

Route::get('/world', function () {
    return 'World';
});

Route::get('/welcome', function () {
    return 'Selamat Datang';
});

Route::get('/about', function () {
    return 'NIM: 2341760114 ; NAMA: MAISYA DHIA';
});

Route::get('/posts/{postId}/comments/{commentId}', function ($postId, $commentId) {
    return "Pos ke-" . $postId . " Komentar ke-" . $commentId;
});

Route::get('/user/{name}', function ($name) {
    return 'Nama saya ' . $name;
});

Route::get('/articles/{id}', function ($id) {
    return "Halaman Artikel dengan ID " . $id;
});

Route::get('/user/{name?}', function ($name = 'John') {
    return 'Nama saya ' . $name;
});

Route::get('/user/profile', function () {
    return 'Ini halaman profile';
})->name('profile');

