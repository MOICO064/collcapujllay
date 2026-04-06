<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionUsage extends Model
{
    protected $fillable = [
        'promotion_id',
        'ci',
        'used_at',
    ];

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }
}
