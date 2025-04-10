<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua penjualan_id yang valid dari tabel t_penjualan
        $penjualanIds = DB::table('t_penjualan')->pluck('penjualan_id')->toArray();
        
        if (empty($penjualanIds)) {
            return; // Hentikan seeding kalau tidak ada transaksi
        }

        $data = [];
        for ($i = 1; $i <= 30; $i++) { // Loop untuk 30 transaksi
            $penjualan_id = $penjualanIds[array_rand($penjualanIds)]; // Pilih penjualan_id yang valid secara acak

            for ($j = 1; $j <= 3; $j++) { // Loop untuk 3 barang per transaksi
                $barang_id = rand(1, 10); // Pilih barang_id acak antara 1-10
                $harga = DB::table('m_barang')->where('barang_id', $barang_id)->value('harga_jual');

                $data[] = [
                    'penjualan_id' => $penjualan_id,
                    'barang_id' => $barang_id,
                    'jumlah' => rand(1, 5), // Jumlah acak antara 1-5
                    'harga' => $harga
                ];
            }
        }

        // Masukkan semua data dalam satu query agar lebih efisien
        DB::table('t_penjualan_detail')->insert($data);
    }
}

