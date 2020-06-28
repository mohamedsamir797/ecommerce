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

define('PAGINATION',10);

Route::group(['namespace' =>'Admin','middleware'=>'auth:admin'],function (){

    Route::get('/','DashboardController@index')->name('admin.dashboard');

    ################# Begin Language Routes #######################

    Route::group(['prefix'=>'languages'],function (){
        Route::get('/','LanguageController@index')->name('admin.languages');
        Route::get('/create','LanguageController@create')->name('admin.languages.create');
        Route::post('/store','LanguageController@store')->name('admin.languages.store');
        Route::get('edit/{id}','LanguageController@edit')->name('admin.languages.edit');
        Route::put('/update/{id}','LanguageController@update')->name('admin.languages.update');
        Route::get('/delete/{id}','LanguageController@destroy')->name('admin.languages.delete');
    });

    ################# End Language Routes #######################


    ################# Begin Main Categories Routes #######################

        Route::resource('/categories','MainCategoryController');

    ################# End Main Categories Routes #######################


    ################# Begin Main Vendors Routes #######################

    Route::resource('/vendors','VendorsController');

    ################# End Main Vendors Routes #######################

});


Route::group(['namespace' =>'Admin','middleware'=>'guest:admin'],function (){
    Route::get('login','LoginController@getLogin')->name('get.admin.login');
    Route::post('login','LoginController@Login')->name('admin.login');

});
