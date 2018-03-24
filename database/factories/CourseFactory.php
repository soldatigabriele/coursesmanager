<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {
    return [
        'long_id' => strtoupper(str_random(6)),
        'description' => $faker->sentence,
        'user_id' => function(){
        	return factory('App\User')->create()->id;
        },
        'date' => date('d/m/Y').' al '.date('d/m/Y'),
        'limit' => rand(10, 20),
    ];
});
