<?php

use Faker\Generator as Faker;

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => str_random(8),
        'email' => $faker->safeEmail(),
        'password' => bcrypt(uniqid()),
        'telegram_chat_id' => strtolower(md5(uniqid())),
        'telegram_settings' => json_encode(['telegram_chat_id' => strtolower(md5(uniqid())), 'silent' => true, 'disable_notification' => false]),
        'active' => 1,
    ];
});
