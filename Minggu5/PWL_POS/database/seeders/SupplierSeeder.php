<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        DB::table('m_supplier')->insert([
            ['supplier_nama' => 'PT Sumber Makmur', 'supplier_alamat' => 'Jakarta', 'supplier_kontak' => '08123456789'],
            ['supplier_nama' => 'CV Mitra Sejahtera', 'supplier_alamat' => 'Bandung', 'supplier_kontak' => '08234567890'],
            ['supplier_nama' => 'UD Berkah Jaya', 'supplier_alamat' => 'Surabaya', 'supplier_kontak' => '08345678901']
        ]);
    }
}