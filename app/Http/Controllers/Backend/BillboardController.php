<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Billboard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Role;

// Use this import for the Request class

class BillboardController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['billboard.view']);
        $billboards = Billboard::all();
        return view('backend.pages.billboards.index', [
            'billboards' => $billboards
        ]);
    }

    public function allBillboards()
    {
        $this->checkAuthorization(auth()->user(), ['billboard.view']);

//        Log::info('Fetching all billboards');
        $billboards = Billboard::all()->toArray();
//        Log::info($billboards);

        return $billboards;
    }



    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['billboard.create']);

        return view('backend.pages.billboards.create');
    }

    public function store(Request $request): RedirectResponse
    {
//        dd($request->all());
        $this->checkAuthorization(auth()->user(), ['billboard.create']);

        // Validate the request data and handle any validation errors automatically
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable',
            'type' => 'nullable',
            'brand' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
        ]);
        // Create the billboard with validated data
        Billboard::create($validatedData);

        // Flash success message to the session
        session()->flash('success', 'Billboard has been created.');

        // Redirect to the index route for billboards
        return redirect()->back();
    }

    public function edit(int $id): Renderable|RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['billboard.edit']);

        $billboard = Billboard::findOrFail($id);
//        dd($billboard);
        return view('backend.pages.billboards.edit', [
            'billboard' => $billboard,
            'roles' => Role::all(),
        ]);

    }

    public function update(Request $request, int $id): RedirectResponse
    {
//        dd(request()->all());
        $this->checkAuthorization(auth()->user(), ['billboard.edit']);

        $billboard = Billboard::findOrFail($id);
//dd($billboard);
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'nullable',
            'type' => 'nullable',
            'brand' => 'nullable',
            'longitude' => 'nullable',
            'latitude' => 'nullable',
            'location' => 'nullable',
            'district' => 'nullable',
        ]);

        $billboard->update($validatedData);

        session()->flash('success', 'Billboard has been updated.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['billboard.delete']);

        $billboard = Billboard::findOrFail($id);
        $billboard->delete();

        session()->flash('success', 'Billboard has been deleted.');
        return redirect()->route('admin.billboards.index');
    }


}
