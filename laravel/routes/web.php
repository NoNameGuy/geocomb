<?php

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

/*Route::get('/', function () {
    return view('welcome');
});*/

#Route::get('/', 'LandingController@index');
#Route::post('/', 'LandingController@index');

Route::get('/',
  ['as' => 'contact', 'uses' => 'LandingController@index']);
Route::post('/',
  ['as' => 'search_station', 'uses' => 'LandingController@index']);
Route::post('/',
  ['as' => 'current_location', 'uses' => 'LandingController@index']);

  Auth::routes();

Route::get('/fetch', 'LandingController@fetchData');
Route::get('/fetchStation/{id}', 'LandingController@fetchStationData');
Route::get('/fetchStation', 'LandingController@fetchStationData');#para apagar
Route::get('/maps', 'LandingController@mapsApi');
Route::get('/fetchId', 'LandingController@fetchStationID');


Route::get('/login', 'Auth\LoginController@index');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/register', 'Auth\RegisterController@index');
Route::post('/register', 'Auth\RegisterController@register');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/user_page', 'UserPageController@index');
Route::post('/user_page', 'UserPageController@index');
Route::post('/add_vehicle', 'UserPageController@add');

Route::post('/showGpsCoordinates', 'LandingController@receiveGPSCoordinates');
