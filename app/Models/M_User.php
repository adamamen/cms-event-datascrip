<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

// class M_User extends Model
class M_User extends Authenticatable
{
    use HasFactory;

    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_user';
    protected $primaryKey = 'id';

    protected $fillable = [
        'username',
        'password',
    ];
}
