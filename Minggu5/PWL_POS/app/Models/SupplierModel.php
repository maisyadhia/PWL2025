<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\HasMany;

// class SupplierModel extends Model
// {
//     protected $table = 'm_supplier';
//     protected $primaryKey = 'supplier_id';
//     protected $fillable = ['supplier_nama', 'supplier_alamat', 'supplier_kontak'];
//     public $timestamps = false;
// }



namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'm_supplier'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'supplier_id'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'supplier_kode',
        'supplier_nama',
        'supplier_alamat',
        'supplier_kontak',
    ];
}