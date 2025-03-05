<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    protected $table = 'ticket_message';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
