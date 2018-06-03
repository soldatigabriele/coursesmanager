<?php

use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(App\Partecipant::class, function (Faker $faker) {

    // $faker = Faker::create('it_IT');
    $data = [];
    $data['job'] = $faker->jobTitle;
    $trans = ['auto', 'treno', 'bici'];
    $data['transport'] = $trans[mt_rand(0, count($trans) - 1)];
    $source = ['facebook', 'sito', 'amici'];
    $data['source'] = $source[mt_rand(0, count($source) - 1)];
    $data['shares'] = random_int(0, 1);
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
        'phone' => '3' . rand(111111111, 999999999),
        'data' => json_encode($data),
        'meta' => json_encode(['ip' => $faker->ipv4]),
        'created_at' => Carbon::now()->subMinutes(rand(10, 40000)),
    ];
});
