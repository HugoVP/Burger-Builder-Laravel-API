<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
  /**
   * Get the user that the order owns.
   */
  public function user() {
    return $this->belongsTo(User::class);
  }
}
