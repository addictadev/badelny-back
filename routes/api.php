<?php

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
    Route::get('/categories' , 'CategoryAPIController@index');

    Route::group(['prefix' => 'user'] , function () {
        Route::get('/splash' , 'UserAPIController@getUserSplash');
        Route::post('/register' , 'UserAPIController@userRegister');
        Route::post('/login' , 'UserAPIController@login');

        Route::group(['middleware' => ['auth:api' , 'role:user']] , function () {
            Route::post('/logout' , 'UserAPIController@logout');
            Route::post('/update-profile' , 'UserAPIController@updateUserProfile');
            Route::post('/change-password' , 'UserAPIController@changePassword');

            // Add Interested categories
            Route::post('/interested-categories' , 'UserAPIController@interestedCategories');

            // Mobile verification
            Route::post('/send-verification-code' , 'MobileVerificationsAPIController@sendVerificationCode');
            Route::post('/validate-verification-code' , 'MobileVerificationsAPIController@validateVerificationCode');
        });
    });
});

