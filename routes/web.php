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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//=========ADMIN==========

Route::group(['middleware' => 'auth'], function () {
    //phần admin-dashboard
    Route::get('dashboard', 'DashboardController@show')->name('user.admin');
    Route::get('admin', 'DashboardController@show');
    //phần admin-user
    Route::group(['prefix' => 'admin/user'], function () {
        Route::group(['prefix' => 'role'], function () {

        });
        Route::get('/', 'AdminUserController@list');
        Route::get('list', 'AdminUserController@list');
        Route::get('info', 'AdminUserController@info');
        Route::get('add', 'AdminUserController@add');
        Route::post('store', 'AdminUserController@store');
        Route::get('delete/{id}', 'AdminUserController@delete')->name('delete_user');
        Route::get('action', 'AdminUserController@action');
        Route::get('edit/{id}', 'AdminUserController@edit')->name('edit.user');
        Route::post('update/{id}', 'AdminUserController@update');
    });
    //phần admin-page
    Route::group(['prefix' => 'admin/page'], function () {
        Route::get('/', 'AdminPageController@list');
        Route::get('list', 'AdminPageController@list');
        Route::get('add', 'AdminPageController@add');
        Route::post('store', 'AdminPageController@store');
        Route::get('delete/{id}', 'AdminPageController@delete')->name('delete.page');
        Route::get('action', 'AdminPageController@action');
        Route::get('edit/{id}', 'AdminPageController@edit')->name('edit.page');
        Route::post('update/{id}', 'AdminPageController@update');
    });
    //phần admin-PostCat
    Route::group(['prefix' => 'admin/post'], function () {
        Route::group(['prefix' => 'cat'], function () {
            Route::get('/', 'AdminPostCatController@list');
            Route::get('list', 'AdminPostCatController@list');
            Route::get('add', 'AdminPostCatController@add');
            Route::post('store', 'AdminPostCatController@store');
            Route::get('delete/{id}', 'AdminPostCatController@delete')->name('delete.post.cat');
            Route::get('edit/{id}', 'AdminPostCatController@edit')->name('edit.post.cat');
            Route::post('update/{id}', 'AdminPostCatController@update');
        });
        Route::get('/', 'AdminPostController@list');
        Route::get('list', 'AdminPostController@list');
        Route::get('add', 'AdminPostController@add');
        Route::get('action', 'AdminPostController@action');
        Route::post('store', 'AdminPostController@store');
        Route::get('delete/{id}', 'AdminPostController@delete')->name('delete.post');
        Route::get('edit/{id}', 'AdminPostController@edit')->name('edit.post');
        Route::post('update/{id}', 'AdminPostController@update');
        Route::get('checkStatus', 'AdminPostController@checkStatus');
    });
    Route::group(['prefix' => 'admin/product'], function () {
        Route::group(['prefix' => 'cat'], function () {
            Route::get('/', 'AdminProductCatController@list');
            Route::get('list', 'AdminProductCatController@list');
            Route::get('add', 'AdminProductCatController@add');
            Route::post('store', 'AdminProductCatController@store');
            Route::get('delete/{id}', 'AdminProductCatController@delete')->name('delete.product.cat');
            Route::get('edit/{id}', 'AdminProductCatController@edit')->name('edit.product.cat');
            Route::post('update/{id}', 'AdminProductCatController@update');
        });
        Route::get('/', 'AdminProductController@list');
        Route::get('list', 'AdminProductController@list');
        Route::get('add', 'AdminProductController@add');
        Route::get('action', 'AdminProductController@action');
        Route::post('store', 'AdminProductController@store');
        Route::get('delete/{id}', 'AdminProductController@delete')->name('delete.product');
        Route::get('edit/{id}', 'AdminProductController@edit')->name('edit.product');
        Route::post('update/{id}', 'AdminProductController@update');
        Route::get('checkStatus', 'AdminProductController@checkStatus');
    });
    Route::group(['prefix' => 'admin/slider'], function () {
        Route::get('/', 'AdminSliderController@list');
        Route::get('list', 'AdminSliderController@list');
        Route::get('add', 'AdminSliderController@add');
        Route::get('action', 'AdminSliderController@action');
        Route::post('store', 'AdminSliderController@store');
        Route::get('delete/{id}', 'AdminSliderController@delete')->name('delete.slider');
        Route::get('edit/{id}', 'AdminSliderController@edit')->name('edit.slider');
        Route::post('update/{id}', 'AdminSliderController@update');
    });
    Route::group(['prefix' => 'admin/ads'], function () {
        Route::get('/', 'AdminAdsController@list');
        Route::get('list', 'AdminAdsController@list');
        Route::get('add', 'AdminAdsController@add');
        Route::get('action', 'AdminAdsController@action');
        Route::post('store', 'AdminAdsController@store');
        Route::get('delete/{id}', 'AdminAdsController@delete')->name('delete.ads');
        Route::get('edit/{id}', 'AdminAdsController@edit')->name('edit.ads');
        Route::post('update/{id}', 'AdminAdsController@update');
    });
});
Route::get('home', 'GuestController@index')->name('guest.index');
Route::get('/', 'GuestController@index');