<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_LogSendEmailCust extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'log_send_email_cust';
}
