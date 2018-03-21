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

Route::apiResources(['courses' => 'CoursesController']);
Route::apiResources(['users' => 'UsersController']);

Route::get('getsubscriptions/{user_id?}', 'SubscriptionsController@getSubscriptions');
Route::any('subscribe', 'SubscriptionsController@subscribe');
Route::post('unsubscribe', 'SubscriptionsController@unsubscribe');
