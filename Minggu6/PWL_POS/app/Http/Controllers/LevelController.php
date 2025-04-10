<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    // Menampilkan halaman daftar level
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang tersedia dalam sistem'
        ];

        $activeMenu = 'level'; // Set menu yang sedang aktif
        
        $level = LevelModel::all();

        return view('level.index', compact('breadcrumb', 'level', 'page', 'activeMenu'));
    }

    //index ajax
    public function index_ajax()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar level yang terdaftar dalam sistem'
        ];

        $activeMenu = 'level';

        // Pastikan variabel ini dikirim ke view
        $level = LevelModel::all();

        return view('level.index_ajax', compact('breadcrumb', 'page', 'activeMenu', 'level'));
    }

    // Mengambil data level dalam format JSON untuk DataTables
    public function getLevels(Request $request)
    {
        if ($request->ajax()) {
            $data = LevelModel::select('level_id', 'level_kode', 'level_nama'); // Sesuaikan dengan tabel
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($level) {
                    return '
                        <a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a>
                        <a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a>
                        <form method="POST" action="' . url('/level/' . $level->level_id) . '" class="d-inline-block">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus?\');">Hapus</button>
                        </form>
                    ';
                })
                ->rawColumns(['aksi']) // Pastikan kolom aksi diproses sebagai HTML
                ->make(true);
        }
    }
    


    // Menampilkan halaman tambah level
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Level Baru'
        ];

        $activeMenu = 'level';

        return view('level.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data level baru
    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|unique:m_level,level_kode',
            'level_nama' => 'required|string'
        ]);
        
        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);        

        return redirect('/level')->with('success', 'Data Level berhasil ditambahkan');
    }

    // Menampilkan halaman edit level
    public function edit($id)
    {
        $level = LevelModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Data Level'
        ];

        $activeMenu = 'level';

        return view('level.edit', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    // Menyimpan perubahan data level
    public function update(Request $request, $id)
    {
        $request->validate([
            'level_kode' => 'required|string|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required|string'
        ]);

        $level = LevelModel::findOrFail($id);
        $level->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data Level berhasil diperbarui');
    }

    // Menghapus data level
    public function destroy($id)
    {
        $level = LevelModel::find($id);

        if (!$level) {
            return redirect('/level')->with('error', 'Data Level tidak ditemukan');
        }

        try {
            $level->delete();
            return redirect('/level')->with('success', 'Data Level berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data Level gagal dihapus karena masih terkait dengan data lain');
        }
    }

    public function show($id)
    {
    $level = LevelModel::findOrFail($id);

    $breadcrumb = (object) [
        'title' => 'Detail Level',
        'list' => ['Home', 'Level', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Level'
    ];

    $activeMenu = 'level';

    return view('level.show', compact('breadcrumb', 'page', 'level', 'activeMenu'));
    }

    // Tambah Data ajax
    public function create_ajax()
    {
        return view('level.create_ajax');
    }


    // Store ajax
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:5',
                'level_nama' => 'required|string|max:100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            LevelModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

     // Edit ajax
     public function edit_ajax(string $id)
     {
         $level = LevelModel::find($id);
         return view('level.edit_ajax', ['level' => $level]);
 
     }

       // Update ajax
    public function update_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:5',
                'level_nama' => 'required|string|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $check = LevelModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data level berhasil diubah',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data level tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }

     // Confirm ajax
     public function confirm_ajax(string $id)
     {
         $level = LevelModel::find($id);
 
         return view('level.confirm_ajax', ['level' => $level]);
     }
 
     // Delete ajax
     public function delete_ajax(Request $request, string $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $level = LevelModel::find($id);
             if ($level) {
                 $level->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data level berhasil dihapus',
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data level tidak ditemukan',
                 ]);
             }
         }
         return redirect('/');
     }
}