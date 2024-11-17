<?php

namespace App\Http\Controllers;

use App\Models\Billboard;
use App\Models\CentralPoint;
use App\Models\Retailer;
use Illuminate\Http\Request;
use App\Models\Dealer;

class MapAnalyticsController extends Controller
{

    public function mapAnalytics() {
        $this->checkAuthorization(auth()->user(), 'map.analytics');

        $dealers = Dealer::all();
        $retailers = Retailer::all();
        $billboards = Billboard::all();
        $centralPoints = CentralPoint::all();

        // Count entities grouped by district
        $dealersByDistrict = $dealers->groupBy('district')->map->count();
        $retailersByDistrict = $retailers->groupBy('district')->map->count();
        $billboardsByDistrict = $billboards->groupBy('district')->map->count();

        return view('backend.pages.map-analytics.map-analytics', [
            'dealers' => $dealers,
            'retailers' => $retailers,
            'billboards' => $billboards,
            'centralPoints' => $centralPoints,
            'dealersByDistrict' => $dealersByDistrict,
            'retailersByDistrict' => $retailersByDistrict,
            'billboardsByDistrict' => $billboardsByDistrict,
        ]);
    }
}
