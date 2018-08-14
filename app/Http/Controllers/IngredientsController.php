<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IngredientsController extends Controller {
  public function index() {
    return response()->json([
      'salad' => 0,
      'bacon' => 0,
      'cheese' => 0,
      'meat' => 0,
    ], 200);
  }
}
