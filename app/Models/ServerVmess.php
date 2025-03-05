<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerVmess extends Model
{
    protected $table = 'server_vmess';
    protected $guarded = ['id'];
    protected $casts = [
        'group_id' => 'array',
        'route_id' => 'array',
        'tlsSettings' => 'array',
        'networkSettings' => 'array',
        'dnsSettings' => 'array',
        'ruleSettings' => 'array',
        'tags' => 'array',
    ];
}
