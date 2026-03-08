<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Greenhouse extends Model
{
    protected $fillable = ['user_id', 'product_id', 'name', 'location'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function settings()
    {
        return $this->hasOne(GreenhouseSetting::class);
    }

    public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }
}
