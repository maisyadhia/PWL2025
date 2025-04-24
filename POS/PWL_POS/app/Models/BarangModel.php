<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangModel extends Model
{
    protected $table = 'm_barang';
    protected $primaryKey = 'barang_id';
    protected $fillable = ['barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual', 'stok'];
    
    protected $casts = [
        'harga_beli' => 'integer',
        'harga_jual' => 'integer',
        'stok' => 'integer'
    ];
    
    public function kategori(): BelongsTo {
        return $this->belongsTo(KategoriModel::class, 'kategori_id', 'kategori_id');
    }
    
    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'barang_id', 'barang_id');
    }
    
    public function stoks()
    {
        return $this->hasMany(StokModel::class, 'barang_id', 'barang_id');
    }
    
}