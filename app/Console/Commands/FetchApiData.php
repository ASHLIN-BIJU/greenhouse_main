<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchApiData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:disease-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching disease data from external API...');

        // Placeholder URL - User should replace with real API
        $apiUrl = 'https://api.example.com/diseases';

        try {
            // Mocking the response for demonstration if the API doesn't exist
            // In a real scenario, use: $response = \Illuminate\Support\Facades\Http::get($apiUrl);
            $mockData = [
                [
                    'id' => 'EXT-001',
                    'name' => 'Powdery Mildew',
                    'description' => 'A fungal disease that affects a wide range of plants.',
                    'symptoms' => 'White powdery spots on the leaves and stems.',
                    'treatment' => 'Fungicides and improving air circulation.'
                ],
                [
                    'id' => 'EXT-002',
                    'name' => 'Leaf Spot',
                    'description' => 'Commonly caused by fungi or bacteria.',
                    'symptoms' => 'Small, dark spots on leaves, often with a yellow halo.',
                    'treatment' => 'Remove infected leaves and apply appropriate spray.'
                ]
            ];

            foreach ($mockData as $item) {
                \App\Models\Disease::updateOrCreate(
                    ['external_id' => $item['id']],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'],
                        'symptoms' => $item['symptoms'],
                        'treatment' => $item['treatment'],
                    ]
                );
            }

            $this->info('Successfully fetched and updated ' . count($mockData) . ' diseases.');
        } catch (\Exception $e) {
            $this->error('Failed to fetch data: ' . $e->getMessage());
        }
    }
}
