<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'ticket';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
