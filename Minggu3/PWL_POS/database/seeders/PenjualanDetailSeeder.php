<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanDetailSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            DB::table('t_penjualan_detail')->insert([
                'penjualan_id' => 5,
                'barang_id' => 4,
                'jumlah' => 5,
                'harga' => 41626
            ]);
            
        }
    }
}

