<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupon';
    protected $guarded = ['id'];
    protected $casts = [
        'limit_plan_ids' => 'array',
        'limit_period' => 'array',
    ];
}
