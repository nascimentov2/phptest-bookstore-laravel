<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return ['BookStore' => 'Web API version 0.1beta'];
});

Route::fallback(function(){

    \App\Jobs\NotifyPageNotFoundResquest::dispatch(); //simulates a page 404 notification using jobs and horizon

    return response()->json([
        'message' => 'Page Not Found. If error persists, contact '.env('SUPPORT_CONTACT')], 404);
});
