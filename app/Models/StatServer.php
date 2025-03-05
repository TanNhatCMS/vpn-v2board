<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatServer extends Model
{
    protected $table = 'stat_server';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
