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
        'edit' => 'partecipant-edit',
        'update' => 'partecipant-update',
    ], 
]);
Route::middleware('auth')->group(function(){
    Route::get('partecipants', 'PartecipantsController@index')->name('partecipant-index');
    Route::get('partecipants/create', 'PartecipantsController@create')->name('partecipant-create');
    Route::get('partecipants/{partecipant}/edit', 'PartecipantsController@edit')->name('partecipant-edit');
    Route::put('partecipants/{partecipant}', 'PartecipantsController@update')->name('partecipant-update');
    Route::delete('partecipants/{partecipant}', 'PartecipantsController@destroy')->name('partecipant-destroy');
});

Route::post('partecipants', 'PartecipantsController@store')->name('partecipant-store');
Route::get('partecipants/{slug}', 'PartecipantsController@show')->name('partecipant-show');


Route::resource('newsletters', 'NewslettersController', [
    'names' => [
        'index' => 'newsletter-index',
        'create' => 'newsletter-create',
        'store' => 'newsletter-store',
        'show' => 'newsletter-show',
    ],
]);

Route::get('corsi/scheda/1', 'PartecipantsController@scheda1')->name('scheda-1');
Route::get('corsi/scheda/2', 'PartecipantsController@scheda2')->name('scheda-2');

Route::get('getsubscriptions/{user_id?}', 'SubscriptionsController@getSubscriptions');
Route::post('subscribe', 'SubscriptionsController@subscribe');
Route::post('unsubscribe', 'SubscriptionsController@unsubscribe');

// Homepage
Route::get('/', 'HomeController@index')->name('home')->middleware('auth');


