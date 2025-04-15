<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;
    
    protected $table = 't_penjualan';
    protected $primaryKey = 'penjualan_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id');
    }

    // Accessor untuk total transaksi
    public function getTotalAttribute()
    {
        return $this->details->sum(function($detail) {
            return $detail->harga * $detail->jumlah;
        });
    }
}