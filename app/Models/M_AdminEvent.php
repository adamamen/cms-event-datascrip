<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_AdminEvent extends Model
{
    use HasFactory;
    protected $primaryKey = 'admin_id';
    protected $table = 'tbl_admin_event';
}
