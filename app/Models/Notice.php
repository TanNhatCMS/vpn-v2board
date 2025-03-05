<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $table = 'notice';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'tags' => 'array',
    ];
}
