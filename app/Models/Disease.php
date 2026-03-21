<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    protected $fillable = [
        'disease_name',
        'description',
        'symptoms',
        'causes',
        'preventive_measures',
        'treatment',
        'confidence_value',
        'image_path',
    ];

    /**
     * Get the full URL for the disease image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['image_url'];
}
