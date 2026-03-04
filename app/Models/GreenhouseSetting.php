<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GreenhouseSetting extends Model
{
    protected $fillable = ['user_id', 'temperature_limit', 'humidity_limit'];
}
