<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modelos\Comentario;
use Faker\Generator as Faker;

$factory->define(Comentario::class, function (Faker $faker) {
    return [
        'titulo' => $faker->word,
        'comentario' => $faker->sentence,
        'users_id' => $faker->numberBetween($min = 1, $max = 50),
        'producto_id' => $faker->numberBetween($min = 1, $max = 50)
    ];
});
