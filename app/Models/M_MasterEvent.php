<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class M_MasterEvent extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_event';
    protected $guarded = [];
    // protected $fillable = ['status', 'title', 'desc', 'company', 'start_event', 'end_event', 'logo', 'location', 'created_at', 'updated_at'];
    protected $table = 'tbl_master_event';
}
