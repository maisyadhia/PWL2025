<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 't_stok';
    protected $primaryKey = 'stok_id';
    public $timestamps = false; // karena created_at dan updated_at NULL

    // app/Models/StokModel.php
    protected $fillable = [
        'barang_id',
        'user_id',
        'penjualan_id',
        'stok_tanggal',
        'stok_jumlah',
        'jenis',
        'keterangan',
        'kode_transaksi'
    ];
    
    // Tambahkan accessor untuk format tanggal
    protected $dates = ['stok_tanggal'];
    
    public function getStokTanggalAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i');
    }

// Tambahkan relasi ke penjualan
    public function penjualan()
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id', 'penjualan_id');
    }

        public function barang()
        {
            return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
        }

        public function user()
        {
            return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
        }

        public function supplier()
        {
            return $this->belongsTo(SupplierModel::class, 'supplier_id', 'supplier_id');
        }

}