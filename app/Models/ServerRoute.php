<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerRoute extends Model
{
    protected $table = 'server_route';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
