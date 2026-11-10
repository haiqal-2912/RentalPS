<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = "transaksi";
    protected $guarded = ['id'];
    public function playstation()
    {
        return $this->belongsTo(Playstation::class);
    }
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
}
