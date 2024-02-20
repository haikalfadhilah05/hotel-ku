<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transactionsM extends Model
{
    use HasFactory;
    protected $table = "transactions";
    protected $fillable = ["id", "id_produk", "nama_pelanggan", "jmlh_org", "nomor_unik", "checkin", "checkout", "total_harga", "uang_bayar", "uang_kembali", "harga_awal"];

}
