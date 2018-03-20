<?php

use Faker\Generator as Faker;

$factory->define(App\Course::class, function (Faker $faker) {

	$data = [];
	$data['city'] = $faker->city;
	$data['job'] = $faker->jobTitle;
	$data['transport'] = 'car';
	$data['region'] = $faker->state;
	$data['city'] = $faker->city;
	$data['fiscal_code'] = $faker->taxId;
    
    return [
        'long_id' => strtoupper(str_random(5)),
        'description' => $faker->sentence,
        'date' => date('d/m/Y').' al '.date('d/m/Y'),
        'limit' => rand(1, 20),
    ];
});
