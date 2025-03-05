<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatUser extends Model
{
    protected $table = 'stat_user';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
