<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InviteCode extends Model
{
    protected $table = 'invite_code';
    protected $fillable = ['user_id', 'code', 'status', 'pv'];
}
