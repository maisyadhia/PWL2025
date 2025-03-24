<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [];
        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'kategori_id' => rand(1, 5), // Mengambil kategori secara acak
                'barang_kode' => 'BRG00' . $i,
                'barang_nama' => 'Barang ' . $i,
                'harga_beli' => rand(5000, 20000),
                'harga_jual' => rand(25000, 50000),
            ];
        }
        DB::table('m_barang')->insert($data);
    }
}