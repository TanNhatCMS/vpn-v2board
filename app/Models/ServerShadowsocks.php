<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerShadowsocks extends Model
{
    protected $table = 'server_shadowsocks';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
    protected $casts = [
        'group_id' => 'array',
        'route_id' => 'array',
        'tags' => 'array',
        'obfs_settings' => 'array'
    ];
}
