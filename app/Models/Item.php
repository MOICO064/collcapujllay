<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Item extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
        'enabled',
        'use_once',
        'reservable',
        'capacity',
        'reservation_hours',
        'reservation_price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'enabled' => 'boolean',
        'use_once' => 'boolean',
        'reservable' => 'boolean',
        'capacity' => 'integer',
        'reservation_hours' => 'integer',
        'reservation_price' => 'decimal:2',
    ];

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Item')
            ->logOnly(['name', 'category_id', 'price', 'description', 'enabled', 'use_once', 'reservable', 'capacity', 'reservation_hours', 'reservation_price'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
