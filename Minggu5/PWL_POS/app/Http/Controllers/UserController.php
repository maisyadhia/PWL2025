<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // Menampilkan halaman awal user
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];
        
        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];
        
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');
    
        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">'
                    .csrf_field() . method_field('DELETE') .
                    '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                return $btn;
            })
            ->rawColumns(['aksi']) 
            ->make(true);
    }
    
    public function create(){
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];

    $page = (object) [
        'title' => 'Tambah user baru'
    ];

    $level = LevelModel::all(); // Ambil data level untuk ditampilkan di form
    $activeMenu = 'user'; // Set menu yang sedang aktif

    return view('user.create', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
    }
    public function store(Request $request){
    // Validasi input
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username',
        'nama' => 'required|string|max:100',
        'password' => 'required|min:5',
        'level_id' => 'required|integer'
    ]);

    // Simpan data user baru ke database
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => bcrypt($request->password), // Enkripsi password sebelum disimpan
        'level_id' => $request->level_id
    ]);
    // Redirect ke halaman user dengan pesan sukses
    return redirect('/user')->with('success', 'Data user berhasil disimpan');
    }
    // Menampilkan detail user
    public function show(string $id)
    {
    $user = UserModel::with('level')->find($id);

    $breadcrumb = (object) [
        'title' => 'Detail User',
        'list' => ['Home', 'User', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail User'
    ];

    $activeMenu = 'user'; // Set menu yang sedang aktif

    return view('user.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'activeMenu' => $activeMenu
    ]);
    }
    // Menampilkan halaman form edit user
    public function edit(string $id)
    {
    $user = UserModel::find($id); // Menemukan data user berdasarkan ID
    $level = LevelModel::all();   // Mengambil semua data level

    $breadcrumb = (object) [
        'title' => 'Edit User',
        'list' => ['Home', 'User', 'Edit']
    ];

    $page = (object) [
        'title' => 'Edit User'
    ];

    $activeMenu = 'user'; // Set menu yang sedang aktif

    // Mengembalikan view dengan data user dan level
    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'user' => $user,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
    }
    // Menyimpan perubahan data user
    public function update(Request $request, string $id)
    {
    // Validasi input dari form
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
        'nama' => 'required|string|max:100', // nama harus diisi dan maksimal 100 karakter
        'password' => 'nullable|min:5', // password bisa diisi (minimal 5 karakter)
        'level_id' => 'required|integer' // level_id harus diisi dan berupa angka
    ]);

    // Temukan user berdasarkan ID dan update data
    $user = UserModel::find($id);
    
    if (!$user) {
        return redirect()->route('user.index')->with('error', 'User tidak ditemukan.');
    }

    // Update data user
    $user->update([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => $request->password ? bcrypt($request->password) : $user->password, // Enkripsi password jika diubah
        'level_id' => $request->level_id
    ]);

    return redirect('/user')->with('success', 'Data user berhasil diubah');
    }
    // Menghapus data user
    public function destroy(string $id)
    {
    // Cek apakah user dengan ID yang dimaksud ada
    $user = UserModel::find($id);
    if (!$user) {
        // Jika user tidak ditemukan, redirect ke halaman user dengan pesan error
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        // Menghapus data user berdasarkan ID
        UserModel::destroy($id);
        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {
        // Jika terjadi error saat menghapus, redirect dengan pesan error
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}

}
