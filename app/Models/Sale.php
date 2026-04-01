<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Sale extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'sale_date',
        'total',
    ];

    protected $casts = [
        'sale_date' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sales')
            ->logOnly(['sale_date', 'total'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
