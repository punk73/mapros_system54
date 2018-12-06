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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Board::class, function (Faker\Generator $faker) {
    return [
        'board_id' => $faker->randomDigit(24),
        'modelname' => $faker->unique()->randomDigit,
        'lotno' => $faker->unique()->randomDigit,
        'scanner_id' => factory(App\Scanner::class)->create()->id,
        'status' => $faker->randomElement(['IN','OUT']),
        'judge' => $faker->randomElement(['OK','NG', 'REWORK']),
        'scan_nik' => $faker->randomDigit
	];
});
