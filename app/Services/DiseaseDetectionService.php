<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DiseaseDetectionService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('DISEASE_API_URL');
        $this->apiKey = env('DISEASE_API_KEY');
        $this->client = new Client();
    }

    /**
     * Detect plant disease from an image.
     *
     * @param string $imagePath Local path to the image
     * @return array
     * @throws \Exception
     */
    public function detect(string $imagePath): array
    {
        // If API URL is not set, return mock response
        if (empty($this->apiUrl)) {
            return $this->getMockResponse();
        }

        try {
            $response = $this->client->post($this->apiUrl, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                ],
                'multipart' => [
                    [
                        'name'     => 'image',
                        'contents' => fopen(storage_path('app/public/' . $imagePath), 'r'),
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            Log::error('Disease Detection API Error: ' . $e->getMessage());
            throw new \Exception('Failed to connect to external disease detection API: ' . $e->getMessage());
        }
    }

    /**
     * Returns the required mock response.
     *
     * @return array
     */
    private function getMockResponse(): array
    {
        return [
            "disease_name" => "Leaf Blight",
            "description" => "A fungal disease affecting plant leaves",
            "symptoms" => "Yellow spots on leaves, wilting",
            "causes" => "Fungal infection due to high humidity",
            "preventive_measures" => "Ensure proper air circulation",
            "treatment" => "Apply fungicide every 7 days",
            "confidence_value" => 92.5
        ];
    }
}
