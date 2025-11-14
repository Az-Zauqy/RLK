<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
Auth::routes(['verify' => true]);
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::group(['prefix' => '/admin','middleware' => ['auth','verified','IsAdmin']], function () {
Route::get('/dashboard','DashboardController@index')->name('admin.dashboard');

//laporan
Route::group(['prefix' => '/setting'], function () {
    Route::get('/','SettingController@index')->name('setting.data');
    Route::post('/update','SettingController@update')->name('setting.update.data');
    Route::get('/social','SettingController@social')->name('setting.social');
    Route::post('/social','SettingController@updateSocial')->name('setting.update.social');
    Route::get('/phone','SettingController@phone')->name('setting.phone');
    Route::post('/phone','SettingController@updatePhone')->name('setting.update.phone');
});

//master
Route::resource('/posts','PostsController',['as'=>'admin']);
Route::get('/blogs/status/{id}','BlogsController@status')->name('admin.blogs.status');
Route::post('blogs/taging','BlogsController@getTag')->name('admin.blogs.tagpost');
Route::resource('/blogs','BlogsController',['as'=>'admin']);
Route::delete('blogs/image/{id}/delete', 'BlogsController@deleteImage');
Route::post('blogs/image/{id}/replace', 'BlogsController@replaceImage');
Route::post('blogs/image/{id}/set-cover/{blogId}', 'BlogsController@setCover');

/*
=========================================================
==   TAMBAHKAN ROUTE EVENT ANDA DI SINI BERSAMA BLOGS  ==
=========================================================
*/
Route::resource('/events','EventController',['as' => 'admin']);


Route::group(['prefix' => '/master'], function () {
    Route::resource('/kategori','KategoriController',['as' => 'master']);
    Route::resource('/sliders','SlidersController',['as' => 'master']);
    Route::resource('/kelengkapan','KelengkapanController',['as' => 'master']);
    Route::resource('/provinsi','ProvinsiController',['as' => 'master']);
    Route::resource('/kabupaten','KabupatenController',['as' => 'master']);
    Route::resource('/kecamatan','KecamatanController',['as' => 'master']);
    // Route::resource('/desa','DesaController',['as' => 'master']);
    Route::resource('/shipper','ShipperController',['as' => 'master']);
    Route::resource('/karya','KaryaController',['as' => 'master']);
    Route::get('/product/reset/{id}','ProductsController@resetBid')->name('master.product.reset.bid');
    Route::get('/product/status/{id}','ProductsController@status')->name('master.product.status');
    Route::resource('/product','ProductsController',['as' => 'master']);
});
Route::resource('/daftar-penawaran','DaftarPenawaranController',['as' => 'admin']);
Route::resource('/daftar-pemenang','DaftarPemenangController',['as' => 'admin']);
//transaksi
// Route::group(['prefix' => '/transaksi'], function () {
//     Route::get('/cari/barang','BarangController@dataBarang')->name('transaksi.data.barang');
//     Route::get('/stok/barang/{id}','BarangController@getBarang')->name('transaksi.get.barang');
    
//     Route::resource('/masuk','MasukController',['as' => 'transaksi']);
//     Route::resource('/keluar','KeluarController',['as' => 'transaksi']);
//     Route::resource('/transaksi','TransaksiController',['as' => 'transaksi']);

// });

// //print
// Route::group(['prefix' => '/print'], function () {
//     Route::get('/masuk/{id}','PrintController@masuk')->name('print.masuk');
//     Route::get('/keluar/{id}','PrintController@keluar')->name('print.keluar');

//     Route::get('/stok','PrintController@stok')->name('print.stok');
//     Route::get('/laporan/masuk','PrintController@reportMasuk')->name('print.laporan.masuk');
//     Route::get('/laporan/keluar','PrintController@reportKeluar')->name('print.laporan.keluar');
// });

// //laporan
// Route::group(['prefix' => '/laporan'], function () {
//     Route::get('/stok','ReportController@stok')->name('report.stok');
//     Route::get('/masuk','ReportController@masuk')->name('report.masuk');
//     Route::get('/keluar','ReportController@keluar')->name('report.keluar');
// });

//users
 Route::get('/user/status/{id}','UsersController@status')->name('admin.user.status');
 Route::resource('/user','UsersController',['as' => 'admin']);
});