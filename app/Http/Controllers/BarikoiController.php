<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BarikoiController extends Controller
{
    public function autocomplete(Request $request)
    {
        $query = $request->query('q');
        $apiKey = 'bkoi_0f0c0e2aaed92fda43a85d29493d69776ef1c810e8f3d425f0b90fed001bef50';

        $response = Http::get("https://barikoi.xyz/v2/api/search/autocomplete/{$apiKey}/place", [
            'q' => $query,
        ]);

        // Check if the request was successful
        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            // Log the error or return a message for debugging
            return response()->json(['error' => 'Failed to fetch data from Barikoi'], 500);
        }
    }

}
