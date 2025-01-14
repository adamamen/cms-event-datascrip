<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class M_AccessUser extends Authenticatable
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tbl_access_user';
}
