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

// Route::get('courses', 'CoursesController@index');
Route::apiResources(['courses' => 'CoursesController']);
Route::apiResources(['users' => 'UsersController']);

// Route::post('subscribe', function(){return 'ok';});
// Route::apiResources(['subscriptions' => 'SubscriptionsController']);
Route::post('subscriptions', 'API/SubscriptionsController@subscribe');
Route::get('subscriptions', 'SubscriptionsController@index');