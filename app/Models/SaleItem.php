<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',
        'price',
        'cost',      // ✅ modal per item
        'subtotal',
        'profit',    // ✅ benefit per item
    ];

    protected $casts = [
        'sale_id'   => 'integer',
        'product_id'=> 'integer',
        'qty'       => 'integer',
        'price'     => 'integer',
        'cost'      => 'integer',
        'subtotal'  => 'integer',
        'profit'    => 'integer',
    ];

    /**
     * Relasi: item ini milik 1 transaksi (Sale)
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Relasi: item ini merujuk ke 1 produk
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Helper untuk format angka ke rupiah
     */
    public function getPriceFormattedAttribute()
    {
        return "Rp " . number_format($this->price ?? 0, 0, ',', '.');
    }

    public function getCostFormattedAttribute()
    {
        return "Rp " . number_format($this->cost ?? 0, 0, ',', '.');
    }

    public function getSubtotalFormattedAttribute()
    {
        return "Rp " . number_format($this->subtotal ?? 0, 0, ',', '.');
    }

    public function getProfitFormattedAttribute()
    {
        return "Rp " . number_format($this->profit ?? 0, 0, ',', '.');
    }

    /**
     * Margin per item (%)
     */
    public function getMarginAttribute(): float
    {
        $subtotal = (int) ($this->subtotal ?? 0);
        $profit   = (int) ($this->profit ?? 0);

        return $subtotal > 0 ? round(($profit / $subtotal) * 100, 2) : 0;
    }
}
