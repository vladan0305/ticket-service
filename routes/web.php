<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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



Route::get('/', 'App\Http\Controllers\HomeController@index');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('new-ticket', 'App\Http\Controllers\TicketsController@create');
Route::post('new-ticket', 'App\Http\Controllers\TicketsController@store');
Route::get('my_tickets', 'App\Http\Controllers\TicketsController@userTickets');
Route::get('tickets/{ticket_id}', 'App\Http\Controllers\TicketsController@show');
Route::post('comment', 'App\Http\Controllers\CommentsController@postComment');
Route::group(['prefix' => 'admin', 'middleware' => 'admin'], function (){
    Route::get('tickets', 'App\Http\Controllers\TicketsController@index');
    Route::get('{id}/tickets', 'App\Http\Controllers\TicketsController@myTickets')->name('my_tickets');
    Route::post('close_ticket/{ticket_id}', 'App\Http\Controllers\TicketsController@close');
});

Route::post('tickets/{ticket_id}/change_status', 'App\Http\Controllers\TicketsController@changeStatus')->middleware('admin')->name('change_status');
Route::post('tickets/{ticket_id}/upload_image', 'App\Http\Controllers\TicketsController@uploadImage')->middleware('admin')->name('upload_image');
Route::post('claim_ticket/{ticket_id}', 'App\Http\Controllers\TicketsController@claimTicket')->middleware('admin');
