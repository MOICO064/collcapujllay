<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sale extends Model
{
    use HasFactory, LogsActivity;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_ANNULLED = 'annulled';

    protected $fillable = [
        'sale_date',
        'invoice_number',
        'status',
        'subtotal',
        'discount_type',
        'discount_value',
        'total',
        'payment_method',
        'paid_amount',
        'balance_due',
        'customer_code',
        'user_id',
        'glosa',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'invoice_number' => 'int',
        'status' => 'string',
        'subtotal' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'total' => 'decimal:2',
        'payment_method' => 'string',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'user_id' => 'integer',
        'glosa' => 'string',
    ];

    /**
     * Configuración de logs
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Sale')
            ->logOnly([
                'sale_date',
                'invoice_number',
                'status',
                'subtotal',
                'discount_type',
                'discount_value',
                'total',
                'customer_code',
                'payment_method',
                'user_id',
                'glosa',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
