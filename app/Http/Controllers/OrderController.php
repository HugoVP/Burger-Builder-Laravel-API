<?php

namespace App\Http\Controllers;

use Validator;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), self::$rules);

        if ($validator->fails()) {
          return response()->json($validator->errors(), 400);
        }

        $order = new Order;
        $order->country = $request->country;
        $order->delivery_method = $request->delivery_method;
        $order->email = $request->email;
        $order->name = $request->name;
        $order->street = $request->street;
        $order->zip_code = $request->zip_code;
        $order->bacon = $request->bacon;
        $order->cheese = $request->cheese;
        $order->meat = $request->meat;
        $order->salad = $request->salad;
        $order->price = $request->price;

        if (!$order->save()) {
          return response()->json(['error' => 'order_not_saved'], 500);
        }

        return response()->json($order, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      try {
        $order = Order::findOrFail($id);
      } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'order_not_found'], 404);
      }

      return response()->json($order, 200);
    }
}
