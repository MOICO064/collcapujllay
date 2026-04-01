<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class SaleItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'unit_price',
        'total',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sale_items')
            ->logOnly(['sale_id', 'item_id', 'quantity', 'unit_price', 'total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
