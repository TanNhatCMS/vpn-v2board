<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    protected $table = 'mail_log';
    protected $dateFormat = 'U';
    protected $guarded = ['id'];
}
