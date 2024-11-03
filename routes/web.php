<?php

use App\Http\Controllers\ApiProxyController;
use App\Http\Controllers\Backend\DealerController;
use App\Http\Controllers\MapAnalyticsController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Backend\AdminsController;
use App\Http\Controllers\Backend\Auth\ForgotPasswordController;
use App\Http\Controllers\Backend\Auth\LoginController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\RolesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('/', 'HomeController@redirectAdmin')->name('index');
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Admin routes
 */
Route::get('/barikoi/autocomplete', [App\Http\Controllers\BarikoiController::class, 'autocomplete']);

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RolesController::class);
    Route::resource('admins', AdminsController::class);

    // Login Routes.
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login/submit', [LoginController::class, 'login'])->name('login.submit');

    // Logout Routes.
    Route::post('/logout/submit', [LoginController::class, 'logout'])->name('logout.submit');

    // Forget Password Routes.
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset/submit', [ForgotPasswordController::class, 'reset'])->name('password.update');

    //Dealer
    Route::resource('dealers', DealerController::class);
    Route::get('all-dealers', [DealerController::class,'allDealers'])->name('allDealers');
    Route::get('map-analytics', [MapAnalyticsController::class, 'mapAnalytics'])->name('map.analytics');
})->middleware('auth:admin');
Route::get('/test',[TestController::class,'index'])->name('test');
//Route::get('/api/proxy/autocomplete', [ApiProxyController::class, 'fetchAutocomplete']);

