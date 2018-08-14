<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
  return [
    'country' => $faker->country,
    'delivery_method' => $faker->randomElement(['fastest', 'cheapest']),
    'email' => $faker->safeEmail,
    'name' => $faker->name,
    'street' => $faker->streetAddress,
    'zip_code' => $faker->randomNumber(5),
    'bacon' => $faker->randomDigitNotNull,
    'cheese' => $faker->randomDigitNotNull,
    'meat' => $faker->randomDigitNotNull,
    'salad' => $faker->randomDigitNotNull,
  ];
});
