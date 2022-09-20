<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelHasRole extends Model
{
    use HasFactory;

    protected $guard_name = 'api';

    protected $fillable = [
        'model_id',
        'model_type',
        'role_id',
    ];
}
