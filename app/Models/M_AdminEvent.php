<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class M_AdminEvent extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'admin_id';
    protected $table = 'tbl_admin_event';
}
