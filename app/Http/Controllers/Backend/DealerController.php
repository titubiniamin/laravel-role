<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Exports\DealersExport;
use App\Http\Controllers\Controller;
use App\Imports\DealersImport;
use App\Models\Dealer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

// Use this import for the Request class

class DealerController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.view']);
        $dealers = Dealer::all();
        return view('backend.pages.dealers.index', [
            'dealers' => $dealers
        ]);
    }

    public function allDealers()
    {
        $this->checkAuthorization(auth()->user(), ['dealer.view']);

//        Log::info('Fetching all dealers');
        $dealers = Dealer::all()->toArray();
//        Log::info($dealers);

        return $dealers;
    }



    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.create']);

        return view('backend.pages.dealers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.create']);

        // Validate the request data and handle any validation errors automatically
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'zone' => 'nullable|string|max:255',
            'dealer_code' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'mobile' => 'nullable|string|max:15', // Adjust max length as needed
            'address' => 'nullable|string|max:255',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
            'average_sales' => 'nullable',
            'market_size' => 'nullable',
            'market_share' => 'nullable',
            'competition_brand' => 'nullable',
        ]);
        // Create the dealer with validated data
        Dealer::create($validatedData);

        // Flash success message to the session
        session()->flash('success', 'Dealer has been created.');

        // Redirect to the index route for dealers
        return redirect()->back();
    }

    public function edit(int $id): Renderable|RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.edit']);

        $dealer = Dealer::findOrFail($id);
        return view('backend.pages.dealers.edit', [
            'dealer' => $dealer,
            'roles' => Role::all(),
        ]);

    }

    public function update(Request $request, int $id): RedirectResponse
    {
//        dd(request()->all());
        $this->checkAuthorization(auth()->user(), ['dealer.edit']);

        $dealer = Dealer::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'zone' => 'nullable|string|max:255',
            'dealer_code' => 'nullable|string|max:255',
            'email' => 'required|email|unique:dealers,email,' . $dealer->id,//email
            'website' => 'nullable|url|max:255',
            'mobile' => 'nullable|string|max:15', // Adjust max length as needed
            'address' => 'nullable|string|max:255',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
            'average_sales' => 'nullable',
            'market_size' => 'nullable',
            'market_share' => 'nullable',
            'competition_brand' => 'nullable',
        ]);
//        dd('update');
//        dd($validatedData);

        // Update the dealer with validated data
        $dealer->update($validatedData);

        session()->flash('success', 'Dealer has been updated.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['dealer.delete']);

        $dealer = Dealer::findOrFail($id);
        $dealer->delete();

        session()->flash('success', 'Dealer has been deleted.');
        return redirect()->route('admin.dealers.index');
    }

    public function importShow(Request $request)
    {

        $dealer = Dealer::all(); // Use findOrFail to throw an error if not found

        // Return the view with the dealer data
        return view('backend.pages.dealers.excel-import', compact('dealer'));
    }

    public function import(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Import the data from the Excel file
        Excel::import(new DealersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Dealers data imported successfully.');
    }

    public function export()
    {
        return Excel::download(new DealersExport, 'dealers.xlsx');
    }



}
