<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'total',
        'paid_amount',
        'change_amount',
        'profit',     // ✅ benefit/profit
        'user_id',    // ✅ kasir (kalau kamu tambahkan kolomnya di tabel sales)
    ];

    protected $casts = [
        'total'         => 'integer',
        'paid_amount'   => 'integer',
        'change_amount' => 'integer',
        'profit'        => 'integer',
        'user_id'       => 'integer',
    ];

    /**
     * Relasi: 1 Sale punya banyak SaleItem
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * ✅ Relasi: Sale milik User (Kasir)
     * NOTE: ini butuh kolom `user_id` di tabel `sales`.
     * Kalau belum ada, kamu bisa tetap simpan relasinya dulu,
     * tapi query ->with('user') akan error kalau kolom user_id belum ada saat dipakai untuk join.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Format otomatis untuk struk thermal
     */
    public function getTotalFormattedAttribute()
    {
        return "Rp " . number_format($this->total ?? 0, 0, ',', '.');
    }

    public function getPaidFormattedAttribute()
    {
        return "Rp " . number_format($this->paid_amount ?? 0, 0, ',', '.');
    }

    public function getChangeFormattedAttribute()
    {
        return "Rp " . number_format($this->change_amount ?? 0, 0, ',', '.');
    }

    /**
     * ✅ Format benefit/profit
     */
    public function getProfitFormattedAttribute()
    {
        return "Rp " . number_format($this->profit ?? 0, 0, ',', '.');
    }

    /**
     * ✅ Margin profit (%) dari total
     */
    public function getMarginAttribute()
    {
        $total = (int) ($this->total ?? 0);
        $profit = (int) ($this->profit ?? 0);

        return $total > 0 ? round(($profit / $total) * 100, 2) : 0;
    }
}
