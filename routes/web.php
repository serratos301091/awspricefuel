<?php

use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;

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

Route::get('/', function () { });

Route::post('getprice','PriceFuelController@priceFuel');
Route::get('getstates','CodePostalController@index');
Route::get('getstates/{d_estado}','CodePostalController@show');