<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BarangKeluar extends Model
{
    use HasFactory;

    protected $table = 'barang_keluar';

    protected $fillable = [
        'product_id',
        'qty',
        'keterangan',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
