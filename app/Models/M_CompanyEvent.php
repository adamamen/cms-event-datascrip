<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_CompanyEvent extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'tbl_company_event';
}
