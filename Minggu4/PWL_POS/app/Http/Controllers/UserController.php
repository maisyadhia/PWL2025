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
        'level_id' => 2,
        'username' => 'manager_tiga',
        'nama' =>'Manager 3',
        'password' => Hash::make (' 12345')
    ];
    // UserModel::create($data);
    //UserModel::where('username', 'customer-1')->update($data);

    // Akses model UserModel untuk mengambil semua data dari tabel m_user
    // $user = UserModel::find(1);
    $user = UserModel::findOr(20, ['username', 'nama'], function (){
        abort (404) ;
    }) ;
    return view('user', ['data' => $user]);
}
}

