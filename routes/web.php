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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource("map", 'MapController');
Route::resource("tenant", "TenantController");

//External API
Route::get('external/storage/map/original/{id}', 'ApiController@originalImageDownload');
Route::get('external/image_infos/{map_id}', 'ApiController@getMapInfos');

Route::get('external/map/{mapId}', 'ApiController@mapJsonById');
Route::post('external/process/map/{mapId}', 'ApiController@processMap');

Route::get('external/tenant/map/{gmap_id}', 'ApiController@mapJsonByTenant');
Route::get('external/tenant/all', 'ApiController@tenantJsonAll');
Route::get('external/tenant/by_id/{tenantId}', 'ApiController@tenantJsonById');
Route::get('external/tenant/delete/{tenant_id}', 'TenantController@destroy');

Route::get('external/map/unprocessed/all', 'ApiController@allUnprocessedMap');

Route::post('external/test/post', 'ApiController@postTest');
Route::post('external/login', 'ApiController@authenticate');

Route::get('external/tenant/by_places_id/{places_id}', 'ApiController@tenantByPlacesId');

Route::post('external/place/add', 'TenantController@store');
Route::post('external/place/edit/{id}', 'TenantController@update');
Route::post('external/place/search', 'TenantController@getByAddress');

Route::get('external/tenant/by_user_id/{user_id}', 'ApiController@tenantByUserId');
Route::get('external/map/by_tenant_id/{tenant_id}', 'ApiController@mapByTenantId');

Route::post('external/map/store', 'MapController@store');
Route::post('external/map/update/{id}', 'MapController@update');
Route::delete('external/map/delete/{id}', 'MapController@destroy');

Route::get('external/maps/by_map_id/{map_id}', 'ApiController@mapsByMapId');
Route::post('external/marker/add', 'ApiController@storeMarker');
Route::post('external/marker/edit', 'ApiController@editMarker');
Route::get('external/marker/delete/{id}', 'ApiController@deleteMarker');



Route::get('external/marker/by_map_id/{map_id}', 'ApiController@getMarkerByMapId');
Route::get('external/marker/by_tenant_id/{tenant_id}', 'ApiController@getMarkerByTenantId');
Route::get('external/marker/by_id/{marker_id}', 'ApiController@getMarkerById');


Route::get('external/map/processed_map/download/{id}', 'ApiController@getProcessedImage');
Route::get('external/map/processed_map/by_marker/download/{id}', 'ApiController@getCalibrateScanPointImage');
Route::post('external/marker/calibrate_scan_point/', 'ApiController@saveScanPointCalibrationData');

Route::get('test/process/image/{map_id}', 'ApiController@tryProcessImage');
Route::get('external/map/map_array_data/{map_id}', 'ApiController@getArrayMapData');

Route::get('external/marker/generate_qr_code/{id}', 'ApiController@generateMarkerQrCode');

Route::post('external/report/add', 'ApiController@storeReport');
Route::post('external/user/login', 'ApiController@externalUserLogin');

Route::get('external/report/{id}', 'ApiController@getReportById');
Route::get('external/process_report_marker/{id}', 'ApiController@processMarkerReport');

Route::post('external/tenant/verify', 'ApiController@verifyTenant');

Route::get('asd1', function () {
	return \App\User::find(1)->reports;
});
