<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productsM extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $fillable = ["id", "nomor_kamar", "lantai_kamar", "nama_produk", "harga_produk", "status", "fasilitas", "kapasitas"];
}
