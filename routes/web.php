<?php

Auth::routes();

Route::resource('courses', 'CoursesController', ['names' => [
    'index' => 'courses.index',
    'create' => 'courses.create',
    'show' => 'courses.show',
    'destroy' => 'courses.destroy',
    'store' => 'courses.store',
    'edit' => 'courses.edit',
    'update' => 'courses.update',
]]);
Route::get('courses/{course}/export')
    ->name('courses.export')
    ->uses('CoursesController@export')
    ->middleware('auth');

Route::resource('partecipants', 'PartecipantsController', [
    'names' => [
        'edit' => 'partecipant.edit',
        'update' => 'partecipant.update',
    ],
]);
Route::middleware('auth')->group(function () {
    Route::get('partecipants', 'PartecipantsController@index')->name('partecipant.index');
    Route::get('partecipants/create', 'PartecipantsController@create')->name('partecipant-create');
    Route::get('partecipants/{partecipant}/edit', 'PartecipantsController@edit')->name('partecipant.edit');
    Route::put('partecipants/{partecipant}', 'PartecipantsController@update')->name('partecipant.update');
    Route::delete('partecipants/{partecipant}', 'PartecipantsController@destroy')->name('partecipant.destroy');
});

Route::post('partecipants', 'PartecipantsController@store')->name('partecipant.store');
Route::get('partecipants/{slug}', 'PartecipantsController@show')->name('partecipant.show');

Route::resource('newsletters', 'NewslettersController', [
    'names' => [
        'index' => 'newsletter.index',
        'create' => 'newsletter.create',
        'store' => 'newsletter.store',
    ],
]);
Route::get('newsletters/{slug}', 'NewslettersController@show')->name('newsletter.show');

Route::get('corsi/scheda/1', 'PartecipantsController@scheda1')->name('scheda-1');
Route::get('corsi/scheda/2', 'PartecipantsController@scheda2')->name('scheda-2');
Route::get('corsi/scheda/3', 'PartecipantsController@scheda3')->name('scheda-3');

Route::get('corsi/scheda/test', function () {
    return view('forms.scheda-test')->with(['regions' => \App\Region::all(), 'courses' => \App\Course::where('end_date', '>', \Carbon\Carbon::today())->get()]);
})->name('scheda-test')->middleware('auth');
Route::get('newsletters/create/test', function () {
    return view('newsletters.create-test')->with(['regions' => \App\Region::all()]);
})->name('scheda-test')->middleware('auth');

Route::get('getsubscriptions/{user_id?}', 'SubscriptionsController@getSubscriptions');
Route::post('subscribe', 'SubscriptionsController@subscribe');
Route::post('unsubscribe', 'SubscriptionsController@unsubscribe');

// Homepage
Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['as' => 'coupon.', 'middleware' => 'web'], function(){
    Route::get('coupon/check', 'CouponController@check')->name('check');
});
