<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_supplier')->insert([
            [
                'nama_supplier' => 'PT Sumber Makmur',
                'supplier_kontak' => '08123456789',
                'alamat' => 'Jakarta',
            ],
            [
                'nama_supplier' => 'CV Mitra Sejahtera',
                'supplier_kontak' => '08234567890',
                'alamat' => 'Bandung',
            ],
            [
                'nama_supplier' => 'UD Berkah Jaya',
                'supplier_kontak' => '08345678901',
                'alamat' => 'Surabaya',
            ],
        ]);
    }
}
