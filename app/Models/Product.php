<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'harga_beli',
        'stock',
        'category',
        'image_path',
        'short_description',
        'description',
    ];

    protected $casts = [
        'price'      => 'integer',
        'harga_beli' => 'integer',
        'stock'      => 'integer',
    ];

    /**
     * Relasi: Product bisa muncul di banyak item penjualan
     */
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Relasi: Product punya data barang masuk (kalau modelnya ada)
     */
    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

    /**
     * Relasi: Product punya data barang keluar (kalau modelnya ada)
     */
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class);
    }

    /**
     * Format Rupiah (Harga Jual)
     */
    public function getPriceFormattedAttribute(): string
    {
        return "Rp " . number_format((int) ($this->price ?? 0), 0, ',', '.');
    }

    /**
     * Format Rupiah (Harga Beli / Modal)
     */
    public function getHargaBeliFormattedAttribute(): string
    {
        return "Rp " . number_format((int) ($this->harga_beli ?? 0), 0, ',', '.');
    }

    /**
     * Estimasi profit per 1 pcs (harga jual - modal)
     */
    public function getProfitPerItemAttribute(): int
    {
        return (int) ($this->price ?? 0) - (int) ($this->harga_beli ?? 0);
    }

    /**
     * Estimasi margin per 1 pcs (%) (profit/modal terhadap harga jual)
     */
    public function getMarginAttribute(): float
    {
        $price = (int) ($this->price ?? 0);
        $profit = $this->profit_per_item;

        return $price > 0 ? round(($profit / $price) * 100, 2) : 0;
    }

    public function getProfitPerItemFormattedAttribute(): string
    {
        return "Rp " . number_format((int) ($this->profit_per_item ?? 0), 0, ',', '.');
    }
}
