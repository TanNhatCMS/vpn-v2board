<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerHysteria extends Model
{
    protected $table = 'server_hysteria';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'group_id' => 'array',
        'route_id' => 'array',
        'tags' => 'array'
    ];
}
