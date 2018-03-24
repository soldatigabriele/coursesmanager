<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Partecipant::class, function (Faker $faker) {
	

	// $faker = Faker::create('it_IT');
	$data = [];
	$data['job'] = $faker->jobTitle;
	$trans = ['auto', 'treno', 'bici'];
	$data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
	$source = ['facebook', 'sito', 'amici'];
	$data['source'] = $source[mt_rand(0, count($source) - 1)];
	$shares = ['si', 'no'];
	$data['shares'] = $shares[mt_rand(0, count($shares) - 1)];
	$data['transport'] = 'car';
	$data['region'] = $faker->state;
	$data['city'] = $faker->city;
	$data['fiscal_code'] = $faker->taxId;
	$food = ['veget', 'vegano', 'onnivoro'];
	$data['food'] = $food[mt_rand(0, count($food) - 1)];

    return [
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'email' => $faker->unique()->safeEmail,
        'phone' => '3'.rand(111111111, 999999999),
        'data' => json_encode($data),
    ];
});
