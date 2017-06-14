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

Route::get('/user/activation/{token}', 'Auth\RegisterController@userActivation');

Route::get('/', 'LandingController@index')->name('home');

Route::get('/fetchStation', 'LandingController@getJsonOpenMaps');

/*
Route::get('/login', 'Auth\LoginController@index');
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/register', 'Auth\RegisterController@index');
Route::post('/register', 'Auth\RegisterController@register');
*/
/*Route::get('/logout', function(){
	Auth::logout();

	return redirect('/');#'Auth\LoginController@logout'
});*/

Route::group(['middleware' => 'auth'], function () {
	Route::get('/userpage', 'UserPageController@index')->name('userpage');
	Route::post('/userpage', 'UserPageController@index');
	Route::post('/addvehicle', 'UserPageController@add')->name('addvehicle');

  Route::get('/userpage/vehicles/edit/{id}', 'UserPageController@editVehicle')->name('editVehicle');
  Route::post('/userpage/vehicles/edit/{vehicleid}', 'UserPageController@saveVehicle')->name('postEditVehicle');

  Route::get('/userpage/info/edit/{id}', 'UserPageController@editInfo')->name('editInfo');
  Route::post('/userpage/info/edit/{id}', 'UserPageController@saveInfo')->name('postInfo');

  Route::post('/userpage', 'UserPageController@postTripData')->name('sendTripData');

  Route::post('/userpage/editPass', 'UserPageController@postCredentials')->name('editPass');

  Route::get('/userpage', 'UserPageController@index')->name('planRoute');
  Route::get('/userpage/vehicles', 'UserPageController@getVehicles')->name('manageVehicles');
  Route::get('/userpage/info', 'UserPageController@getInfo')->name('manageInfo');
  Route::get('/userpage/vehicles/{id}', 'UserPageController@removeVehicle')->name('removeVehicle');
});

Route::post('/showGpsCoordinates', 'LandingController@index');

//API
Route::get('/api/districts', 'LandingController@apiDistricts')->name('apidistricts');
Route::get('/api/brands', 'LandingController@apiBrands')->name('apibrands');
Route::get('/api/stations/{district}/{brand}/{fuelType}', 'LandingController@apiStations')->name('apistations');
Route::get('/api/stationsup/{origin}/{destination}/{autonomy}', 'UserPageController@apiStations')->name('apistationsup');
