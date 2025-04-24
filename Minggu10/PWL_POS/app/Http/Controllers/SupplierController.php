<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    // Menampilkan halaman daftar supplier
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar Supplier yang tersedia dalam sistem'
        ];

        $activeMenu = 'supplier'; // Set menu yang sedang aktif
        
        $supplier = SupplierModel::all(); 
        return view('supplier.index', compact('breadcrumb', 'supplier', 'page', 'activeMenu'));

    }

    public function list(Request $request)
{
    $query = Supplier::select('supplier_id', 'nama_supplier', 'supplier_kontak', 'alamat');

    if ($request->supplier_id) {
        $query->where('supplier_id', $request->supplier_id);
    }

    return DataTables::of($query)
        ->addIndexColumn()
        ->addColumn('aksi', function ($row) {
            return '<a href="'.route('supplier.edit', $row->supplier_id).'" class="btn btn-sm btn-warning">Edit</a>
                    <form action="'.route('supplier.destroy', $row->supplier_id).'" method="POST" style="display:inline;">
                        '.csrf_field().method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
        })
        ->rawColumns(['aksi'])
        ->make(true);
    }


    public function getSuppliers(Request $request)
    {
        $query = SupplierModel::query();

        if ($request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $data = $query->select('supplier_id', 'nama_supplier', 'supplier_kontak', 'alamat');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($supplier) {
                return '
                    <a href="' . route('supplier.show', $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a>
                    <a href="' . route('supplier.edit', $supplier->supplier_id) . '" class="btn btn-warning btn-sm">Edit</a>
                    <form method="POST" action="' . route('supplier.destroy', $supplier->supplier_id) . '" class="d-inline-block">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Yakin ingin menghapus?\');">Hapus</button>
                    </form>
                ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    // Menampilkan halaman tambah supplier
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Supplier',
            'list' => ['Home', 'Supplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Supplier Baru'
        ];

        $activeMenu = 'supplier';

        return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    // Menyimpan data supplier baru
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|string|unique:m_supplier,supplier_id|max:50',
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'supplier_kontak' => 'nullable|string|max:20',
        ]);

        SupplierModel::create([
            'supplier_id' => $request->supplier_id,
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'supplier_kontak' => $request->supplier_kontak,
        ]);

        return redirect('/supplier')->with('success', 'Data Supplier berhasil ditambahkan');
    }

    // Menampilkan halaman edit supplier
    public function edit($id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Edit Supplier',
            'list' => ['Home', 'Supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Data Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.edit', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    // Menyimpan perubahan data supplier
    public function update(Request $request, $id)
    {
        $request->validate([
            'supplier_id' => 'required|string|max:50|unique:m_supplier,supplier_id,' . $id . ',supplier_id',
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'nullable|string',
            'supplier_kontak' => 'nullable|string|max:20',
        ]);

        $supplier = SupplierModel::findOrFail($id);
        $supplier->update([
            'supplier_id' => $request->supplier_id,
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'supplier_kontak' => $request->supplier_kontak,
        ]);

        return redirect('/supplier')->with('success', 'Data Supplier berhasil diperbarui');
    }

    // Menghapus data supplier
    public function destroy($id)
    {
        $supplier = SupplierModel::find($id);

        if (!$supplier) {
            return redirect('/supplier')->with('error', 'Data Supplier tidak ditemukan');
        }

        try {
            $supplier->delete();
            return redirect('/supplier')->with('success', 'Data Supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/supplier')->with('error', 'Data Supplier gagal dihapus karena masih terkait dengan data lain');
        }
    }

    // Menampilkan detail supplier
    public function show($id)
    {
        $supplier = SupplierModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Detail Supplier',
            'list' => ['Home', 'Supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Supplier'
        ];

        $activeMenu = 'supplier';

        return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
    }

    // Create ajax
    public function create_ajax()
    {
        return view('supplier.create_ajax');
    }

      // Store ajax
      public function store_ajax(Request $request)
      {
          if ($request->ajax() || $request->wantsJson()) {
              $rules = [
                  'supplier_id' => 'required|string|max:50|unique:m_supplier,supplier_id',
                  'nama_supplier' => 'required|string|max:100',
                  'alamat' => 'nullable|string|max:100',
                  'supplier_kontak' => 'nullable|string|max:20',
              ];
      
              $validator = Validator::make($request->all(), $rules);
      
              if ($validator->fails()) {
                  return response()->json([
                      'status' => false,
                      'message' => 'Validasi Gagal',
                      'msgField' => $validator->errors(),
                  ]);
              }
      
              SupplierModel::create($request->all());
      
              return response()->json([
                  'status' => true,
                  'message' => 'Data supplier berhasil disimpan',
              ]);
          }
          return redirect('/');
      }
      

     // Edit ajax
     public function edit_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
         return view('supplier.edit_ajax', ['supplier' => $supplier]);
     }
 
 
     public function update_ajax(Request $request, string $id)
    {
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'supplier_id' => 'required|string|max:50|unique:m_supplier,supplier_id,' . $id . ',supplier_id',
            'nama_supplier' => 'required|string|max:100',
            'alamat' => 'nullable|string|max:100',
            'supplier_kontak' => 'nullable|string|max:20',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $check = SupplierModel::find($id);
        if ($check) {
            $check->update($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data supplier berhasil diubah',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Data supplier tidak ditemukan',
            ]);
        }
    }
        return redirect('/');
    }
 
    public function confirm_ajax(string $id)
    {
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }
    
    public function delete_ajax(Request $request, string $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil dihapus',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data supplier tidak ditemukan',
                ]);
            }
        }
        return redirect('/');
    }
    public function export_excel()
    {
        // Ambil data supplier
        $supplier = SupplierModel::select('supplier_nama', 'supplier_telepon', 'supplier_alamat')
            ->orderBy('supplier_nama')
            ->get();

        // Load PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Supplier');
        $sheet->setCellValue('C1', 'Telepon');
        $sheet->setCellValue('D1', 'Alamat');

        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        // Isi data
        $no = 1;
        $baris = 2;
        foreach ($supplier as $s) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $s->supplier_nama);
            $sheet->setCellValue('C' . $baris, $s->supplier_telepon);
            $sheet->setCellValue('D' . $baris, $s->supplier_alamat);
            $baris++;
            $no++;
        }

        // Auto-size kolom
        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Supplier');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Supplier ' . date('Y-m-d H-i-s') . '.xlsx';

        // Header response
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit();
    }

    public function export_pdf()
    {
        ini_set('max_execution_time', 300); // biar tidak timeout
        // Ambil data supplier lengkap dengan kontak dan alamat
        $supplier = SupplierModel::select('supplier_nama', 'supplier_telepon', 'supplier_alamat')
            ->orderBy('supplier_nama')
            ->get();

        // Load ke view dengan data yang dibutuhkan
        $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption(['isRemoteEnabled' => true]);

        return $pdf->stream('Data Supplier ' . date('Y-m-d H:i:s') . '.pdf');
    }
}
