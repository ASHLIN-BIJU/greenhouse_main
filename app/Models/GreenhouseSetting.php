<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GreenhouseSetting extends Model
{
    protected $fillable = ['greenhouse_id', 'temperature_limit', 'humidity_limit'];
}
