<?php

// Authentication Routes
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

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

Route::middleware('auth')->group(function () {
    Route::get('partecipants', 'PartecipantsController@index')->name('partecipant.index');
    Route::get('partecipants/deleted', 'PartecipantsController@deleted')->name('partecipant.deleted');
    Route::get('partecipants/create', 'PartecipantsController@create')->name('partecipant.create');
    Route::get('partecipants/{partecipant}/edit', 'PartecipantsController@edit')->name('partecipant.edit');
    Route::put('partecipants/{partecipant}', 'PartecipantsController@update')->name('partecipant.update');
    Route::delete('partecipants/{partecipant}', 'PartecipantsController@destroy')->name('partecipant.destroy');
    Route::post('partecipants/{partecipant}/restore', 'PartecipantsController@restore')->name('partecipant.restore');
});

Route::post('partecipants', 'PartecipantsController@store')->name('partecipant.store');
Route::get('partecipants/{slug}', 'PartecipantsController@show')->name('partecipant.show');

// Estrattore email
Route::get('email-extractor', function(){
    return view('email-extractor');
})->name('email-extractor');


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
    Route::get('coupon/unset', 'CouponController@unset')->name('unset');
});
