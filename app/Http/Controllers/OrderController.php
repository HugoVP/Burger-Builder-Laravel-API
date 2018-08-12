<?php

namespace App\Http\Controllers;

use Log;
use Validator;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{ 
    protected static $rules = [
      'country' => 'required',
      'delivery_method' => 'required',
      'email' => 'required',
      'name' => 'required',
      'street' => 'required',
      'zip_code' => 'required',
      'bacon' => 'required',
      'cheese' => 'required',
      'meat' => 'required',
      'salad' => 'required',
      'price' => 'required',
    ];

    /**
     * Create a new OrderController instance.
     *
     * @return void
     */
    public function __construct() {
      $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
      $orders = Order::all();
      
      $orders = $orders->mapWithKeys(function ($order) {
        return [
          $order['id'] => [
            'formData' => [
              'country' => $order['country'],
              'delivery_method' => $order['delivery_method'],
              'email' => $order['email'],
              'name' => $order['name'],
              'street' => $order['street'],
              'zip_code' => $order['zip_code'],
            ],
            'ingredients' => [
              'bacon' => $order['bacon'],
              'cheese' => $order['cheese'],
              'meat' => $order['meat'],
              'salad' => $order['salad'],
            ],            
            'price' => $order['price'],
          ],
        ];
      });

      return response()->json($orders, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
      $inputs = [
        'country' => $request->formData['country'],
        'delivery_method' => $request->formData['deliveryMethod'],
        'email' => $request->formData['email'],
        'name' => $request->formData['name'],
        'street' => $request->formData['street'],
        'zip_code' => $request->formData['zipCode'],
        'bacon' => $request->ingredients['bacon'],
        'cheese' => $request->ingredients['cheese'],
        'meat' => $request->ingredients['meat'],
        'salad' => $request->ingredients['salad'],
        'price' => $request->price,
      ];

      $validator = Validator::make($inputs, self::$rules);

      if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
      }

      $order = new Order;
      $order->country = $inputs['country'];
      $order->delivery_method = $inputs['delivery_method'];
      $order->email = $inputs['email'];
      $order->name = $inputs['name'];
      $order->street = $inputs['street'];
      $order->zip_code = $inputs['zip_code'];
      $order->bacon = $inputs['bacon'];
      $order->cheese = $inputs['cheese'];
      $order->meat = $inputs['meat'];
      $order->salad = $inputs['salad'];
      $order->price = $inputs['price'];

      if (!$order->save()) {
        return response()->json(['error' => 'order_not_saved'], 500);
      }

      return response()->json($inputs, 200);
    }
}
