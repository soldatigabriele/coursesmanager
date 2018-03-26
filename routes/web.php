<?php

Auth::routes();

Route::resource('courses', 'CoursesController', ['names' => [
    'index' => 'course-index',
    'create' => 'course-create',
    'show' => 'course-show',
    'destroy' => 'course-destroy',
    'store' => 'course-store',
    'edit' => 'course-edit',
    'update' => 'course-update',
]]);

Route::resource('partecipants', 'PartecipantsController', [
    'names' => [
        'index' => 'partecipant-index',
        'create' => 'partecipant-create',
        'destroy' => 'partecipant-destroy',
        'store' => 'partecipant-store',
        'edit' => 'partecipant-edit',
        'update' => 'partecipant-update',
    ], 
]);

Route::get('partecipants/{slug}', 'PartecipantsController@show')->name('partecipant-show');


Route::resource('newsletters', 'NewslettersController', [
    'names' => [
        'index' => 'newsletter-index',
        'create' => 'newsletter-create',
        'store' => 'newsletter-store',
        'show' => 'newsletter-show',
    ], 
]);

Route::get('corsi/scheda1', 'PartecipantsController@scheda1')->name('scheda-1');
Route::get('corsi/scheda2', 'PartecipantsController@scheda2')->name('scheda-2');

Route::get('getsubscriptions/{user_id?}', 'SubscriptionsController@getSubscriptions');
Route::post('subscribe', 'SubscriptionsController@subscribe');
Route::post('unsubscribe', 'SubscriptionsController@unsubscribe');

// Homepage
Route::get('/', 'HomeController@index')->name('home')->middleware('auth');


