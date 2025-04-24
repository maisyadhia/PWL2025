<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanModel;
use App\Models\PenjualanDetailModel;
use App\Models\BarangModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\UserModel; 

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
        $data = PenjualanModel::orderBy('penjualan_tanggal', 'asc');
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

    public function create_ajax()
    {
        $page = (object)[
            'title' => 'Tambah Transaksi Baru'
        ];

        $barang = BarangModel::all();
        return view('transaksi.create_ajax', compact('page', 'barang'));
    }

    public function storeAjax(Request $request)
{

    $validated = $request->validate([
        'pembeli' => 'required|string|max:50',
        'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
        'penjualan_tanggal' => 'required|date',
        'items' => 'required|array|min:1',
        'items.*.barang_id' => 'required|exists:m_barang,barang_id',
        'items.*.jumlah' => 'required|integer|min:1'
    ]);

    DB::beginTransaction();
    try {
        // Buat transaksi
        $transaksi = PenjualanModel::create([
            'user_id' => auth()->id(),
            'pembeli' => $validated['pembeli'],
            'penjualan_kode' => $validated['penjualan_kode'],
            'penjualan_tanggal' => $validated['penjualan_tanggal']
        ]);

        // Proses items
        foreach ($validated['items'] as $item) {
            $barang = BarangModel::find($item['barang_id']);
            
            // Buat detail penjualan
            PenjualanDetailModel::create([
                'penjualan_id' => $transaksi->penjualan_id,
                'barang_id' => $item['barang_id'],
                'harga' => $barang->harga_jual,
                'jumlah' => $item['jumlah']
            ]);
        }

        DB::commit();
        return redirect('transaksi
        ');
        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
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

    public function list(Request $request)
{
    $transactions = PenjualanModel::with('user')
        ->select('t_penjualan.*')
        ->when($request->user, function($query) use ($request) {
            return $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', $request->user);
            });
        })
        ->when($request->tanggal, function($query) use ($request) {
            return $query->whereDate('penjualan_tanggal', $request->tanggal);
        })
        ->when($request->pembeli, function($query) use ($request) {
            return $query->where('pembeli', 'like', '%'.$request->pembeli.'%');
        });

    return DataTables::of($transactions)
        ->addIndexColumn()
        ->addColumn('total', function($transaksi) {
            return $transaksi->details->sum(function($detail) {
                return $detail->harga * $detail->jumlah;
            });
        })
        ->addColumn('user_nama', function($transaksi) {
            $level = $transaksi->user->level->level_nama ?? 'Kasir';
            $badgeClass = '';
            
            if ($level == 'Administrator') {
                $badgeClass = 'badge-administrator';
            } elseif ($level == 'Manager') {
                $badgeClass = 'badge-manager';
            } else {
                $badgeClass = 'badge-staff';
            }
            
            return '<span class="badge badge-kasir ' . $badgeClass . '">' . ($transaksi->user->nama ?? 'System') . '</span>';
        })
        ->rawColumns(['user_nama', 'aksi'])
        ->make(true);
}
    public function edit_ajax($id)
{
    $transaksi = PenjualanModel::find($id);
    if (!$transaksi) {
        return response()->json(['message' => 'Data tidak ditemukan'], 404);
    }

    $page = (object)[
        'title' => 'Edit Data Transaksi'
    ];

    return view('transaksi.edit_ajax', compact('transaksi', 'page'));
}

public function update_ajax(Request $request, $id)
{
    $request->validate([
        'penjualan_kode' => 'required|string|max:20|unique:t_penjualan,penjualan_kode,'.$id.',penjualan_id',
        'pembeli' => 'required|string|max:50',
        'penjualan_tanggal' => 'required|date'
    ]);

    $transaksi = PenjualanModel::find($id);
    if (!$transaksi) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ], 404);
    }

    try {
        $transaksi->update([
            'penjualan_kode' => $request->penjualan_kode,
            'pembeli' => $request->pembeli,
            'penjualan_tanggal' => $request->penjualan_tanggal
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $transaksi
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Gagal memperbarui data: '.$e->getMessage()
        ], 500);
    }
}
}