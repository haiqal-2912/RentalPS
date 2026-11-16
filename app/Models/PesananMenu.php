<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananMenu extends Model
{
    use HasFactory;
    protected $table = "pesanan_menu";
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail()
    {
        return $this->hasMany(DetailPesananMenu::class, 'pesanan_menu_id');
    }
}
