<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanDetailModel extends Model
{
    use HasFactory;
    
    protected $table = 't_penjualan_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'penjualan_id',
        'barang_id',
        'harga',
        'jumlah',
    ];

    public function penjualan()
    {
        return $this->belongsTo(PenjualanModel::class, 'penjualan_id');
    }

    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id');
    }

    // Accessor untuk subtotal
    public function getSubtotalAttribute()
    {
        return $this->harga * $this->jumlah;
    }
}