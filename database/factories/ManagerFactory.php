<?php

use Faker\Generator as Faker;

$factory->define(App\Manager::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'api_token' => strtolower(md5(uniqid())).strtolower(md5(uniqid())),
        'active' => 1,
    ];
});
