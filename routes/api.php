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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// mandatory start_date and end_date
// http://127.0.0.1:8000/api/statistics?start_date=2022-3-8&end_date=2022-3-08
Route::get('statistics', '\App\Http\Controllers\Api\ApiController@statistics');
