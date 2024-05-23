<?php

use App\Http\Controllers\API\UserAPIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['local_handler']] , function () {

    Route::resource('products', App\Http\Controllers\API\ProductAPIController::class)->middleware('auth:api')
        ->except(['create', 'edit']);
    Route::resource('categories', App\Http\Controllers\API\CategoryAPIController::class)
        ->except(['create', 'edit']);
    Route::resource('areas', App\Http\Controllers\API\AreasAPIController::class)
        ->except(['create', 'edit']);

    Route::get('/terms-conditions' , 'UserAPIController@termsConditions');

    // Mobile verification
    Route::post('/send-verification-code' , 'MobileVerificationsAPIController@sendVerificationCode');
    Route::post('/validate-verification-code' , 'MobileVerificationsAPIController@validateVerificationCode');

    Route::group(['prefix' => 'user'] , function () {
        Route::get('/splash' , 'UserAPIController@getUserSplash');
        Route::get('/home' , 'UserAPIController@getHome');

        Route::post('/register' , 'UserAPIController@register');
        Route::post('/login' , 'UserAPIController@login');
        Route::post('/forget-password', 'PasswordResetsCodesAPIController@forgetPassword');
        Route::post('/reset-password', 'PasswordResetsCodesAPIController@resetPassword');

        Route::get('/products/{id}' , 'ProductAPIController@show');

        Route::group(['middleware' => ['auth:api']] , function () {
            Route::post('/logout' , 'UserAPIController@logout');
            Route::delete('/delete-account' , 'UserAPIController@deleteAccount');

            Route::get('/profile' , 'UserAPIController@profile');
            Route::post('/update-profile' , 'UserAPIController@updateProfile');
            Route::post('/change-password' , 'UserAPIController@changePassword');
            Route::get('/issues-types' , 'UserAPIController@issuesTypes');
            Route::post('/contact-us' , 'UserAPIController@contactUs');
            Route::get('/favourites-products' , 'UserAPIController@getFavourites');

            Route::resource('users-addresses', App\Http\Controllers\API\UsersAddressesAPIController::class)->except('update');
            Route::post('users-addresses/{id}' , 'UsersAddressesAPIController@update');

            Route::post('/products/{id}', [App\Http\Controllers\API\ProductAPIController::class,'update'])->name('products.update');
            Route::get('/my-products' , 'ProductAPIController@index');
            Route::post('/favourites/{id}' , 'ProductAPIController@productFavourite');
            // Interested categories
            Route::get('/my-interested-categories' , 'UserAPIController@myInterestedCategories');
            Route::post('/interested-categories' , 'UserAPIController@interestedCategories');
        });
    });

    Route::group(['prefix' => 'orders' , 'middleware' => 'auth:api'] , function () {
    Route::post('/store/{type}' , 'OrderAPIController@store')->middleware('auth:api');
    Route::post('/change-status' , 'OrderAPIController@changeStatus')->middleware('auth:api');

        // request list for notification
        Route::get('/request' , 'OrderAPIController@getRequests')->middleware('auth:api');
        Route::get('/request/{id}' , 'OrderAPIController@getRequestById')->middleware('auth:api');

        // for list order
        Route::get('/' , 'OrderAPIController@getOrders')->middleware('auth:api');

    });
    // for reviews
    Route::group(['prefix' => 'reviews'] , function () {
    Route::post('/' , 'UserAPIController@storeReview')->middleware('auth:api');
    Route::get('/product/{id}' , 'UserAPIController@getProductReview');
    Route::get('/seller/{id}' , 'UserAPIController@getSellerReview');
    });
    Route::group(['prefix' => 'sellers'] , function () {
    Route::get('get/info/{id}' , 'UserAPIController@getSellerInfo');
    });
});
