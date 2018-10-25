<?php

use App\Course;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Coupon::class, function (Faker $faker) {

    $course = App\Course::inRandomOrder()->first();
    $course = factory('App\Course')->create();
    $course->partecipants()->saveMany(factory('App\Partecipant', 10)->create());
    $partecipantId = $course->partecipants()->inRandomOrder()->first()->id;

    return [
        'value' => str_random(5),
        'active' => true,
        'usages' => 0,
        'course_id' => $course->id,
        'partecipant_id' => $partecipantId,
        'created_at' => Carbon::now(),
    ];
});
