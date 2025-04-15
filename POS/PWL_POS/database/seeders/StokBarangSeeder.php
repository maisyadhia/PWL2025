<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StokBarangSeeder extends Seeder
{
    public function run()
{
    $barangIds = BarangModel::pluck('barang_id')->toArray();
    $userId = UserModel::first()->user_id;

    foreach(range(1, 20) as $i) {
        $jenis = $i % 3 == 0 ? 'keluar' : 'masuk'; // 1/3 keluar, 2/3 masuk
        
        StokModel::create([
            'barang_id' => $barangIds[array_rand($barangIds)],
            'user_id' => $userId,
            'stok_tanggal' => now()->subDays(rand(1, 30)),
            'stok_jumlah' => rand(1, 100),
            'jenis' => $jenis,
            'keterangan' => $jenis == 'masuk' ? 'Pembelian dari supplier' : 'Penjualan ke pelanggan',
            'kode_transaksi' => 'STK-'.date('Ymd').'-'.$i
        ]);
    }
}

    
}
