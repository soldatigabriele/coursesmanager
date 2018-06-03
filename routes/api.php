<?php

use Illuminate\Http\Request;

Route::group(['middleware' => 'ApiAuth'], function () {

    Route::get('/partecipant', function (Request $request) {
        return 'authorizzato';}
    );

    Route::apiResources(['courses' => 'CoursesController']);
    Route::apiResources(['partecipants' => 'PartecipantsController']);

    Route::get('getsubscriptions/{user_id?}', 'SubscriptionsController@getSubscriptions');
    Route::any('subscribe', 'SubscriptionsController@subscribe');
    Route::post('unsubscribe', 'SubscriptionsController@unsubscribe');
});
