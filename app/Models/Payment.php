<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'config' => 'array'
    ];
}
