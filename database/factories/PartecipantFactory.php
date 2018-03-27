<?php

use Carbon\Carbon;
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
	$data['city'] = $faker->city;
	$data['fiscal_code'] = $faker->taxId;
	$food = ['veget', 'vegano', 'onnivoro'];
	$data['food'] = $food[mt_rand(0, count($food) - 1)];

    return [
        'slug' => str_random(20),
        'name' => $faker->firstName,
        'surname' => $faker->lastName,
        'region_id' => App\Region::inRandomOrder()->first()->id,
        'email' => $faker->unique()->safeEmail,
        'phone' => '3'.rand(111111111, 999999999),
        'data' => json_encode($data),
        'meta' => json_encode(['ip'=>$faker->ipv4]),
        'created_at' => Carbon::now()->subMinutes(rand(10, 40000)),
    ];
});
