<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class OrderController extends Controller
{ 
    protected const RULES = [
      'country' => 'required|regex:/^[\\p{L}\s.]{1,100}$/u',
      'delivery_method' => 'required|in:fastest,cheapest',
      'email' => 'required|email|max:100',
      'name' => 'required|regex:/^[\\p{L}\s]{1,100}$/u',
      'street' => 'required|regex:/^[\\p{L}\s.0-9]{1,100}$/u',
      'zip_code' => 'required|digits:5',
      'bacon' => 'required|integer|max:255',
      'cheese' => 'required|integer|max:255',
      'meat' => 'required|integer|max:255',
      'salad' => 'required|integer|max:255',
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
      $orders = auth()->user()->orders->mapWithKeys(function ($order) {
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
            'localId' => $order['user_id'],
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
      ];

      $validator = Validator::make($inputs, self::RULES);

      if ($validator->fails()) {
        return response()->json(['error' => ['message' => $validator->errors()->first()]], 400);
      }

      $order = new Order;
      $order->user_id = auth()->user()->id;
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

      if (!$order->save()) {
        return response()->json(['error' => ['message' => 'order_not_saved']], 500);
      }

      return response(NULL, 204);
    }
}
