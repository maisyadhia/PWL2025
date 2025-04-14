<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 use Illuminate\Support\Facades\Storage;
 use App\Models\UserModel;
 
 class ProfileController extends Controller
 {

    public function index()
    {
        // Jika kamu punya data user, kirim datanya ke view
        return view('profile.index');
    }

     public function edit()
     {
         $user = auth()->user();
 
         return view('profile.edit', [
             'user' => $user,
             'activeMenu' => 'profile',
             'breadcrumb' => (object)[
                 'title' => 'Profil Saya',
                 'list' => [
                     route('dashboard'),
                     'Profil'
                 ]
             ]
         ]);
     }
 
     
 
 
     public function update(Request $request)
     {
         $request->validate([
             'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
         ]);
     
         $user = auth()->user();
         
         try {
             if ($request->hasFile('foto')) {
                 // Hapus foto lama jika ada
                 if ($user->foto && file_exists(public_path($user->foto))) {
                     unlink(public_path($user->foto));
                 }
     
                 // Buat folder jika belum ada
                 if (!file_exists(public_path('uploads/user'))) {
                     mkdir(public_path('uploads/user'), 0755, true);
                 }
     
                 // Generate nama file
                 $filename = 'user_'.$user->id.'_'.time().'.'.$request->file('foto')->getClientOriginalExtension();
                 
                 // Simpan file ke public/uploads/user
                 $request->file('foto')->move(public_path('uploads/user'), $filename);
                 
                 // Update database dengan path relatif
                 $user->foto = 'uploads/user/'.$filename;
                 $user->save();
     
                 return back()->with('success', 'Foto profil berhasil diupdate!');
             }
         } catch (\Exception $e) {
             return back()->with('error', 'Gagal mengupload foto: '.$e->getMessage());
         }
     
         return back()->with('error', 'Tidak ada file yang diupload');
     }
 }