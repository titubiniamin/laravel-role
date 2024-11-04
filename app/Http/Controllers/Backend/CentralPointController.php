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

        CentralPoint::create($request->all());
        session()->flash('success', 'Central Point has been created.');

        // Redirect to the index route for dealers
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
