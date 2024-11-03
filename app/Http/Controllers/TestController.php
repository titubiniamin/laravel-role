<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    function index() {
        $dealers = \App\Models\Dealer::all()->toArray(); // Get dealers as an array
        return view('test', [
            'dealers' => $dealers // Pass the array directly to the view
        ]);
    }
}
