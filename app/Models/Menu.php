<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    protected $table = "menu";
    protected $guarded = ['id'];
    protected $casts = [
        'status' => 'boolean',
        'harga' => 'integer',
        'stok' => 'integer',
    ];

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesananMenu::class);
    }

    /** Menu yang bisa dipesan pelanggan: status aktif dan stok masih ada. */
    public function scopeTersedia($query)
    {
        return $query->where('status', true)->where('stok', '>', 0);
    }
}
