<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuk';

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
