<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $page = (object)[
            'title' => 'Data Transaksi'
        ];

        $breadcrumb = (object)[
            'title' => 'Transaksi',
            'list' => ['Home', 'Transaksi']
        ];

        $activeMenu = 'transaksi';
        return view('transaksi.index', compact('page', 'breadcrumb', 'activeMenu'));
    }

public function getPenjualan(Request $request)
{
    if ($request->ajax()) {
        $data = PenjualanModel::orderBy('penjualan_tanggal', 'desc');
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('aksi', function ($transaksi) {
                $btn = '<button onclick="modalAction(\''.url('/transaksi/'.$transaksi->penjualan_id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button>';
                $btn .= '<button onclick="modalAction(\''.url('/transaksi/'.$transaksi->penjualan_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button>';
                $btn .= '<button onclick="modalAction(\''.url('/transaksi/'.$transaksi->penjualan_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button>';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
}

public function show_ajax($id)
{
    $transaksi = PenjualanModel::with(['user', 'details.barang'])->find($id);

    $page = (object)[
        'title' => 'Detail Transaksi'
    ];

    return view('transaksi.show_ajax', compact('transaksi', 'page'));
}

public function list(Request $request)
{
    $transactions = PenjualanModel::query();
    return datatables()->of($transactions)
        ->addIndexColumn()
        ->addColumn('aksi', function($row) {
            // Your action buttons here
        })
        ->rawColumns(['aksi'])
        ->toJson();
}


    public function create_ajax()
    {
        $page = (object)[
            'title' => 'Tambah Transaksi Baru'
        ];

        $barang = BarangModel::all();
        return view('transaksi.create_ajax', compact('page', 'barang'));
    }

    public function store_ajax(Request $request)
    {
        $request->validate([
            'pembeli' => 'required|string|max:50',
            'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.barang_id' => 'required|exists:m_barang,barang_id',
            'items.*.jumlah' => 'required|numeric|min:1'
        ]);

        try {
            DB::beginTransaction();

            // Buat transaksi
            $transaksi = PenjualanModel::create([
                'user_id' => auth()->id(),
                'pembeli' => $request->pembeli,
                'penjualan_kode' => $request->penjualan_kode,
                'penjualan_tanggal' => $request->penjualan_tanggal
            ]);

            // Simpan detail transaksi
            foreach ($request->items as $item) {
                $barang = BarangModel::find($item['barang_id']);
                
                PenjualanDetailModel::create([
                    'penjualan_id' => $transaksi->penjualan_id,
                    'barang_id' => $item['barang_id'],
                    'harga' => $barang->harga_jual,
                    'jumlah' => $item['jumlah']
                ]);

                // Kurangi stok barang
                $barang->stok -= $item['jumlah'];
                $barang->save();
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil disimpan',
                'data' => $transaksi
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan transaksi: '.$e->getMessage()
            ], 500);
        }
    }

    public function delete_ajax($id)
    {
        $transaksi = PenjualanModel::find($id);
        
        if (!$transaksi) {
            return response()->json([
                'status' => false,
                'message' => 'Data transaksi tidak ditemukan'
            ], 404);
        }

        try {
            DB::beginTransaction();
            
            // Kembalikan stok barang
            foreach ($transaksi->details as $detail) {
                $barang = $detail->barang;
                $barang->stok += $detail->jumlah;
                $barang->save();
            }
            
            // Hapus transaksi
            $transaksi->details()->delete();
            $transaksi->delete();
            
            DB::commit();
            
            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus transaksi: '.$e->getMessage()
            ], 500);
        }
    }
}