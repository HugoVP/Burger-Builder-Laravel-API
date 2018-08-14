<?php

use Faker\Generator as Faker;

const BASE_PRICE = 4;

const INGREDIENT_PRICES = [
  'salad' => 0.5,
  'cheese' => 0.4,
  'meat' => 1.3,
  'bacon' => 0.7,
];

$factory->define(App\Order::class, function (Faker $faker) {
  $order = [
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

  $order['price'] = BASE_PRICE
  + INGREDIENT_PRICES['salad'] * $order['salad']
  + INGREDIENT_PRICES['cheese'] * $order['cheese']
  + INGREDIENT_PRICES['meat'] * $order['meat']
  + INGREDIENT_PRICES['bacon'] * $order['bacon'];

  return $order;
});
