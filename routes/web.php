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


Auth::routes();

Route::group(['prefix' => \LaravelLocalization::setLocale() , 'middleware' => 'auth'], function(){

    Route::get('/','\App\Http\Controllers\Dashboard\HomeController@index')->name('dashboard.home');

        Route::get('/logout', '\App\Http\Controllers\Dashboard\UserController@logout')->name('dashboard.logout');

        Route::get('/','\App\Http\Controllers\Dashboard\HomeController@index')->name('dashboard.home');

    // dashboard User
         Route::resource('users',\App\Http\Controllers\Dashboard\UserController::class);
         Route::get('loadUsers', 'App\Http\Controllers\Dashboard\UserController@loadAjaxDatatable')->name('users.ajax');
         Route::get('users/change-password/{id}', 'App\Http\Controllers\Dashboard\UserController@change_password_form')->name('users.changeForm');
         Route::put('users//change_password/{id}', 'App\Http\Controllers\Dashboard\UserController@change_password')->name('users.changePassword');

         // category route
    Route::resource('categories',\App\Http\Controllers\Dashboard\CategoryController::class);
    Route::get('loadCategories', 'App\Http\Controllers\Dashboard\CategoryController@loadAjaxDatatable')->name('categories.ajax');

    // Products Route
    Route::resource('products',\App\Http\Controllers\Dashboard\ProductController::class);
    Route::get('loadProducts', 'App\Http\Controllers\Dashboard\ProductController@loadAjaxDatatable')->name('products.ajax');
});

Route::resource('orders', App\Http\Controllers\OrderController::class);
Route::resource('areas', App\Http\Controllers\AreasController::class);
Route::resource('users-addresses', App\Http\Controllers\UsersAddressesController::class);