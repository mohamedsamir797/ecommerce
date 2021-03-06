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

        Route::resource('/categories','MainCategoryController')->except('destroy');
        Route::get('categories/{id}/delete','MainCategoryController@destroy')->name('categories.destroy');
        Route::get('changeStatus/{id}','MainCategoryController@changeStatus')->name('categories.changeStatus');

    ################# End Main Categories Routes #######################


    ################# Begin Sub Categories Routes #######################

    Route::resource('/subcategories','SubCategoryController')->except('destroy');
    Route::get('subcategories/{id}/delete','SubCategoryController@destroy')->name('categories.destroy');
    Route::get('subcategories/{id}','SubCategoryController@changeStatus')->name('subcategories.changeStatus');

    ################# End Sub Categories Routes #######################

    ################# Begin  Vendors Routes #######################

    Route::resource('/vendors','VendorsController')->except('destroy');
    Route::get('vendors/{id}/delete','VendorsController@destroy')->name('vendors.destroy');
    Route::get('vendors/{id}/changeStatus','VendorsController@changeStatus')->name('vendors.changeStatus');

    ################# End  Vendors Routes #######################

});


Route::group(['namespace' =>'Admin','middleware'=>'guest:admin'],function (){
    Route::get('login','LoginController@getLogin')->name('get.admin.login');
    Route::post('login','LoginController@Login')->name('admin.login');

});


//
//Route::get('subcategory',function (){
//    $maincategory = App\Models\MainCategory::find(20);
//   return $maincategory->subCategory ;
//});
//
//Route::get('maincategory',function (){
//    $subcatefory = App\Models\SubCategory::find(1);
//    return $subcatefory->mainCategory ;
//});