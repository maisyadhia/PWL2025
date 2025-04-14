<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LevelModel;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');

            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal',
                'msgField' => [
                    'username' => ['Username atau password salah'],
                ],
            ]);
        }

        return redirect('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
    
    //update praktikum 4
    public function register(){
        $level = LevelModel::select('level_id','level_nama')->get();

        return View('auth.register')
        ->with('level', $level);
    }

    public function store_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama'     => 'required|string|max:100',
            'password' => 'required|min:5',
            'level_id' => 'required|integer',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ]);
        }

        // $request->validate([
        //     'username' => 'required|string|min:3|unique:m_user,username',
        //     'nama'     => 'required|string|max:100',
        //     'password' => 'required|min:5',
        //     'level_id' => 'required|integer',
        // ]);
    
        // Menyimpan user baru
        UserModel::create([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => bcrypt($request->password), // Enkripsi password
            'level_id' => $request->level_id,
        ]);
    
        // Mengirim respons JSON jika permintaan AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'status' => true,
                'message' => 'Registrasi Berhasil',
                'redirect' => url('login')
            ]);
        }
    
        // Jika bukan AJAX, redirect ke halaman utama dengan flash message
        //return redirect('/')->with('success', 'Registrasi berhasil');
        return redirect('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}
