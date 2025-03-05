<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stat extends Model
{
    protected $table = 'stat';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
