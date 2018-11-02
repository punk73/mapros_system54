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
$factory->define(App\ColumnSetting::class, function (Faker\Generator $faker) {
    return [
    	'id' => 1,
        'name' => 'master',
        'dummy_column' => 'ticket_no_master',
        'table_name' => 'masters',
        'code_prefix' => 'MAMST',
        'level'	=> 1, //highest
        'created_at' => '2018-08-13 13:58:46',
        'updated_at' => '2018-08-13 13:58:46',
	];
});
