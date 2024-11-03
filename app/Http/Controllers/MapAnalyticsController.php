<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dealer;

class MapAnalyticsController extends Controller
{

    public function mapAnalytics(){
        $this->checkAuthorization(auth()->user(), 'map.analytics');
        $dealers = \App\Models\Dealer::all()->toArray(); // Get dealers as an array

        return view('backend.pages.map-analytics.map-analytics',[
            'dealers' => $dealers
        ]);
    }
}
