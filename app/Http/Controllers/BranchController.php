<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch; // Assuming you have a Branch model
use Illuminate\Support\Facades\Http;

class BranchController extends Controller
{
    public function create()
    {
        return view('branches.create'); // View for adding a branch
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'entry_time' => 'required',
            'exit_time' => 'required',
            'is_overtime' => 'required|boolean',
            'ot_calculation_rules' => 'nullable|string',
            'address' => 'required|string',
        ]);

        // Create Branch Logic
        $branch = Branch::create($request->all());

        return response()->json(['message' => 'Successfully Branch Created']);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q');
        $apiKey = env('BARIKOI_API_KEY', 'your-default-api-key');

        $response = Http::get("https://barikoi.xyz/v2/api/search/autocomplete/{$apiKey}/place", [
            'q' => $query,
        ]);

        return response()->json($response->json());
    }
}
