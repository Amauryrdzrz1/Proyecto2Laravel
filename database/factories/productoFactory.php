<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Modelos\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
    return [
        'nombre' => $faker->colorName,
        'precio' => $faker->randomDigitNotNull
    ];
});
