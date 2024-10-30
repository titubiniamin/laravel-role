<?php
declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

// Use this import for the Request class

class DealerController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.view']);

        return view('backend.pages.dealers.index', [
            'dealers' => Dealer::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['dealer.create']);

        return view('backend.pages.dealers.create');
    }

    public function store(Request $request): RedirectResponse
    {
//dd(request()->all());
        // Check authorization before proceeding
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
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'location' => 'nullable',
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
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'location' => 'nullable',
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
}
