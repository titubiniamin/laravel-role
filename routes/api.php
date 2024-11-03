<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// routes/api.php
use App\Http\Controllers\ApiProxyController;

Route::get('/proxy/autocomplete', [ApiProxyController::class, 'fetchAutocomplete']);
Route::get('/proxy/reverse-geocode', [ApiProxyController::class, 'reverseGeocode']);

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
