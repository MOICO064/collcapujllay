<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'single_use',
        'discount_type',
        'discount_value',
    ];

    public function usages()
    {
        return $this->hasMany(PromotionUsage::class);
    }
}
