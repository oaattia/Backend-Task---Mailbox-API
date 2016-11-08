<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->name,
        'email'     => $faker->email,
        'api_token' => \Illuminate\Support\Facades\Hash::make(str_random(22))
    ];
});


$factory->define(App\Message::class, function (Faker\Generator $faker) {
    return [
        "uid"       => $faker->uuid,
        "sender"    => $faker->firstName . ' ' . $faker->lastName,
        "subject"   => $faker->title,
        "message"   => $faker->text(100),
        'read'      => $faker->boolean,
        'archived'  => $faker->boolean,
        "time_sent" => $faker->unixTime
    ];
});
