<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Shopsign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

// Use this import for the Request class

class ShopsignController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.view']);
        $shopsigns = Shopsign::all();
        return view('backend.pages.shopsigns.index', [
            'shopsigns' => $shopsigns
        ]);
    }

    public function allShopsigns()
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.view']);

//        Log::info('Fetching all shopsigns');
        $shopsigns = Shopsign::all()->toArray();
//        Log::info($shopsigns);

        return $shopsigns;
    }



    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.create']);

        return view('backend.pages.shopsigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.create']);

        // Validate the request data and handle any validation errors automatically
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable',
            'type' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
        ]);
        // Create the shopsign with validated data
        Shopsign::create($validatedData);

        // Flash success message to the session
        session()->flash('success', 'Shopsign has been created.');

        // Redirect to the index route for shopsigns
        return redirect()->back();
    }

    public function edit(int $id): Renderable|RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.edit']);

        $shopsign = Shopsign::findOrFail($id);
        return view('backend.pages.shopsigns.edit', [
            'shopsign' => $shopsign,
            'roles' => Role::all(),
        ]);

    }

    public function update(Request $request, int $id): RedirectResponse
    {
//        dd(request()->all());
        $this->checkAuthorization(auth()->user(), ['shopsign.edit']);

        $shopsign = Shopsign::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable',
            'type' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
        ]);
//        dd('update');
//        dd($validatedData);

        // Update the shopsign with validated data
        $shopsign->update($validatedData);

        session()->flash('success', 'Shopsign has been updated.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['shopsign.delete']);

        $shopsign = Shopsign::findOrFail($id);
        $shopsign->delete();

        session()->flash('success', 'Shopsign has been deleted.');
        return redirect()->route('admin.shopsigns.index');
    }

    public function importShow(Request $request)
    {

//        dd($request->route()->getName());
        $shopsign = Shopsign::all(); // Use findOrFail to throw an error if not found

        // Return the view with the shopsign data
        return view('backend.pages.shopsigns.excel-import', compact('shopsign'));
    }

    public function import(Request $request)
    {
        // Validate the request
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        // Import the data from the Excel file
        Excel::import(new ShopsignsImport, $request->file('file'));

        return redirect()->back()->with('success', 'Shopsigns data imported successfully.');
    }

    public function export()
    {
        return Excel::download(new ShopsignsExport, 'shopsigns.xlsx');
    }



}
