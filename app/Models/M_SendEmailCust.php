<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_SendEmailCust extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tbl_send_email_cust';
}
