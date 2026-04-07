<?php

namespace App\Models;

use App\Models\Promotion;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_date',
        'invoice_number',
        'status',
        'subtotal',
        'discount_type',
        'discount_value',
        'total',
        'promotion_id',
        'customer_ci',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'invoice_number' => 'int',
        'status' => 'string',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'total' => 'decimal:2',
        'promotion_id' => 'int',
        'customer_ci' => 'string',
    ];

    public function getFormattedInvoiceNumberAttribute(): string
    {
        $date = $this->sale_date?->format('Ymd') ?? now()->format('Ymd');
        $number = str_pad($this->invoice_number ?? 0, 3, '0', STR_PAD_LEFT);
        return "{$date}-{$number}";
    }

    public function getDiscountAmountAttribute(): float
    {
        $subtotal = (float) ($this->subtotal ?? 0);
        $total = (float) ($this->total ?? 0);

        return round(max(0, $subtotal - $total), 2);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

}
