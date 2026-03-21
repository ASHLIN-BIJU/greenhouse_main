<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Disease;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            [
                'disease_name' => 'Leaf Blight',
                'description' => 'A fungal disease affecting plant leaves, causing significant damage if not treated.',
                'symptoms' => 'Yellow spots on leaves, wilting, and eventual leaf drop.',
                'causes' => 'Fungal infection due to high humidity and poor air circulation.',
                'preventive_measures' => 'Ensure proper air circulation and avoid overhead watering.',
                'treatment' => 'Apply fungicide every 7 days until symptoms disappear.',
                'confidence_value' => 95.0,
                'image_path' => 'diseases/sample_leaf_blight.jpg',
            ],
            [
                'disease_name' => 'Powdery Mildew',
                'description' => 'A common fungal disease that appears as white powder on leaves and stems.',
                'symptoms' => 'White, powdery spots on leaves, distorted growth.',
                'causes' => 'High humidity and moderate temperatures.',
                'preventive_measures' => 'Provide adequate spacing for airflow and use resistant varieties.',
                'treatment' => 'Apply sulfur-based fungicide or neem oil.',
                'confidence_value' => 88.5,
                'image_path' => 'diseases/sample_powdery_mildew.jpg',
            ]
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }
    }
}
