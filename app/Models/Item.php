<?php

namespace App\Models;

use App\Models\Category;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Item extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
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
            ->useLogName('items')
            ->logOnly(['name', 'category_id', 'price', 'description'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
