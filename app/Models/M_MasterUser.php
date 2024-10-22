<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_MasterUser extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table      = 'mst_cust';
}
