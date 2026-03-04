<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sensor extends Model
{
    protected $fillable = ['greenhouse_id', 'sensor_type', 'unit'];
}
