<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommissionLog extends Model
{
    protected $table = 'commission_log';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
