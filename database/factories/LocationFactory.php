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
$factory->define(App\ModelHeader::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->randomElement(['DDXGT','DPXGT']),
    ];
});

$factory->define(App\Pwb::class, function (Faker\Generator $faker) {
    return [
        'model_header_id' => factory(App\ModelHeader::class)->create()->id,
        'name' => $faker->name,
    ];
});


$factory->define(App\Location::class, function (Faker\Generator $faker) {
    return [
        'pwb_id' => factory(App\Pwb::class)->create()->id,
        'ref_no' => $faker->name,
	];
});
