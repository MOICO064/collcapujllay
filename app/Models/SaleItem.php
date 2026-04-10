<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class SaleItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'sale_id',
        'item_id',
        'quantity',
        'unit_price',
        'total',
        'use_once_number',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Configuración de logs
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('SaleItem')
            ->logOnly([
                'sale_id',
                'item_id',
                'quantity',
                'unit_price',
                'total',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
