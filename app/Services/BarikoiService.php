<?php
// In your Laravel service, e.g., BarikoiService.php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BarikoiService
{
    protected $client;
    protected $accessToken;

    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://barikoi.com/v1/api/']);
        $this->accessToken = env('BARIKOI_API_KEY'); // Set this in your .env
    }

    public function autocomplete(Request $request)
    {
        $query = $request->query('q');
        $apiKey = $this->accessToken;

        $response = Http::get("https://barikoi.com/v1/api/search/autocomplete/{$apiKey}/place", [
            'q' => $query,
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            Log::error('Barikoi API error:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return response()->json(['error' => 'Failed to fetch data from Barikoi'], 500);
        }
    }

}
