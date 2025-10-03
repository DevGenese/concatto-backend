<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cooperative extends Model
{
    protected $fillable = [
        'name',
        'hour_value',
        'km_value'
    ];
}
