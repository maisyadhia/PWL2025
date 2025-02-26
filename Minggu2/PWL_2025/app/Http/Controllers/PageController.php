<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\PageController;
Route::get('/', [PageController::class, 'index']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/articles/{id}', [PageController::class, 'articles']);

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ArticleController;
Route::get('/', HomeController::class);
Route::get('/about', AboutController::class);
Route::get('/articles/{id}', ArticleController::class);
class PageController extends Controller
{
    public function index()
    {
        return 'Selamat Datang';
    }
    
    public function about()
    {
        return 'Nama: [Isi Nama], NIM: [Isi NIM]';
    }
    
    public function articles($id)
    {
        return "Halaman Artikel dengan Id $id";
    }
}