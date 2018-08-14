<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

const BASE_PRICE = 4;

const INGREDIENT_PRICES = [
  'salad' => 0.5,
  'cheese' => 0.4,
  'meat' => 1.3,
  'bacon' => 0.7,
];

class Order extends Model {
  
  /* Calculate 'price' property */
  public function getPriceAttribute() {
    return BASE_PRICE
      + INGREDIENT_PRICES['salad'] * $this->salad
      + INGREDIENT_PRICES['cheese'] * $this->cheese
      + INGREDIENT_PRICES['meat'] * $this->meat
      + INGREDIENT_PRICES['bacon'] * $this->bacon;
  }

  /**
   * Get the user that the order owns.
   */
  public function user() {
    return $this->belongsTo(User::class);
  }
}
