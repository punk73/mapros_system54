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
$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'ticket_no' => $faker->randomDigit(24),
        'guid_master' => $faker->randomDigit(24),
        'guid_ticket' => $faker->randomDigit(24),
        'scanner_id' => factory(App\Scanner::class)->create()->id,
        'status' => $faker->randomElement(['IN','OUT']),
        'judge' => $faker->randomElement(['OK','NG', 'REWORK']),
        'scan_nik' => $faker->randomDigit
	];
});
