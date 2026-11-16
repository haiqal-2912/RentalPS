<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananMenu extends Model
{
    use HasFactory;
    protected $table = "detail_pesanan_menu";
    protected $guarded = ['id'];

    public function pesanan()
    {
        return $this->belongsTo(PesananMenu::class, 'pesanan_menu_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
