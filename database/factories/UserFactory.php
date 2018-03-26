
<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => str_random(8),
        'email' => $faker->safeEmail(),
        'password' => bcrypt(uniqid()),
        'api_token' => strtolower(md5(uniqid())).strtolower(md5(uniqid())),
        'telegram_chat_id' => strtolower(md5(uniqid())),
        'active' => 1,
    ];
});
