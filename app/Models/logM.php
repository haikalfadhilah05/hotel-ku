<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class logM extends Model
{
    use HasFactory;
    protected $table = "logs";
    protected $fillable = ["id", "id_user", "activity"];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->LogOnly(['id_user', 'activity']);
    }
}
