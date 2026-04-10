<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PromotionUsage extends Model
{
    use LogsActivity;

    protected $fillable = [
        'promotion_id',
        'ci',
        'code',
        'used_at',
        'sale_id',
    ];

    /**
     * Configuración de logs
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('PromotionUsage')
            ->logOnly([
                'promotion_id',
                'ci',
                'code',
                'used_at',
                'sale_id',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
}
