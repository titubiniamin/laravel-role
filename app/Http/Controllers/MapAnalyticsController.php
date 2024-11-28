<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use App\Models\CentralPoint;
use App\Models\District;
use App\Models\Highwall;
use App\Models\Retailer;
use App\Models\Shopsign;
use Illuminate\Http\Request;
use App\Models\Dealer;

class MapAnalyticsController extends Controller
{

    public function mapAnalytics()
    {
        $this->checkAuthorization(auth()->user(), 'map.analytics');

        $dealers = Dealer::all();
        $retailers = Retailer::all();
        $billboards = Billboard::all();
        $centralPoints = CentralPoint::all();
        $shopsigns = ShopSign::all();
        $highwalls = HighWall::all();
        $districts = District::all();

        // Count entities grouped by district
        $dealersByDistrict = $dealers->groupBy('district')->map->count();
        $retailersByDistrict = $retailers->groupBy('district')->map->count();
        $billboardsByDistrict = $billboards->groupBy('district')->map->count();
        $shopsignsByDistrict = $shopsigns->groupBy('district')->map->count();
        $highwallsByDistrict = $highwalls->groupBy('district')->map->count();


//dd($shopsignsByDistrict);
        return view('backend.pages.map-analytics.map-analytics', [
            'dealers' => $dealers,
            'retailers' => $retailers,
            'billboards' => $billboards,
            'centralPoints' => $centralPoints,
            'shopsigns' => $shopsigns,
            'highwalls' => $highwalls,
            'dealersByDistrict' => $dealersByDistrict,
            'retailersByDistrict' => $retailersByDistrict,
            'billboardsByDistrict' => $billboardsByDistrict,
            'shopsignsByDistrict' => $shopsignsByDistrict,
            'highwallsByDistrict' => $highwallsByDistrict,
            'districts' => $districts,
        ]);
    }
}
