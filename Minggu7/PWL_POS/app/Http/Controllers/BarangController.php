<?php
   
 namespace App\Http\Controllers;
   
 use App\Models\BarangModel;
 use Illuminate\Http\Request;
 use Yajra\DataTables\DataTables;
 use App\Models\KategoriModel;
 use Illuminate\Support\Facades\Validator;
   
 class BarangController extends Controller {
     public function index(){
         $breadcrumb = (object) [
             'title' => 'Daftar barang',
             'list' => ['Home', 'barang']
         ];
      
         $page = (object) [
             'title' => 'Daftar barang yang terdaftar dalam sistem'
         ];
      
         $activeMenu = 'barang';
             return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

     public function list(){
        $barang = BarangModel::all();
            return DataTables::of($barang)
                ->addIndexColumn()
                ->addColumn('aksi', function ($barang) {
                    $btn  = '<button onclick="showDetail(' . $barang->barang_id . ')" class="btn btn-info btn-sm mr-1">Detail</button>';
                    $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm mr-1">Edit</a>';
                    $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                          . csrf_field() . method_field('DELETE') .
                          '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
                
    }
     
    public function create(){
        $breadcrumb = (object) [
            'title' => 'Tambah barang',
            'list' => ['Home', 'barang', 'Tambah']
        ];
     
        $page = (object) [
            'title' => 'Tambah barang baru'
        ];
     
        $activeMenu = 'barang';
            return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
     
    public function store(Request $request){
        $request->validate([
            'barang_kode' => 'required|string',
            'barang_nama' => 'required|string|max:100', 
            'kategori_id' => 'required|string', 
            'harga_beli' => 'required|integer',
            'harga_jual' =>'required|integer',
    ]);
     
        BarangModel::create([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id, 
            'harga_beli' => $request->harga_beli,
            'harga_jual' =>$request->harga_jual,
        ]);
            return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }
     
    public function edit(string $id){
        $barang = BarangModel::find($id);
            $breadcrumb = (object) [
                'title' => 'Edit barang',
                'list' => ['Home', 'barang', 'Edit']
            ];
     
            $page = (object) [
                'title' => 'Edit barang'
            ];
     
            $activeMenu = 'barang';
            return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'barang' => $barang, 'activeMenu' => $activeMenu]);
    }
     
    public function update(Request $request, string $id){
        $request->validate([
            'barang_kode' => 'required|string',
            'barang_nama' => 'required|string|max:100', 
            'kategori_id' => 'required|string', 
            'harga_beli' => 'required|integer',
            'harga_jual' =>'required|integer',
        ]);
     
        BarangModel::find($id)->update([
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'kategori_id' => $request->kategori_id, 
            'harga_beli' => $request->harga_beli,
            'harga_jual' =>$request->harga_jual,
        ]);
            return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }
     
    public function destroy(string $id){
        $check = BarangModel::find($id);
            if (!$check) {
                return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
            }
     
            try {
                BarangModel::destroy($id);
     
                return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
            } catch (\Illuminate\Database\QueryException $e) {
                return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
            }
    }

    public function show(string $id)
    {
    $barang = BarangModel::with('kategori')->findOrFail($id);

    $breadcrumb = (object) [
        'title' => 'Detail Barang',
        'list' => ['Home', 'Barang', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Barang'
    ];

    $activeMenu = 'barang';

    return view('barang.show', compact('barang', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function showAjax($id)
    {
        $barang = BarangModel::with('kategori')->findOrFail($id);
        return view('barang.show_ajax', compact('barang'));
    }

        // Create ajax
    public function create_ajax()
    {
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.create_ajax')->with('kategori', $kategori);
    }
    
    // Store ajax
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson) {
            $rules = [
                'kategori_id' => 'required|int',
                'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|integer',
                'harga_jual' => 'required|integer',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            BarangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    // Edit ajax
    public function edit_ajax(string $id)
    {
         $barang = BarangModel::find($id);
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }
 
    // Update ajax
    public function update_ajax(Request $request, string $id)
    {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'barang_kode' => 'required|string|min:3|unique:m_barang,barang_kode,' . $id . ',barang_id',
                 'barang_nama' => 'required|string|max:100',
                 'kategori_id' => 'required|integer',
                 'harga_beli' => 'required|integer',
                 'harga_jual' => 'required|integer',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
             $check = BarangModel::find($id);
             if ($check) {
                 $check->update($request->all());
 
                 return response()->json([
                     'status' => true,
                     'message' => 'Data barang berhasil diupdate',
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data barang tidak ditemukan',
                 ]);
             }
         }
         return redirect('/');
    }
 
    // Confirm ajax
    public function confirm_ajax(string $id)
    {
         $barang = BarangModel::find($id);
 
         return view('barang.confirm_ajax', ['barang' => $barang]);
    }
 
    // Delete ajax
    public function delete_ajax(Request $request, string $id)
    {
         if ($request->ajax() || $request->wantsJson()) {
             $barang = BarangModel::find($id);
 
             if ($barang) {
                 $barang->delete();
                 return response()->json([
                     'status' => true,
                     'message' => 'Data barang berhasil dihapus'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data barang tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
     }
 
}