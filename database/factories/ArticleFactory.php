<?php

/*
|--------------------------------------------------------------------------
| Article Factories
|--------------------------------------------------------------------------
|
| Here you may define Article model factories. Field factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\Models\Article::class, function (Faker\Generator $faker) {
    return [
        'judul' => $faker->name,
        'summary' => $faker->name,
        'deskripsi' => $faker->name,
        'penulis' => $faker->name
    ];
});
