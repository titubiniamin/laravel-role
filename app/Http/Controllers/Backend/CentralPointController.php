<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CentralPoint;
use App\Models\Dealer;
use Illuminate\Http\Request;

class CentralPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->checkAuthorization(auth()->user(), ['central-point.view']);
        $centralPoints = CentralPoint::all();
        return view('backend.pages.central-point.index', [
            'centralPoints' => $centralPoints
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pages.central-point.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['central-point.create']);
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'district' => 'required|string|unique:central_points,district',
        ]);

        // Create the new CentralPoint record
        CentralPoint::create($request->all());

        // Flash success message to the session
        session()->flash('success', 'Central Point has been created.');

        // Redirect back to the previous page or a specific route
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        dd('show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $centralPoint=CentralPoint::find($id);
        return view('backend.pages.central-point.edit', ['centralPoint'=>$centralPoint]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $centralPoint=CentralPoint::findOrfail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'district' => 'required|string|unique:central_points,district,' . $centralPoint->id,
        ]);

        // Update the CentralPoint record
        $update = $centralPoint->update($request->all());

        // Flash success message to the session
        session()->flash('success', 'Central Point has been updated.');

        // Redirect back to the previous page or a specific route
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
