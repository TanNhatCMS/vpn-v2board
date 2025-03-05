<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';
    protected $guarded = ['id'];
    protected $casts = [
        'tags' => 'array',
    ];
}
