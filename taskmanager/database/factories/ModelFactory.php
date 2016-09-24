<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'display_name' => $faker->firstName,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence($nbWords = 4, $variableNbWords = true),
        'description' => $faker->optional()->text($maxNbChars = 100),
        'status' => $faker->numberBetween($min = 0, $max=3),
        'priority' => $faker->numberBetween($min=0, $max=20),
        'time_estimated' => $faker->optional()->numberBetween($min=1, $max=100),
        'due_date' => $faker->optional()->dateTimeThisYear,
        'created_by' => $faker->randomDigitNotNull
    ];
});

$factory->define(App\Subtask::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'complete' => $faker->boolean,
        'priority' => $faker->numberBetween($min=0, $max=10),
    ];
});

$factory->define(App\LoggedTime::class, function (Faker\Generator $faker) {
    return [
        'start_date_time' => $faker->dateTimeThisYear,
        'notes' => $faker->optional()->sentence($nbWords = 7, $variableNbWords = true),
        'time_logged' => $faker->numberBetween($min=1, $max=10),
    ];
});

