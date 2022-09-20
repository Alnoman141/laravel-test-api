<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermission extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $fillable = [
        'permission_id',
        'role_id',
    ];
}
