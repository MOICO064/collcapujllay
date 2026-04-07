<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Promotion extends Model
{
    use LogsActivity;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'single_use',
        'discount_type',
        'discount_value',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'single_use' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Promotion')
            ->logOnly([
                'name',
                'description',
                'start_date',
                'end_date',
                'single_use',
                'discount_type',
                'discount_value'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
