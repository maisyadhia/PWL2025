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
    // $data = [
    //     'level_id' => 2,
    //     'username' => 'manager_tiga',
    //     'nama' =>'Manager 3',
    //     'password' => Hash::make (' 12345')
    // ];
    // UserModel::create($data);
    //UserModel::where('username', 'customer-1')->update($data);

    // Akses model UserModel untuk mengambil semua data dari tabel m_user
    // $user = UserModel::find(1);
    // $user = UserModel::where('level_id', 2) -> count();
    // dd($user);
   // Menghitung jumlah user dengan level_id = 2
    //$jumlahUser = UserModel::where('level_id', 2)->count(); 
    $user = UserModel::create(
        [
            'username' => 'manager11',
            'nama' => 'Manager11',
            'password' => Hash::make('12345'),
            'level_id' => 2
        ],
    ) ;

    $user->username = 'manager12';

    $user->save();

    $user->wasChanged(); // true
    $user->wasChanged ('username'); // true
    $user->wasChanged ( ['username', 'level_id']); // true
    $user->wasChanged ('nama'); // false
    $user->wasChanged (['nama', 'username']); // true
    dd($user->wasChanged (['nama', 'username'])) ; // true
    // return view('user', ['data' => $user]);
}
}

