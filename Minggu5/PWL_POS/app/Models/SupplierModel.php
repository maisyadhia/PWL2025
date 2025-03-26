<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;

    protected $table = 'm_supplier'; // Sesuaikan dengan nama tabel di database

    protected $primaryKey = 'supplier_id'; // Sesuaikan dengan primary key

    public $timestamps = true; // created_at & updated_at otomatis diisi oleh Laravel

    protected $fillable = [
        'nama_supplier',
        'alamat',
        'supplier_kontak',
    ];
}


