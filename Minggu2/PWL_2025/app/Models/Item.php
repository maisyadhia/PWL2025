<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    // Menentukan kolom mana saja yang bisa diisi oleh user (mass assignable)
    protected $fillable = [
        'name',      // Nama item yang bisa diisi
        'description',  // Deskripsi item yang bisa diisi
    ];
}
