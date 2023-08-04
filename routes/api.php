<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\PopulationController;
use App\Http\Controllers\CountryController;

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

Route::middleware('auth:api')->group(function () {

    // Route::group([
    //     'prefix' => 'users'
    // ], function () {
    //     //User
    //     Route::get('/', [UserController::class, 'index']);
    //     Route::get('/{id}', [UserController::class, 'show']);
    //     Route::put('/{id}', [UserController::class, 'update']);
    //     Route::delete('/{id}', [UserController::class, 'destroy']);
    // });

});

Route::post('/users', [UserController::class, 'store']);

Route::group([
    'prefix' => 'users'
], function () {
    //User
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}', [UserController::class, 'update']);
    Route::delete('/{id}', [UserController::class, 'destroy']);
});

Route::group([
    'prefix' => 'weathers'
], function () {
    //weathers
    Route::get('/test}', [WeatherController::class, 'test']);
    Route::get('/{city}', [WeatherController::class, 'getWeather']);
    
});

Route::group([
    'prefix' => 'rates'
], function () {
    //rates
    // Route::get('/', [RateController::class, 'getRates']);
    Route::get('/', [RateController::class, 'getRates']);
    Route::get('/convert/{amount}/{from}/{to}', [RateController::class, 'convert']);
});

Route::group([
    'prefix' => 'countries'
], function () {
    //countries
    Route::get('/', [CountryController::class, 'getCountriesData']);
    
    Route::get('/{countryCode}/pop', [CountryController::class, 'getCountries']);
    Route::get('/only', [CountryController::class, 'getCountries']);
});
