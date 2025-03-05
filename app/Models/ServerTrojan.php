<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerTrojan extends Model
{
    protected $table = 'server_trojan';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'group_id' => 'array',
        'route_id' => 'array',
        'tags' => 'array'
    ];
}
