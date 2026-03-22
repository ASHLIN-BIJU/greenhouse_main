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

            $apiData = json_decode($response->getBody()->getContents(), true);

            // Map external API fields to our database fields
            return [
                'disease_name'        => $apiData['display_name'],
                'description'         => $apiData['description'],
                'symptoms'            => $apiData['symptoms'],
                'causes'              => 'Not specified by detection API',
                'preventive_measures' => $apiData['prevention'],
                'treatment'           => $apiData['solutions'],
                'confidence_value'    => (float) rtrim($apiData['confidence'], '%'),
            ];

        } catch (GuzzleException $e) {
            Log::error('Disease Detection API Error: ' . $e->getMessage());
            throw new \Exception('Failed to connect to external disease detection API: ' . $e->getMessage());
        }
    }

    /**
     * Returns a mock response matching the real API field mapping.
     *
     * @return array
     */
    private function getMockResponse(): array
    {
        return [
            'disease_name'        => 'Late Blight',
            'description'         => 'Late Blight is one of the most dangerous tomato diseases. It moves incredibly fast in cool, wet conditions and can kill an entire healthy plant in just a few days.',
            'symptoms'            => 'Large, dark, oily-looking patches appearing on the leaves and stems. White, fuzzy mold growing on the underside of patches during humid mornings.',
            'causes'              => 'Not specified by detection API',
            'preventive_measures' => 'Avoid overhead watering and ensure your garden has excellent drainage. Plant resistant varieties.',
            'treatment'           => 'Pull out and destroy heavily infected plants immediately. For early infections, apply fungicide like chlorothalonil or copper spray every 5 days.',
            'confidence_value'    => 100.0,
        ];
    }
}