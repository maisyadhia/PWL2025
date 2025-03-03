<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
{
    // Mengupdate data user dengan username 'customer-1'
    $data = [
        'nama' => 'Pelanggan Pertama'
    ];

    UserModel::where('username', 'customer-1')->update($data);

    // Akses model UserModel untuk mengambil semua data dari tabel m_user
    $user = UserModel::all();
    return view('user', ['data' => $user]);
}
}

