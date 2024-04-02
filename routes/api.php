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

    Route::resource('products', App\Http\Controllers\API\ProductAPIController::class)
        ->except(['create', 'edit']);
    Route::resource('categories', App\Http\Controllers\API\CategoryAPIController::class)
        ->except(['create', 'edit']);
    Route::post('/products/{id}', [App\Http\Controllers\API\ProductAPIController::class,'update'])->name('products.update');
    // Mobile verification
    Route::post('/send-verification-code' , 'MobileVerificationsAPIController@sendVerificationCode');
    Route::post('/validate-verification-code' , 'MobileVerificationsAPIController@validateVerificationCode');

    Route::group(['prefix' => 'user'] , function () {
        Route::get('/splash' , 'UserAPIController@getUserSplash');
        Route::post('/register' , 'UserAPIController@register');
        Route::post('/login' , 'UserAPIController@login');
        Route::post('/forget-password', 'PasswordResetsCodesAPIController@forgetPassword');
        Route::post('/reset-password', 'PasswordResetsCodesAPIController@resetPassword');

        Route::group(['middleware' => ['auth:api']] , function () {
            Route::post('/logout' , 'UserAPIController@logout');
            Route::get('/profile' , 'UserAPIController@profile');
            Route::post('/update-profile' , 'UserAPIController@updateUserProfile');
            Route::post('/change-password' , 'UserAPIController@changePassword');

            // Add Interested categories
            Route::post('/interested-categories' , 'UserAPIController@interestedCategories');

        });
    });
});
