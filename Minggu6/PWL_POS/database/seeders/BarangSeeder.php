<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    Schema::disableForeignKeyConstraints();
    DB::table('m_barang')->truncate(); // Hapus dulu isinya
    Schema::enableForeignKeyConstraints();

    $barangNama = [
        'Sabun Mandi Lux',
        'Minyak Goreng Bimoli 1L',
        'Beras Ramos 5kg',
        'Gula Pasir Rose Brand',
        'Susu Dancow 800g',
        'Indomie Goreng Spesial',
        'Tepung Terigu Segitiga Biru',
        'Air Mineral Aqua 600ml',
        'Kopi Kapal Api Special',
        'Kecap Manis ABC 620ml',
    ];

    $data = [];

    for ($i = 1; $i <= 10; $i++) {
        $data[] = [
            'kategori_id' => rand(1, 5),
            'barang_kode' => 'BRG00' . $i,
            'barang_nama' => $barangNama[$i - 1],
            'harga_beli' => rand(5000, 20000),
            'harga_jual' => rand(25000, 50000),
        ];
    }

    DB::table('m_barang')->insert($data);
    }
}
