<?php

use Illuminate\Http\Request;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([ 'namespace' => 'API','middleware' => 'apikey'], function () {
	Route::post('/login', 'LoginController@checkConnection');
	Route::get('/app-setting', 'RequestController@urlSetting');
});

Route::group([ 'namespace' => 'API','middleware' => 'token'], function () {
	Route::post('/toko/password/update', 'LoginController@updatePassword');
	Route::get('/promo', 'PromoController@promo');
	Route::get('/bank', 'RequestController@bankDaftar');
	Route::get('/rekeningbank', 'BankController@getRekening');
});

Route::group([ 'namespace' => 'API','middleware' => 'collector'], function () {
	Route::post('/collector/password/update', 'LoginController@updateCollectorPassword');
	Route::get('/register/lists', 'RegisterController@lists'); 
	Route::get('/idenpembayaran/lists', 'PembayaranController@pullIdenpembayaran'); 
	Route::get('/hasiltagihan/lists', 'PembayaranController@pullHasiltagihan'); 
	Route::get('/setoruang/lists', 'SetoruangController@lists'); 
	Route::post('/setoruang/create', 'SetoruangController@create'); 
	Route::post('/sync', 'SetoruangController@sync'); 
	Route::get('/pembayaran/status', 'PembayaranController@lists'); 
	Route::get('/pembayaran/lists', 'PembayaranController@datalists'); 
	Route::get('/transfer/lists', 'SetoruangController@transferLists'); 
});

Route::group([ 'namespace' => 'API','middleware' => 'toko'], function () {
	Route::get('/piutang', 'RegisterController@piutang'); 
	Route::get('/nota', 'RegisterController@notaPiutang'); 
	Route::get('/request/list', 'RequestController@requesttoko'); 
	Route::post('/request/create', 'RequestController@create'); 
	Route::get('/toko/detail', 'LoginController@detailToko');
	Route::get('/point', 'PromoController@getpoint');
});